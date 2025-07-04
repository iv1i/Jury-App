<?php

namespace App\Http\Middleware;

use App\Models\Settings;
use App\Services\SettingsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSidebarAccess
{

    public function __construct(protected SettingsService $settings)
    {
    }
    public function handle(Request $request, Closure $next, string $sidebarItem): Response
    {
        if(!$this->settings->get("sidebar.{$sidebarItem}")) {
            abort(403);
        }
        return $next($request);
    }
}
