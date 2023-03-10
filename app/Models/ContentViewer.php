<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ContentViewer extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'contents_viewers';
    protected $primaryKey = 'id';
    protected $fillable = ['content_id', 'type_viewer', 'created_at', 'created_by'];
}
