<?php

declare(strict_types=1);


namespace App\Services\RatchetServer;

class PusherWamp extends RatchetWampServer
{
    public function onEntry($entry)
    {
        logger()->warning('SADSas', [$entry]);
    }
}
