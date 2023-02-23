<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'tag';
    protected $primaryKey = 'id';
    protected $fillable = ['slug_name', 'tag_name', 'tag_desc', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];
}