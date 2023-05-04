<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;
    //use HasUuids;
    public $incrementing = false;

    protected $table = 'users_groups';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'slug_name', 'group_name', 'group_desc', 'created_at', 'created_by', 'updated_at', 'updated_by'];
}
