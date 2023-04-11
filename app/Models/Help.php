<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

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
}
