<?php

declare(strict_types=1);

namespace App\Services\RatchetServer;


use App\Models\Chat;
use App\Models\Enum\UserRolesEnum;
use App\Models\Message;
use App\Models\User;
use App\Models\UserSocket;
use Illuminate\Support\Collection;


class Pusher extends RatchetWsServer
{
    public function onEntry($entry)
    {
        $data = json_decode($entry, true);

        $userId  = $data['user_id'] ?? 0;
        $chatId  = $data['chat_id'] ?? 0;
        $message = $data['message'] ?? "";

        $user = User::whereId($userId)->first();

        $newEntry = [
            'user_id' => $userId,
            'chat_id' => $chatId,
            'message' => $message,
            'chat'    => Chat::whereId($chatId)->first(),
        ];

        $newEntry = json_encode($newEntry);

        if ($user) {
            $socketIds = $this->getSocketIdByUser($chatId, $user);

            foreach ($this->clients as $client) {
                if (in_array($client->resourceId, $socketIds)) {
                    $client->send($newEntry);
                }
            }
        }
    }

    public function getSocketIdByUser($chatId, User $fromUser)
    {
        /** @var Collection $userIds */
        $userIds =
            Message::select(['user_id'])->where('chat_id', $chatId)->where('user_id', '!=', $fromUser->id)->groupBy(
                'user_id'
            )->get();

        $userIds = $userIds->pluck('user_id')->toArray();

        logger('usersIds', $userIds);

        $guestUsersIds = [];

        if ($fromUser->roles === UserRolesEnum::ADMIN->value) {
            $guestUsers    = User::where('roles', UserRolesEnum::GUEST->value)->whereNotIn('id', $userIds)->get();
            $guestUsersIds = $guestUsers->pluck('id')->toArray();
        }

        logger('$guestUsersIds', $guestUsersIds);

        $useAllIds = array_merge($userIds, $guestUsersIds);

        logger('$useAllIds', $useAllIds);

        $users = User::whereIn('id', $useAllIds)->get();

        $tokens = [];

        /** @var User $user */
        foreach ($users as $user) {
            $userToken = $user->tokens()->first();
            if ($userToken) {
                $tokens[] = $userToken->token;
            }
        }

        $sockets = UserSocket::whereIn('token', $tokens)->get();

        if ($sockets->count()) {
            return $sockets->pluck('socket_id')->toArray();
        }

        return [];
    }

}
