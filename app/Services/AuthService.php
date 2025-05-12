<?php

namespace App\Services;

use App\Services\Service;
use App\DTOs\Auth\AuthDTO;
use App\DTOs\Auth\out\AuthLoginDTO;
use App\DTOs\Auth\Out\BearerDTO;
use App\DTOs\User\UserDTO;
use App\Enums\Response\Status;
use App\Enums\Status\UserStatus;
use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;
use App\Helpers\Responses\TransactionResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AuthService extends Service
{
    public UserRepository $userRepository;
    private int $attempts_limit = 10;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function login(AuthDTO $authDTO)
    {
        $user = $this->userRepository->getUserByEmail($authDTO->email);
        // User exist
        if ($user == null) return TransactionResponse::WARNING(Status::NotFound, "User not found");

        // User status
        if ($user->status != UserStatus::Active->get()) {
            $authDTO->status = $user->status;
            $reason = $user->status == UserStatus::Locked->value ? " due to exceeding the number of attempts" : "";
            return TransactionResponse::WARNING(Status::Forbidden, "The user is " . $user->status . $reason);
        }

        // User password 
        if (!Hash::check($authDTO->password, $user->password)) {
            $available_attempts = $this->attempts_limit - ($user->failed_attempts + 1);
            if ($available_attempts <= 0) {
                AuthRepository::LockUser($authDTO->email);
            }
            AuthRepository::AddAttempts($authDTO->email);
            return TransactionResponse::WARNING(Status::Unauthorized, "Invalid password. You have $available_attempts attempts remaining.");
        }

        // User reset password
        if ($user->reset_password) {
            $authDTO->need_reset_password = true;
            $authLoginDTO = AuthLoginDTO::ToDTO($authDTO->toArray());
            return TransactionResponse::WARNING(Status::Forbidden, "You need reset password", (array) $authLoginDTO);
        }

        $authDTO->session->expires_at != null ? $authDTO->session->expires_at : now()->addSeconds(config('auth.bearer_expired_at', 3600));

        $bearerDTO = AuthRepository::login($authDTO);

        if ($bearerDTO == null) {
            return TransactionResponse::WARNING(Status::OK, "Error in login");
        }

        return TransactionResponse::OK(Status::OK, "Init session", $bearerDTO->toArray());
    }
    public function isAuthenticate(BearerDTO $bearerDTO): bool
    {
        $is_auth = true;
        $currentDate = now();
        $bearerDTO->access_token = $this->sanitizeBearerToken($bearerDTO->access_token);

        if (empty($bearerDTO->access_token)) return false;

        $sessionDTO = AuthRepository::getSession($bearerDTO);

        if (!$sessionDTO) return false;

        if (!$sessionDTO->is_active) {
            $is_auth = false;
            return false;
        }

        if ($sessionDTO->user->status != UserStatus::Active->get()) {
            $sessionDTO->is_active = false;
            $is_auth = false;
        }

        if ($sessionDTO->forced_expires_at && $sessionDTO->forced_expires_at < $currentDate) {
            $is_auth = false;
        }

        $expiredAt = now()->subHours();
        if ($sessionDTO->last_activity && $sessionDTO->last_activity < $expiredAt) {
            $is_auth = false;
        }

        $sessionDTO->expires_at = now()->addSeconds(config('auth.bearer_expired_at', 3600));
        $sessionDTO->is_active = $is_auth;
        $this->setUserInAuth($sessionDTO->user);
        return AuthRepository::updateSession($sessionDTO->id, $sessionDTO);
    }
    private function setUserInAuth(UserDTO $userDTO)
    {
        $user = new User(
            [
                'id' => $userDTO->id,
                'email' => $userDTO->email,
                'name' => $userDTO->name,
                'status' => $userDTO->status,
                'created_at' => $userDTO->created_at,
                'updated_at' => $userDTO->updated_at
            ]
        );
        Auth::setUser($user);
    }
    //METHODS
    private function sanitizeBearerToken(string $bearerToken): string|null
    {
        if (!Str::startsWith($bearerToken, 'Bearer ')) {
            return null;
        }
        return Str::after($bearerToken, 'Bearer ');
    }
}