<?php

namespace App\Http\Middleware;

use App\Models\Message;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use Illuminate\Support\Facades\DB;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        $user = $request->user();

        if ($user) {
            $user->load('info'); // charge la relation info
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $user
                    ? [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'info' => $user->info ? $user->info->toArray() : null,
                    ]
                    : null,
                'userInfo' => $user && $user->info ? [
                    'avatar_url' => $user->info->avatar ? asset('storage/' . $user->info->avatar) : null,
                    'phone' => $user->info->phone,
                    'address' => $user->info->address,
                    'birthdate' => $user->info->birthdate,
                    'gender' => $user->info->gender,
                    'bio' => $user->info->bio,
                ] : null,
                'unreadCount' => $user
                    ? Message::where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->count()
                    : 0,
                'unreadByUser' => $user
                    ? Message::select('sender_id', DB::raw('COUNT(*) as total'))
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->groupBy('sender_id')
                    ->get()
                    : [],
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
