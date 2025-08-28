<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\Teams;
use App\Services\AuthService;
use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService, protected SettingsService $settings)
    {
    }

    // ----------------------------------------------------------------AUTH-ADMIN
    public function authAdmin(AuthRequest $request): JsonResponse
    {
        $resp = $this->authService->authAdmin($request);

        return response()->json($resp, $resp['status']);
    }
    public function logoutAdmin(Request $request)
    {
        $this->authService->logoutAdmin($request);
    }

    //----------------------------------------------------------------Auth
    public function authApp(AuthRequest $request)
    {
        $resp = $this->authService->authApp($request);

        return response()->json($resp, $resp['status']);
    }
    public function logoutApp(Request $request, SettingsService $settings)
    {
        if(!$this->settings->get('sidebar.Logout')){
            abort(403);
        }

        $resp = $this->authService->logoutApp($request);

        return redirect($resp);
    }
}
