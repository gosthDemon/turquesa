<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "roles";

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_role');
    }

    public function menus_role()
    {
        return $this->hasMany(MenuRole::class);
    }
}