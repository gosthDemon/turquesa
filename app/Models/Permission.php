<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public function menuRoles()
    {
        return $this->belongsToMany(MenuRole::class, 'menu_role_permissions');
    }

    public function menuRolePermissions()
    {
        return $this->hasMany(MenuRolePermission::class);
    }
}