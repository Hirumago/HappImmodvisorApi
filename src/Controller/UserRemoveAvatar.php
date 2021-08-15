<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Request;

#[AsController]
class UserRemoveAvatar extends AbstractController
{
    public function __invoke(Request $request)
    {
        $user = $request->attributes->get('data');
        if (!($user instanceof User)) {
            throw new \RuntimeException('Invalid User');
        }

        $user->setAvatar(null);
        $user->setUpdatedAt(new \DateTimeImmutable());

        return $user;
    }
}