<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'archives';
    protected $primaryKey = 'id';
    protected $fillable = ['slug_name', 'archive_name', 'archive_desc', 'created_at', 'updated_at', 'created_by', 'deleted_at'];
}
