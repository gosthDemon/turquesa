<?php

namespace App\DTOs\Auth;

use App\DTOs\DTO;
use App\DTOs\Session\SessionDTO;

class AuthDTO extends DTO
{
    public string $email = "";
    public string $password = "";
    public string $status = "";
    public bool $need_reset_password = false;
    public SessionDTO $session;
}