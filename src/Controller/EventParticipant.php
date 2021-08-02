<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventParticipant extends AbstractController
{
    public function __invoke(Event $data): Event
    {
//        dd($data->getParticipants());
//        $data->addParticipant()
//        dd($request);
        dd($data);
        return $data;
    }
}