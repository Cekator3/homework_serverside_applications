<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\traits\SoftDeletesWithAuthor;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory, SoftDeletesWithAuthor;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'description', 'code', 'created_by'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
        'deleted_by',
    ];

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    const UPDATED_AT = null;
}
