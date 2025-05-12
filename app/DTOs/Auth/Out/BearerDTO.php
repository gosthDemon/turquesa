<?php

namespace App\DTOs\Auth\Out;

use App\DTOs\DTO;

class BearerDTO extends DTO
{
    public ?string $access_token = "";
    public string $token_type = "";
    public int $expires_at = 0;
}