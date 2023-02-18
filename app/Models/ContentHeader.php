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
    protected $fillable = ['slug_name', 'content_title', 'content_desc', 'is_important', 'is_draft', 'created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by', 'updated_by'];
}
