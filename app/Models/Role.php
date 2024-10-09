<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use App\Models\traits\SoftDeletesWithAuthor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
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

    /**
     * The roles that belong to the user.
     */
    public function permissions() : BelongsToMany
    {
        return $this->belongsToMany(Permission::class, "role_permissions");
    }
}
