<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $fillable = ['MOT_range', 'MOL_range', 'CE_range', 'created_at', 'updated_at', 'created_by', 'updated_by'];
}
