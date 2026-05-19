<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecureFile extends Model
{
    protected $fillable = [
        'user_id',
        'original_name',
        'stored_name',
        'mime_type',
        'file_size',
        'integrity_hash',
        'encryption_iv',
        'encrypted_content',  // ← ADD THIS
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Human-readable file size
     */
    public function getReadableSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes < 1024)
            return $bytes . ' B';
        if ($bytes < 1048576)
            return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }
}