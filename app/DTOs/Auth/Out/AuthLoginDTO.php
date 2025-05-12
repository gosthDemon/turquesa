<?php

namespace App\DTOs\Auth\Out;

use App\DTOs\DTO;

class AuthLoginDTO extends DTO
{
    public string $email = "";
    public bool $need_reset_password = false;
}