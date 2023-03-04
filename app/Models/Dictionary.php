<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'dictionaries';
    protected $primaryKey = 'id';
    protected $fillable = ['slug_name', 'dct_name', 'dct_desc', 'dct_type', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

    public static function getDictionaryByType($type){
        if(is_array($type)){
            $arr_count = count($type);
            $query = "";
            $i = 1;

            foreach($type as $ty){
                $stmt = 'type_name = '."'".$ty."'";

                if($i != 1){
                    $query = substr_replace($query, " ".$stmt." OR", 0, 0);
                } else {
                    $query = substr_replace($query, " ".$stmt, 0, 0);
                }
                $i++;
            }

            $res = Dictionary::select('slug_name','dct_name','dct_desc','type_name')
                ->join('dictionaries_types', 'dictionaries_types.app_code', '=', 'dictionaries.dct_type')
                ->whereRaw($query)
                ->orderBy('dictionaries.created_at', 'ASC')
                ->get();
        } else {
            $res = Dictionary::select('slug_name','dct_name','dct_desc','type_name')
                ->join('dictionaries_types', 'dictionaries_types.app_code', '=', 'dictionaries.dct_type')
                ->where('type_name', $type)
                ->orderBy('dictionaries.created_at', 'ASC')
                ->get();
        }

        return $res;
    }
}
