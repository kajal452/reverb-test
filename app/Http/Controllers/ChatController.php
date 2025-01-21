<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ChatController extends Controller
{
    public function dashboard(){
        $users = User::where('id','!=',auth()->user()->id)->get();
        return view('dashboard',[
            'users' => $users,
        ]);
    }
    public function chat($id){
        $user = User::where('id', $id)->get();

        return view('chat',[
            'id' => $id,
            'user' => $user,
        ]);
    }
}
