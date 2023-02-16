<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Archieve extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'archieve';
    protected $primaryKey = 'id';
    protected $fillable = ['id_user', 'archieve_name', 'created_at', 'updated_at'];
}