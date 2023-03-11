<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class GroupRelation extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'groups_relations';
    protected $primaryKey = 'id';
    protected $fillable = ['group_id', 'created_at', 'created_by'];
}
