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

    public static function getChartSetting($user_id){
        $res = Setting::select('id', 'MOT_range', 'MOL_range', 'CE_range')
            ->where('created_by', $user_id)
            ->limit(1)
            ->get();

        return $res;
    }

    public static function getSingleSetting($col, $user_id){
        $res = Setting::select('id', $col)
            ->where('created_by', $user_id)
            ->limit(1)
            ->get();

        return $res;
    }
}
