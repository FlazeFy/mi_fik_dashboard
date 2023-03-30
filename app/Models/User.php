<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    use HasUuids;
    use HasApiTokens;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['slug_name', 'username', 'email', 'password', 'first_name', 'last_name', 'role', 'image_url', 'created_at', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'accepted_at', 'accepted_by', 'is_accepted'];

    protected $casts = [
        'role' => 'array',
    ];

    public static function getMostUsedRole(){
        //This query must directly return at least 10 most used tag
        $res = User::select('role')
            ->whereNot('role', null)
            ->get();

        return $res;
    }
}
