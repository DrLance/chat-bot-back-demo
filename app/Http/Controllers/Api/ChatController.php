<?php

declare(strict_types=1);


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Enum\UserRolesEnum;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use React\EventLoop\Loop as EventLoop;
use React\ZMQ\Context;
use ZMQ;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate(
            [
                'message' => 'required',
                'chatId'  => 'required|exists:chats,id',
            ]
        );

        $message = $request->get('message');
        $chatId  = $request->get('chatId');
        $user    = Auth::user();

        $messages = new Message();

        $messages->user_id = $user->id;
        $messages->chat_id = $chatId;
        $messages->message = $message;

        $messages->save();

        $loop    = EventLoop::get();
        $context = new Context($loop);

        $push = $context->getSocket(ZMQ::SOCKET_PUSH);
        $push->connect('tcp://127.0.0.1:5555');

        $push->send(
            json_encode(
                [
                    'user_id' => $user->id,
                    'chat_id' => $chatId,
                    'message' => $message,
                ]
            )
        );

        return response()->json(
            [
                'messages' => Message::where('chat_id', $chatId)->get(),
                'user'     => $user,
            ]
        );
    }

    public function getMessages(Request $request)
    {
        $validated = $request->validate(
            [
                'chatId' => 'required|exists:chats,id',
            ]
        );

        $chatId = $request->get('chatId');
        $user   = Auth::user();

        $messages = Message::where('chat_id', $chatId)->get();

        return response()->json(
            [
                'messages' => $messages,
                'user'     => $user,
            ]
        );
    }

    public function create(Request $request)
    {
        $chatName = $request->get('chatName');
        $user     = Auth::user();

        $chat = Chat::where('owner_id', $user->id)->where('name', $chatName)->first();

        if (!$chat) {
            $chat = new Chat();
        }

        $chat->name     = $chatName;
        $chat->owner_id = $user->id;
        $chat->slug     = Str::slug($chatName);

        $chat->save();

        return response()->json(['chats' => Chat::whereOwnerId($user->id)->get()]);
    }

    public function getChats(Request $request)
    {
        $user = Auth::user();

        if ($user->roles === UserRolesEnum::ADMIN->value) {
            return response()->json(['chats' => Chat::all()]);
        }

        return response()->json(['chats' => Chat::whereOwnerId($user->id)->get()]);
    }
}
