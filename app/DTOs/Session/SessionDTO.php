<?php

namespace App\DTOs\Session;

use App\DTOs\DTO;
use App\DTOs\User\UserDTO;

class SessionDTO extends DTO
{
    public int $id = 0;
    public string $token = "";
    public ?int $user_id;
    public ?string $ip_address = null;
    public string $device_type = "";
    public ?string $user_agent = null;
    public string $payload = "";
    public ?string $expires_at = null;
    public ?string $forced_expires_at = null;

    public bool $is_active = true;
    public ?string $last_activity = null;
    public ?string $location = null;
    public bool $is_expired = false;

    public ?string $browser = null;
    public ?string $os = null;
    public bool $is_mobile = false;

    public int $failed_attempts = 0;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public UserDTO $user;
}