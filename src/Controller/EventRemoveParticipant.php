<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class EventRemoveParticipant extends AbstractController
{
    public function __invoke(int $eventId, int $userId): Event
    {
        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->find($eventId);

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);

        $event->removeParticipant($user);

        return $event;
    }
}