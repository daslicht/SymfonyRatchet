<?php

namespace Acme\DemoBundle\WebSocket;

use P2\Bundle\RatchetBundle\WebSocket\ConnectionEvent;
use P2\Bundle\RatchetBundle\WebSocket\Payload;
use P2\Bundle\RatchetBundle\WebSocket\Server\ApplicationInterface;

class Application implements ApplicationInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'chat.send' => 'onSendMessage'
        );
    }

    public function onSendMessage(MessageEvent $event)
    {
        $client = $event->getConnection()->getClient()->jsonSerialize();
        $message = $event->getPayload()->getData();

        $event->getConnection()->broadcast(
            new EventPayload(
                'chat.message',
                array(
                    'client' => $client,
                    'message' => $message
                )
            )
        );

        $event->getConnection()->emit(
            new EventPayload(
                'chat.message.sent',
                array(
                    'client' => $client,
                    'message' => $message
                )
            )
        );
    }
}