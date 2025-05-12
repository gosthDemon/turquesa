<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuRolePermission extends Model
{
    protected $table = 'menu_role_permission';

    public function menuRole()
    {
        return $this->belongsTo(MenuRole::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}