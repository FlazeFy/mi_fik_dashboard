<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Help extends Model
{
    use HasFactory;
    //use HasUuids;
    public $incrementing = false;

    protected $table = 'helps';
    protected $primaryKey = 'id';
    protected $fillable = ['id','help_type', 'help_category', 'help_body', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'];

    public static function getAboutApp(){        
        $res = Help::select('helps.id','help_body', 'helps.updated_at', 'helps.updated_by',)
            ->leftJoin('admins', 'admins.id', '=', 'helps.updated_by')
            ->where('help_type', 'about')
            ->where('help_category', 'app')
            ->get();

        return $res;
    }

    public static function getHelpListNType(){        
        $res = DB::select(DB::raw("SELECT hs.id, help_type, help_category, help_body, ac.username as username_created_by, au.username as username_updated_by, 
            hs.updated_at, hs.created_at, count(1) as total
            FROM helps hs
            LEFT JOIN admins ac ON ac.id = hs.created_by 
            LEFT JOIN admins au ON au.id = hs.updated_by 
            WHERE help_type != 'about'
            and help_type != 'contact'
            GROUP BY help_type
            ORDER BY created_at DESC"));

        return $res;
    }

    public static function getAboutHelpCategory($type){
        $res = Help::select('id')
            ->where('help_type', $type)
            ->whereNull('help_category')
            ->limit(1)
            ->get();

        if(count($res) > 0){
            foreach($res as $r){
                return $r->id;
            }
        } else {
            return null;
        }
    }

    public static function getAboutHelpType($type){
        $res = Help::select('id')
            ->where('help_type', $type)
            ->limit(1)
            ->get();

        if(count($res) > 0){
            return true;
        } else {
            return false;
        }
    }
}
