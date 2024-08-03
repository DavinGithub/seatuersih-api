<?php

namespace App\Services;

use App\Models\notification as NotificationModel;
use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/../../firebase-auth.json');
        $this->messaging = $factory->createMessaging();
    }


    public function sendNotification($deviceToken, $title, $body, $imageUrl)
    {
        $notification = Notification::create($title, $body, $imageUrl);

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification);

        $notify = $this->messaging->send($message);
        return $notify;
    }
    public function sendNotificationToall($title, $body, $imageUrl)
    {
        $token = User::whereNotNull('notification_token')->pluck('notification_token')->toArray();
        $notification = Notification::create($title, $body, $imageUrl);

        $message = CloudMessage::new()
            ->withNotification($notification);
        
        $messages[] = $message;
        $notify = $this->messaging->sendMulticast($messages, $token);
        return $notify;
    }
}