<?php

namespace App\Http\Controllers;

use App\Models\Teams;
use App\Services\AuthService;
use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    // ----------------------------------------------------------------AUTH-ADMIN
    public function authAdmin(Request $request): JsonResponse
    {
        $resp = $this->authService->authAdmin($request);

        return response()->json($resp, $resp['status']);
    }
    public function logoutAdmin(Request $request)
    {
        $this->authService->logoutAdmin($request);
    }

    //----------------------------------------------------------------Auth
    public function authApp(Request $request)
    {
        $resp = $this->authService->authApp($request);

        return response()->json($resp, $resp['status']);
    }
    public function logoutApp(Request $request, SettingsService $settings)
    {
        $this->authService->logoutApp($request);
    }
}
