<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ContentHeader extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'content_header';
    protected $primaryKey = 'id';
    protected $fillable = ['slug_name', 'content_title', 'content_desc', 'content_date_start', 'content_date_end', 'content_reminder', 'content_image', 'is_important', 'is_draft', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by', 'updated_by'];
    protected $casts = [
        'content_attach' => 'array',
        'content_tag' => 'array',
        'content_loc' => 'array'
    ];
}
