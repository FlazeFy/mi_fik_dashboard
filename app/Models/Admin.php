<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory;
    use HasUuids;
    use HasApiTokens;

    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $fillable = ['slug_name', 'username', 'email', 'phone', 'password', 'first_name', 'last_name', 'image_url', 'created_at', 'updated_at'];
}
