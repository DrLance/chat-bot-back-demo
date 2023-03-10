<?php

declare(strict_types=1);


namespace App\Services\RatchetServer;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

abstract class RatchetWampServer implements WampServerInterface
{
    public $subscribedTopics = [];

    protected $console = false;

    public function __construct($console)
    {
        $this->console = $console;
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onEntry($entry)
    {
        $entryData = json_decode($entry, true);

        if (!array_key_exists($entryData['category'], $this->subscribedTopics)) {
            return;
        }

        $topic = $this->subscribedTopics[$entryData['category']];

        $topic->broadcast($entryData);
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->console->info("onSubscribe: {$conn->WAMP->sessionId} topic: $topic {$topic->count()}");

        if (!array_key_exists($topic->getId(), $this->subscribedTopics)) {
            $this->subscribedTopics[$topic->getId()] = $topic;
            $this->console->info("subscribed to topic $topic");
        }
    }


    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->console->info("onUnSubscribe: topic: $topic {$topic->count()}");
    }

    /**
     * When a new connection is opened it will be passed to this method.
     *
     * @param ConnectionInterface $conn The socket/connection that just connected to your application
     *
     * @throws \Exception
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->console->info("onOpen ({$conn->WAMP->sessionId})");
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     *
     * @param ConnectionInterface $conn The socket/connection that is closing/closed
     *
     * @throws \Exception
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->console->info("onClose ({$conn->WAMP->sessionId})");
    }


    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        $this->console->info('onCall');
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    /**
     * A client is attempting to publish content to a subscribed connections on a URI.
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param string|Topic                 $topic    The topic the user has attempted to publish to
     * @param string                       $event    Payload of the publish
     * @param array                        $exclude  A list of session IDs the message should be excluded from (blacklist)
     * @param array                        $eligible A list of session Ids the message should be send to (whitelist)
     */
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        $this->console->info('onPublish');
    }


    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->console->info('onError'.$e->getMessage());
    }
}
