<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'passwords_resets';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'validation_token', 'new_password', 'created_at', 'created_by', 'validated_at'];
}
