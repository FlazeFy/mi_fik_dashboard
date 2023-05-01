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
    protected $fillable = ['id','DCD_range', 'DTD_range', 'DHD_range', 'FAQ_range', 'FBC_range', 'created_at', 'created_by', 'updated_at', 'updated_by'];

    public static function getJobsSetting(){
        $res = SettingSystem::select('id','DCD_range', 'DTD_range', 'DHD_range', 'updated_at', 'updated_by')
            ->get();

        return $res;
    }

    public static function getLandingSetting(){
        $res = SettingSystem::select('id','FAQ_range','FBC_range','updated_at', 'updated_by')
            ->get();

        return $res;
    }

    public static function getLimitFAQ(){
        $res = SettingSystem::select('FAQ_range')
            ->first();

        return $res->FAQ_range;
    }
}
