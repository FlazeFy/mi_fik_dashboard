<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ContentDetail extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'content_detail';
    protected $primaryKey = 'id';
    protected $fillable = ['content_id', 'content_attach', 'content_tag', 'content_loc', 'created_at', 'updated_at'];
}
