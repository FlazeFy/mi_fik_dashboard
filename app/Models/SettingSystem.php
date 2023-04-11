<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingSystem extends Model
{
    use HasFactory;
    public $incrementing = false;

    protected $table = 'settings_systems';
    protected $primaryKey = 'id';
    protected $fillable = ['id','DCD_range', 'DTD_range', 'created_at', 'created_by', 'updated_at', 'updated_by'];

    public static function getJobsSetting(){
        $res = SettingSystem::select('id','DCD_range', 'DTD_range', 'updated_at', 'updated_by')
            ->get();

        return $res;
    }
}
