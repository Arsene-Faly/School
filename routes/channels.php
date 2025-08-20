<?php

use Illuminate\Support\Facades\Broadcast;

// Routes d'authentification des channels privés
Broadcast::routes(['middleware' => ['web', 'auth']]);

/*
 * Channel pour le chat privé entre utilisateurs
 * L'utilisateur peut écouter son propre channel ou s'il est l'expéditeur
 */
Broadcast::channel('chat.{receiverId}', function ($user, $receiverId) {
    return (int) $user->id === (int) $receiverId || (int) $user->id === auth()->id();
});
