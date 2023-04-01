<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Menu extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'menus';
    protected $primaryKey = 'id';
    protected $fillable = ['menu_group', 'menu_name', 'menu_url', 'menu_icon', 'menu_access_all', 'sort_number', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'];

    public static function getMenu(){

        $res = Menu::select('menu_group', 'menu_name', 'menu_url', 'menu_icon', 'menu_access_all', 'sort_number')
            ->orderBy('sort_number','ASC')
            ->get();

        return $res;
    }
}