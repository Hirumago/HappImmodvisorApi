<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Request;

#[AsController]
class UserAddAvatar extends AbstractController
{
    public function __invoke(Request $request)
    {
        $user = $request->attributes->get('data');
        if (!($user instanceof User)) {
            throw new \RuntimeException('Invalid User');
        }

        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new \RuntimeException('File is required');
        }

        $user->setFile($uploadedFile);
        $user->setUpdatedAt(new \DateTimeImmutable());

        return $user;
    }
}