<?php

namespace App\Http\Controllers;

use App\DTOs\Auth\AuthDTO;
use App\DTOs\Session\SessionDTO;
use App\Enums\Response\Status;
use App\Helpers\HeaderHTTP\Header;
use App\Helpers\Responses\TransactionResponse;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function index()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $re = $request->all();
        try {
            $session = (new Header($request))->buildSession();
            $auth = AuthDTO::ToDTO($request->all());
            $auth->session = SessionDTO::ToDTO($session);
            $response = $this->authService->login($auth);
            return $response->json();
        } catch (\Exception $e) {
            return TransactionResponse::ERROR(Status::InternalServerError, "Internal server error", $e)->json();
        }
    }
    public function logout(AuthDTO $request)
    {
        return $request;
    }
    public function refresh(AuthDTO $request)
    {
        return $request;
    }
}