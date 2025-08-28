<?php

namespace App\Services;

use App\Http\Requests\AuthRequest;
use App\Models\Teams;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthService
{
    public function __construct(private SettingsService $settings)
    {
    }

    // ----------------------------------------------------------------AUTH-ADMIN
    public function authAdmin(AuthRequest $request): array
    {
        $credentials = $request->validated();
        $remember = $request->has('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return [
                'success' => true,
                'redirect_url' => url("/Admin"),
                'status' => 200
            ];
        }

        return [
            'success' => false,
            'message' => __('Incorrect username or password'),
            'status' => 401
        ];
    }
    public function logoutAdmin(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/Admin/Auth');
    }

    //----------------------------------------------------------------Auth
    public function authApp(AuthRequest $request): array
    {
        $auth = $this->settings->get('auth');
        if ($auth === 'base') {
            $credentials = $request->validated();
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return [
                    'success' => true,
                    'redirect_url' => url("/Home"),
                    'status' => 200
                ];
            }
        }

        if ($auth === 'token') {
            $user = Teams::where('token', $request->token)->first();

            if (!$user) {
                return ['success' => false, 'message' => __('Incorrect token'), 'status' => 401];
            }

            Auth::login($user);
            $request->session()->regenerate();

            return ['success' => true, 'redirect_url' => url("/Home"), 'status' => 200];
        }

        if ($auth === 'base') {
            return ['success' => false,'message' => __('Incorrect username or password'), 'status' => 401];
        }

        if ($auth === 'token') {
            return ['success' => false,'message' => __('Incorrect token'), 'status' => 401];
        }

        return ['success' => false,'message' => __('Auth error'), 'status' => 401];
    }
    public function logoutApp(Request $request): string
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return '/Auth';
    }
}
