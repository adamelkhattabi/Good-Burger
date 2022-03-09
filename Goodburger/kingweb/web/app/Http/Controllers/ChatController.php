<?php

namespace App\Http\Controllers;

use App\Lang;
use App\Logging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImageUpload;
use Auth;
use App\Chat;

class ChatController extends Controller
{
    public function getChatMessagesNewCount(Request $request)
    {
        if (!Auth::check())
            response()->json(['error' => "1",], 200);

        return response()->json([
            'error' => "0",
            'count' => Chat::getUserUnreadMessagesCount(),
        ], 200);
    }

    public function load(Request $request)
    {
        if (!Auth::check())
            return \Redirect::route('/');
        return view('chat');
    }

    public function getChatMessages(Request $request)
    {
        $user = Auth::user();
        if ($user == null)
            response()->json(['error' => "1",], 200);

        return response()->json([
            'error' => "0",
            'messages' => Chat::getUserAllMessages()
        ], 200);
    }

    public function chatSendMessage(Request $request)
    {
        if (!Auth::check())
            response()->json(['error' => "1",], 200);

        Chat::NewUserMessage($request->input('text'));

        return response()->json([
            'error' => "0",
            'messages' => Chat::getUserAllMessages()
        ], 200);
    }
}
