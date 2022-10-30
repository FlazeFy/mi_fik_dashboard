<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $table = 'content';
    protected $primaryKey = 'id';
    protected $fillable = ['id_user', 'content_title', 'content_subtitle', 'content_type', 'content_desc', 'content_attach', 'content_tag', 'content_loc', 'content_date_start', 'content_date_end', 'created_at', 'updated_at'];
}