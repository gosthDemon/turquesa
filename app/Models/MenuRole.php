<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuRole extends Model
{
    protected $table = 'menu_role';

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'menu_role_permission');
    }

    public function menu_role_permissions()
    {
        return $this->hasMany(MenuRolePermission::class);
    }
}