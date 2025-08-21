<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $receiverId = $request->query('receiver');

        // Liste des utilisateurs avec qui l’utilisateur a des messages, avec date du dernier message
        $usersWhoMessaged = User::where('id', '!=', $userId)
            ->where(function ($q) use ($userId) {
                $q->whereHas('sentMessages', fn($q) => $q->where('receiver_id', $userId))
                    ->orWhereHas('receivedMessages', fn($q) => $q->where('sender_id', $userId));
            })
            ->with([
                'sentMessages' => fn($q) => $q->where('receiver_id', $userId)->latest(),
                'receivedMessages' => fn($q) => $q->where('sender_id', $userId)->latest()
            ])
            ->get()
            ->map(function ($user) use ($userId) {
                // Calculer la date du dernier message avec cet utilisateur
                $lastSent = $user->sentMessages->first()?->created_at;
                $lastReceived = $user->receivedMessages->first()?->created_at;
                $lastMessageAt = $lastSent && $lastReceived
                    ? max($lastSent->timestamp, $lastReceived->timestamp)
                    : ($lastSent ?? $lastReceived);

                $user->lastMessageAt = $lastMessageAt ? \Carbon\Carbon::createFromTimestamp($lastMessageAt) : null;
                return $user;
            })
            ->sortByDesc('lastMessageAt')
            ->values();

        $receiver = $receiverId ? User::find($receiverId) : null;

        $messages = [];
        if ($receiver) {
            $messages = Message::where(function ($q) use ($userId, $receiverId) {
                $q->where('sender_id', $userId)->where('receiver_id', $receiverId);
            })->orWhere(function ($q) use ($userId, $receiverId) {
                $q->where('sender_id', $receiverId)->where('receiver_id', $userId);
            })->orderBy('created_at')->get();
        }

        $admin = User::where('role', 'admin')->first();

        return Inertia::render('Messages/Index', [
            'usersWhoMessaged' => $usersWhoMessaged,
            'receiver' => $receiver,
            'messages' => $messages,
            'adminUser' => $admin,
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|min:1',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $message = Message::create([
            'content' => trim($data['content']), // on enlève les espaces inutiles
            'receiver_id' => $data['receiver_id'],
            'sender_id' => auth()->id(),
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return redirect()->back()->with([
            'recentMessage' => $message
        ]);
    }


    public function markAsRead(Request $request)
    {
        $receiverId = auth()->id();
        $senderId = $request->input('sender_id');

        // sécurité: vérifier que sender_id est bien fourni
        if (!$senderId) {
            return response()->json(['success' => false, 'message' => 'Sender ID manquant']);
        }

        Message::where('receiver_id', $receiverId)
            ->where('sender_id', $senderId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
