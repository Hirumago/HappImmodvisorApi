<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;

class AddParticipantsToEvent
{
    public function __invoke(Event $data, User $user): Event
    {
        $data->addParticipant($user);

        return $data;
    }
}