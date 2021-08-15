<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class EventRemoveParticipant extends AbstractController
{
    public function __invoke(Request $request)
    {
        $event = $request->attributes->get('data');
        if (!($event instanceof Event)) {
            throw new \RuntimeException('Invalid Event');
        }

        $userId = $request->attributes->get('userId');

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);

        if (!($user instanceof User)) {
            throw new \RuntimeException('Invalid User');
        }

        $event->removeParticipant($user);

        return $event;
    }
}