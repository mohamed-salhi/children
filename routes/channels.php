<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('chat-room.{roomId}', function ($user, $roomId) {
    // هنا تحدد إذا كان المستخدم مسموح له يدخل الغرفة أو لا
    return (int) $user->uuid === (int) $roomId;
});
