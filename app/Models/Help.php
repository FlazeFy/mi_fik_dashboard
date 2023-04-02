<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    use HasFactory;
    //use HasUuids;

    protected $table = 'helps';
    protected $primaryKey = 'id';
    protected $fillable = ['id','help_category', 'help_body', 'help_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'];
}
