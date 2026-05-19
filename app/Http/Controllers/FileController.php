<?php

namespace App\Http\Controllers;

use App\Models\SecureFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FileController extends Controller
{
    private const CIPHER = 'AES-256-CBC';
    private const KEY_LENGTH = 32;

    public function dashboard()
    {
        $files = Auth::user()
            ->files()
            ->latest()
            ->get();

        return view('dashboard', compact('files'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:10240',
                'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,gif,txt,csv,zip',
            ],
        ]);

        $uploadedFile = $request->file('file');


        $originalContents = file_get_contents($uploadedFile->getRealPath());


        $integrityHash = hash('sha256', $originalContents);

        $iv = random_bytes(16);
        $key = $this->getDerivedKey();
        $encryptedData = openssl_encrypt($originalContents, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);

        if ($encryptedData === false) {
            return back()->withErrors(['file' => 'Encryption failed. Please try again.']);
        }

        $storedName = Str::uuid() . '.enc';
        $encryptedContent = base64_encode($encryptedData);


        Auth::user()->files()->create([
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_name' => $storedName,
            'mime_type' => $uploadedFile->getMimeType(),
            'file_size' => $uploadedFile->getSize(),
            'integrity_hash' => $integrityHash,
            'encryption_iv' => base64_encode($iv),
            'encrypted_content' => $encryptedContent,
        ]);

        return back()->with('success', '✓ File encrypted and uploaded securely.');
    }

    public function download(SecureFile $file)
    {
        if ($file->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        if (!$file->encrypted_content) {
            abort(404, 'File not found.');
        }

        $encryptedData = base64_decode($file->encrypted_content);

        $key = $this->getDerivedKey();
        $iv = base64_decode($file->encryption_iv);
        $decryptedData = openssl_decrypt($encryptedData, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);

        if ($decryptedData === false) {
            abort(500, 'Decryption failed. File may be corrupted.');
        }

        $computedHash = hash('sha256', $decryptedData);

        if (!hash_equals($file->integrity_hash, $computedHash)) {
            abort(422, 'INTEGRITY VIOLATION: File hash mismatch. The file may have been tampered with.');
        }

        return response()->streamDownload(
            function () use ($decryptedData) {
                echo $decryptedData;
            },
            $file->original_name,
            ['Content-Type' => $file->mime_type]
        );
    }

    public function delete(SecureFile $file)
    {
        if ($file->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $file->delete();

        return back()->with('success', '✓ File deleted successfully.');
    }

    private function getDerivedKey(): string
    {
        $appKey = config('app.key');

        if (str_starts_with($appKey, 'base64:')) {
            $appKey = base64_decode(substr($appKey, 7));
        }
        
        return substr(str_pad($appKey, self::KEY_LENGTH, "\0"), 0, self::KEY_LENGTH);
    }
}