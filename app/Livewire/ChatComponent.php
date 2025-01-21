<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use App\Models\Message;
use App\Events\MessageSendEvent;
use App\Events\ChatNotification;

class ChatComponent extends Component
{
    // Declare a property to store the list of messages
    public $messages = [];
    public $user_id;
    public $sender_id;
    public $receiver_id;
    public $message = '';
    public $user;

    public function mount($user_id)
    {
        $this->sender_id = auth()->user()->id;
        $this->receiver_id = $user_id;
        $this->user = User::where('id', $user_id)->first();

        $messages = Message::where(function($query) {
            // Sender is the authenticated user, receiver is the other user
            $query->where('sender_id', $this->sender_id)
                  ->where('receiver_id', $this->receiver_id);
        })
        ->orWhere(function($query) {
            // Sender is the other user, receiver is the authenticated user
            $query->where('sender_id', $this->receiver_id)
                  ->where('receiver_id', $this->sender_id);
        })->with('sender', 'receiver')
        ->get();
        foreach ($messages as $message) {
            $this->chatMessage($message);
        }
        // dd($messages->toArray());

    }

    public function render()
    {
        return view('livewire.chat-component');
    }

    public function sendMessage(){
        $message = new Message();
        $message->sender_id     = $this->sender_id;
        $message->receiver_id   = $this->receiver_id;
        $message->message       = $this->message;
        $message->save();
        $this->chatMessage($message);

        broadcast(new MessageSendEvent($message))->toOthers();
        broadcast(new ChatNotification($message))->toOthers();

        $this->message = '';
    }
    
    #[On('echo-private:chat-channel.{sender_id},MessageSendEvent')]
    public function listenForMessage($event)
    {
        // dd($event);
        $chatMessage = Message::whereId($event['id'])->with('sender', 'receiver')->first();
        if(isset($chatMessage->sender->id) && $this->receiver_id == $chatMessage->sender->id){
            $this->chatMessage($chatMessage);
        }
    }
    public function chatMessage($message){
        $this->messages[] = [
            'id' => $message->id,
            'message' => $message->message,
            'created_at' => $message->created_at,
            'sender' => $message->sender->name,
            'receiver' => $message->receiver->name,
        ];
    }

}
