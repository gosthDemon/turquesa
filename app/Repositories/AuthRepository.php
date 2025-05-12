<?php

namespace App\Repositories;

use App\Services\Service;
use App\DTOs\Auth\AuthDTO;
use App\DTOs\Auth\out\BearerDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Enums\Status\UserStatus;
use App\Models\Session;
use App\DTOs\Session\SessionDTO;

class AuthRepository extends Repository
{
    public static function login(AuthDTO $authDTO): BearerDTO | null
    {
        //update user
        $user = User::where("email", '=', $authDTO->email)->first();
        if (!$user) {
            return null;
        }
        $user->last_login_at = now();
        $user->last_login_ip = $authDTO->session->ip_address;
        $user->save();

        //Crear session
        $session = new Session();

        $bearer = $authDTO->session->token ?: bin2hex(random_bytes(32));

        $session->token = Hash::make($bearer);
        $session->user_id = $user->id;
        $session->ip_address = $authDTO->session->ip_address;
        $session->device_type = $authDTO->session->device_type;
        $session->user_agent = $authDTO->session->user_agent;
        $session->payload = $authDTO->session->payload;
        $session->expires_at = $authDTO->session->expires_at;
        $session->forced_expires_at = $authDTO->session->forced_expires_at;
        $session->is_active = $authDTO->session->is_active;
        $session->last_activity = $authDTO->session->last_activity;
        $session->location = $authDTO->session->location;
        $session->is_expired = $authDTO->session->is_expired;
        $session->browser = $authDTO->session->browser;
        $session->os = $authDTO->session->os;
        $session->is_mobile = $authDTO->session->is_mobile;
        $session->failed_attempts = $authDTO->session->failed_attempts;
        $session->created_at = now();
        $session->updated_at = now();
        $session->save();

        $bearerDTO = new BearerDTO();

        $bearerDTO->access_token = $bearer;
        $bearerDTO->token_type = "Bearer";
        $bearerDTO->expires_at = config('auth.bearer_expired_at', 3600);
        return $bearerDTO;
    }
    public static function lockUser(string $email): bool
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->status = UserStatus::Locked->get();
            $user->save();
            return true;
        }
        return false;
    }
    public static function AddAttempts(string $email): void
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->failed_attempts += 1;
            $user->save();
        }
    }
    public static function getSession(BearerDTO $bearerDTO): ?SessionDTO
    {
        $session = Session::with('user')
            ->where('expires_at', '>', now())
            ->where('is_active', true)
            ->latest('created_at')
            ->first();
        if (!$session || !Hash::check($bearerDTO->access_token, $session->token)) {
            return null;
        }
        return SessionDTO::ToDTO($session->toArray());
    }
    public static function updateSession(int $sessionId, SessionDTO $sessionDTO): bool
    {
        $session = Session::find($sessionId);
        if ($session != null) {
            $session->expires_at = $sessionDTO->expires_at;
            $session->is_active = $sessionDTO->is_active;
            $session->last_activity = now();
            return $session->save();
        }
        return false;
    }
}