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

        $uploadedFile = json_decode($request->get('file'));

        if ($uploadedFile->uri) {
            $file = $uploadedFile->uri;
            $file = str_replace('data:image/jpeg;base64,', '', $file);
            $file = str_replace(' ', '+', $file);

            $type = $uploadedFile->type;

            $fileToUpload = uniqid('av-', true) . '.' . $type;

            $folder =  __DIR__ . '/../../' . $_ENV['IMAGES_FOLDER'];

            if (!file_exists($folder) || !is_dir($folder) || !is_writable($folder)) {
                throw new \RuntimeException('Impossible de sauvegarder l\'image.');
            }

            $fileUploaded = file_put_contents($folder . $fileToUpload, $file);

            if (!$fileUploaded) {
                throw new \RuntimeException('Image non sauvegardée.');
            }

            $user->setAvatar($fileToUpload);
        }

        $user->setCreatedAt(new \DateTimeImmutable());

        return $user;
    }
}