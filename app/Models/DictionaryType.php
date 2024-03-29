<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DictionaryType extends Model
{
    use HasFactory;
    //use HasUuids;
    public $incrementing = false;

    protected $table = 'dictionaries_types';
    protected $primaryKey = 'id';
    protected $fillable = ['id','app_code', 'type_name', 'created_at'];
}
