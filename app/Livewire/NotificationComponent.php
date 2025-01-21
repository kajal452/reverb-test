<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageSendEvent;

class NotificationComponent extends Component
{
    // Declare a property to store the list of messages
    public $notifications = [];
    public $user_id;
    public $sender_id;
    public $receiver_id;
    public $message = '';
    public $user;

    public function mount()
    {
        $this->sender_id = auth()->user()->id;

        // $notifications = Message::where(function($query) {
        //     // Sender is the other user, receiver is the authenticated user
        //     $query->where('receiver_id', $this->sender_id);
        // })->with('sender', 'receiver')
        // ->get();
        // foreach ($notifications as $notification) {
        //     $this->chatNotification($notification);
        // }
        // dd($messages->toArray());

    }

    public function render()
    {
        return view('livewire.notification-component');
    }
    
    #[On('echo-private:chat-notification.{sender_id},ChatNotification')]
    public function listenForMessage($event)
    {
        // dd($event);
        $chatNotification = Message::whereId($event['id'])->with('sender', 'receiver')->first();
        $this->chatNotification($chatNotification);
    }
    public function chatNotification($notification){
        $this->notifications[] = [
            'id' => $notification->id,
            'message' => $notification->message,
            'created_at' => $notification->created_at,
            'sender' => $notification->sender->name,
            'receiver' => $notification->receiver->name,
        ];
    }

}
