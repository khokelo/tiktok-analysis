<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UploadedFile extends Model
{
    protected $table = 'uploaded_files';

    protected $fillable = [
        'user_id',
        'file_name',
        'file_path',
        'original_name',
        'file_size',
        'row_count',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that uploaded the file
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
