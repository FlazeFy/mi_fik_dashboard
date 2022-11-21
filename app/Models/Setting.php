<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'setting';
    protected $primaryKey = 'id';
    protected $fillable = ['id_user', 'MOT_range', 'MOL_range', 'CE_range', 'created_at', 'updated_at'];
}
