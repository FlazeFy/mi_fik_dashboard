<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'dictionary';
    protected $primaryKey = 'id';
    protected $fillable = ['slug_name', 'dct_name', 'dct_desc', 'dct_type', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];
}
