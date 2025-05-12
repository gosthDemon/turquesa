<?php

namespace App\DTOs\User;

use App\DTOs\DTO;
use App\DTOs\Role\RoleDTO;

class UserDTO extends DTO
{
    public int $id = 0;
    public string $name = "";
    public string $email = "";
    public string $password = "";
    public ?string $email_verified_at = null;
    public string $status = "";
    public int $failed_attempts = 0;
    public ?string $locked_until = null;
    public ?string $reset_code = null;
    public bool $reset_password = true;
    public ?string $last_login_at = null;
    public ?string $last_login_ip = null;
    public bool $was_updated = false;
    public int $role_id = 0;
    public UserDTO|int|string|null $created_by = null;
    public UserDTO|int|string|null $updated_by = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public ?RoleDTO $role;
}