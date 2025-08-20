<?php

namespace App\Http\Middleware;

use App\Models\SiteView;
use App\Models\SiteViewLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CountSiteViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $ip = $request->ip();

        $alreadyViewed = SiteViewLog::where(function ($query) use ($user, $ip) {
            if ($user) {
                $query->where('user_id', $user->id);
            } else {
                $query->where('ip_address', $ip);
            }
        })->exists();

        if (!$alreadyViewed) {
            // Enregistre la visite dans logs
            SiteViewLog::create([
                'user_id' => $user ? $user->id : null,
                'ip_address' => $ip,
            ]);

            // Incrémente compteur global
            $siteView = SiteView::first();
            if (!$siteView) {
                $siteView = SiteView::create(['views' => 0]);
            }
            $siteView->increment('views');
        }
        return $next($request);
    }
}
