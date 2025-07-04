<?php

namespace App\Http\Controllers;

use App\Models\Teams;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    // ----------------------------------------------------------------AUTH-ADMIN
    public function AuthAdmin(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['max:255'],
            'password' => ['required'],
        ]);
        $remember = $request->has('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Возвращаем JSON с URL для редиректа
            return response()->json([
                'success' => true,
                'redirect_url' => url("/Admin"), // или ->intended() если нужно
            ]);

//            $url = url("/Admin");
//            return redirect()->intended($url);
        }

        return response()->json([
            'success' => false,
            'message' => __('Incorrect username or password'), // Исправлено на "credentials"
        ], 401); // 401 — Unauthorized
    }
    public function logoutAdmin(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/Admin/Auth');
    }

    //----------------------------------------------------------------Auth
    public function AuthApp(Request $request, SettingsService $settings)
    {
        $auth = $settings->get('auth');
        $credentials = [];
        if ($auth === 'base') {
            $credentials = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required'],
            ]);
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return response()->json([
                    'success' => true,
                    'redirect_url' => url("/Home"), // или ->intended() если нужно
                ]);
            }
        }
        if ($auth === 'token') {
            $user = Teams::where('token', $request->token)->first();

            if (!$user) {
                return response()->json(['success' => false, 'message' => __('Incorrect token')], 401);
            }

            Auth::login($user);
            $request->session()->regenerate();

            return response()->json(['success' => true, 'redirect_url' => url("/Home")]);
        }

        if ($auth === 'base') {
            return response()->json(['success' => false,'message' => __('Incorrect username or password')], 401);
        }
        if ($auth === 'token') {
            return response()->json(['success' => false,'message' => __('Incorrect token')], 401);
        }

        return response()->json(['success' => false,'message' => __('Auth error')], 500);
    }
    public function logoutApp(Request $request, SettingsService $settings)
    {
        if(!$settings->get('sidebar.Logout')){
            abort(403);
        }
        // Выход текущего пользователя из системы
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/Auth');
    }
}
