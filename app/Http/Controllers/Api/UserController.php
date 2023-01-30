<?php

declare(strict_types=1);


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use React\EventLoop\Loop as EventLoop;
use React\ZMQ\Context;
use ZMQ;

class UserController extends Controller
{
    public function check(Request $request)
    {
        return response()->json(
            [
                'message' => 'OK',
                'user'     => Auth::user(),
            ]
        );
    }
}
