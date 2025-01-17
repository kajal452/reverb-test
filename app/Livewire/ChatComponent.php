<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
class ChatComponent extends Component
{
    public function render()
    {
        return view('livewire.chat-component');
    }

    #[On('echo-private:App.Models.User.1,MessageSendEvent')]
    public function echoPrivate($data)
    {
        dd($data);
    }
}
