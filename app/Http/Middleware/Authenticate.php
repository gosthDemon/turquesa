<?php

namespace App\Http\Middleware;

use App\DTOs\Auth\out\BearerDTO;
use App\Enums\Response\Status;
use App\Helpers\Responses\TransactionResponse;
use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $authorizationHeader = $request->header('Authorization');
            if (!$authorizationHeader) {
                return TransactionResponse::WARNING(Status::Unauthorized, "Missing Authorization header")->json();
            }
            $bearerDTO = new BearerDTO();
            $bearerDTO->access_token = $authorizationHeader;
            if (!$this->authService->isAuthenticate($bearerDTO)) {
                return TransactionResponse::WARNING(Status::Unauthorized, "Invalid or expired token")->json();
            }
            return $next($request);
        } catch (\Exception $e) {
            return TransactionResponse::ERROR(Status::InternalServerError, "Internal error server", $e)->json();
        }
    }
}