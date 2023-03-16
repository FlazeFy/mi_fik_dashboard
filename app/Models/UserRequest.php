<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'users_requests';
    protected $primaryKey = 'id';
    protected $fillable = ['tag_slug_name', 'request_type', 'created_at', 'created_by', 'is_rejected', 'rejected_by', 'rejected_at', 'is_accepted', 'accepted_by', 'accepted_at'];

    protected $casts = [
        'tag_slug_name' => 'array'
    ];
}