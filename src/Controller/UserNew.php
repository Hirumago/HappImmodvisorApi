<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Request;

#[AsController]
class UserNew extends AbstractController
{
    public function __invoke(Request $request)
    {
        $user = new User();

        $nickname = $request->get('nickname');
        $user->setNickname($nickname);

        $uploadedFile = $request->files->get('file');
        if ($uploadedFile) {
            $user->setFile($uploadedFile);
        }

        $user->setFile($uploadedFile);
        $user->setCreatedAt(new \DateTimeImmutable());

        return $user;
    }
}