<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $fillable = ['slug_name', 'username', 'email', 'phone', 'password', 'first_name', 'last_name', 'image_url', 'created_at', 'updated_at'];
}
