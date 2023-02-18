<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ArchiveRelation extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'archive_relation';
    protected $primaryKey = 'id';
    protected $fillable = ['archive_id', 'content_id', 'created_at', 'created_by'];
}
