<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class EventAddParticipant extends AbstractController
{
    public function __invoke(int $eventId, int $userId): Event
    {
        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->find($eventId);

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);

        $event->addParticipant($user);

        return $event;
    }
}