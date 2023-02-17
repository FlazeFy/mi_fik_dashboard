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
    protected $fillable = ['id_user', 'tag_name', 'created_at', 'updated_at'];
}