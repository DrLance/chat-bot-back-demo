<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ChatUser
 *
 * @property int $id
 * @property int $user_id
 * @property int $chat_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ChatUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatUser whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatUser whereUserId($value)
 * @mixin \Eloquent
 */
class ChatUser extends Model
{
    use HasFactory;
}
