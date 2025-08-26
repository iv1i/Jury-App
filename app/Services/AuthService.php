<?php

namespace App\Services;

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
    public function authAdmin(Request $request): array
    {
        $credentials = $request->validate([
            'name' => ['max:255'],
            'password' => ['required'],
        ]);
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
    public function authApp(Request $request): array
    {
        $auth = $this->settings->get('auth');
        $credentials = [];
        if ($auth === 'base') {
            $credentials = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required'],
            ]);
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
    public function logoutApp(Request $request)
    {
        if(!$this->settings->get('sidebar.Logout')){
            abort(403);
        }
        // Выход текущего пользователя из системы
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/Auth');
    }
}
