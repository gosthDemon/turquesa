<?php

namespace App\DTOs\Role;

use App\DTOs\DTO;

class RoleDTO extends DTO
{
    public int $id;
    public string $name;
    public string $slug;
    public string $description;
    public bool $is_admin;
    public string $status;
    /** @var MenuDTO[] */
    public array $menus;
}