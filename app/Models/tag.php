<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'tags';
    protected $primaryKey = 'id';
    protected $fillable = ['slug_name', 'tag_name', 'tag_desc', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

    public static function getFullTag($order_1, $order_2){
        $res = Tag::orderBy('updated_at', $order_1)
            ->orderBy('created_at', $order_2)
            ->get();
        
        return $res;
    }
}