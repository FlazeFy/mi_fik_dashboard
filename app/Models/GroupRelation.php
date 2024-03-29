<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class GroupRelation extends Model
{
    use HasFactory;
    //use HasUuids;
    public $timestamps = false;
    public $incrementing = false;

    protected $table = 'groups_relations';
    protected $primaryKey = 'id';
    protected $fillable = ['id','group_id', 'user_id', 'created_at', 'created_by'];
}
