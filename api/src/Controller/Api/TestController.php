<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Messenger\Test\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/test/send-messages", name="test.send.messages", methods={"GET"})
     */
    public function sendMessages(): Response
    {
        for ($i = 1; $i <= 200; $i++) {
            $hashKey = '1hash';
            if ($i >= 30 && $i < 70) {
                $hashKey = '5hash';
            } elseif ($i >= 70 && $i < 120) {
                $hashKey = '3hash';
            } elseif ($i >= 120 && $i < 160) {
                $hashKey = '4hash';
            } elseif ($i >= 160 && $i < 175) {
                $hashKey = '2hash';
            } elseif ($i >= 175) {
                $hashKey = '6hash';
            }

            $this->messageBus->dispatch(new Message('MsgName' . $i), [
                new AmqpStamp($hashKey, AMQP_NOPARAM, []),
            ]);
        }

        return $this->json([
                               'name' => 'JSON API',
                           ]);
    }
}