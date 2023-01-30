<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserSocket
 *
 * @property int $socket_id
 * @property string $token
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocket query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocket whereSocketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocket whereToken($value)
 * @mixin \Eloquent
 */
class UserSocket extends Model
{
    use HasFactory;

    public $timestamps = false;
}
