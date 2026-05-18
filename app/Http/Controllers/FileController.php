<?php

namespace App\Http\Controllers;

use App\Models\SecureFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * ENCRYPTION CONFIGURATION
     *
     * AES-256-CBC:
     *  - AES  = Advanced Encryption Standard (symmetric cipher)
     *  - 256  = key size in bits (very strong, used by US government for TOP SECRET)
     *  - CBC  = Cipher Block Chaining mode (each block depends on the previous)
     *
     * The encryption key comes from APP_KEY in .env (32 bytes / 256 bits).
     * Each file gets its own random IV (Initialization Vector) for extra security —
     * even if two files are identical, their encrypted output will differ.
     */
    private const CIPHER      = 'AES-256-CBC';
    private const KEY_LENGTH  = 32; // bytes = 256 bits

    // ─── Dashboard ────────────────────────────────────────────────────────────
    public function dashboard()
    {
        $files = Auth::user()
            ->files()
            ->latest()
            ->get();

        return view('dashboard', compact('files'));
    }

    // ─── Upload File ──────────────────────────────────────────────────────────
    public function upload(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:10240',          // 10 MB max
                'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,gif,txt,csv,zip',
            ],
        ]);

        $uploadedFile = $request->file('file');

        // ── 1. Read raw file contents ────────────────────────────────────────
        $originalContents = file_get_contents($uploadedFile->getRealPath());

        // ── 2. Integrity: SHA-256 hash of the ORIGINAL file ─────────────────
        //    This hash is stored and later compared on download to detect tampering.
        $integrityHash = hash('sha256', $originalContents);

        // ── 3. Encrypt the file contents (AES-256-CBC) ───────────────────────
        $iv              = random_bytes(16); // 128-bit random IV per file
        $key             = $this->getDerivedKey();
        $encryptedData   = openssl_encrypt($originalContents, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);

        if ($encryptedData === false) {
            return back()->withErrors(['file' => 'Encryption failed. Please try again.']);
        }

        // ── 4. Store encrypted file on disk ──────────────────────────────────
        $storedName = Str::uuid() . '.enc'; // Opaque name — no hint of original
        Storage::disk('local')->put('secure_files/' . $storedName, $encryptedData);

        // ── 5. Save metadata in database ─────────────────────────────────────
        Auth::user()->files()->create([
            'original_name'  => $uploadedFile->getClientOriginalName(),
            'stored_name'    => $storedName,
            'mime_type'      => $uploadedFile->getMimeType(),
            'file_size'      => $uploadedFile->getSize(),
            'integrity_hash' => $integrityHash,
            'encryption_iv'  => base64_encode($iv), // Store IV alongside file record
        ]);

        return back()->with('success', '✓ File encrypted and uploaded securely.');
    }

    // ─── Download File ────────────────────────────────────────────────────────
    public function download(SecureFile $file)
    {
        // ── Authentication check: only owner can download ────────────────────
        if ($file->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $storedPath = 'secure_files/' . $file->stored_name;

        if (!Storage::disk('local')->exists($storedPath)) {
            abort(404, 'File not found on server.');
        }

        // ── 1. Read encrypted data from disk ─────────────────────────────────
        $encryptedData = Storage::disk('local')->get($storedPath);

        // ── 2. Decrypt with AES-256-CBC ───────────────────────────────────────
        $key             = $this->getDerivedKey();
        $iv              = base64_decode($file->encryption_iv);
        $decryptedData   = openssl_decrypt($encryptedData, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);

        if ($decryptedData === false) {
            abort(500, 'Decryption failed. File may be corrupted.');
        }

        // ── 3. Integrity check: re-hash and compare ───────────────────────────
        $computedHash = hash('sha256', $decryptedData);

        if (!hash_equals($file->integrity_hash, $computedHash)) {
            // Hashes don't match — file was tampered with or corrupted!
            abort(422, 'INTEGRITY VIOLATION: File hash mismatch. The file may have been tampered with.');
        }

        // ── 4. Stream decrypted file to the user ─────────────────────────────
        return response()->streamDownload(
            function () use ($decryptedData) {
                echo $decryptedData;
            },
            $file->original_name,
            ['Content-Type' => $file->mime_type]
        );
    }

    // ─── Delete File ──────────────────────────────────────────────────────────
    public function delete(SecureFile $file)
    {
        if ($file->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Delete encrypted file from disk
        Storage::disk('local')->delete('secure_files/' . $file->stored_name);

        // Delete record from database
        $file->delete();

        return back()->with('success', '✓ File deleted successfully.');
    }

    // ─── Derive encryption key from APP_KEY ───────────────────────────────────
    /**
     * Laravel's APP_KEY is base64-encoded. We decode it and ensure it's
     * exactly 32 bytes (256 bits) for AES-256. Using the app key means
     * encryption is tied to your specific Laravel installation.
     */
    private function getDerivedKey(): string
    {
        $appKey = config('app.key');

        // APP_KEY format: "base64:xxxx..."
        if (str_starts_with($appKey, 'base64:')) {
            $appKey = base64_decode(substr($appKey, 7));
        }

        // Ensure exactly 32 bytes
        return substr(str_pad($appKey, self::KEY_LENGTH, "\0"), 0, self::KEY_LENGTH);
    }
}