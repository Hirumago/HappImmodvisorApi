<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class RegisterController extends AbstractController
{
    public function __invoke(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();

        $email = $request->get('email');
        $user->setEmail($email);

        $plaintextPassword = $request->get('password');
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        $nickname = $request->get('nickname');
        $user->setNickname($nickname);

        $file = $request->get('file');
        if (!empty($file)) {
            $uploadedFile = json_decode($file);

            if ($uploadedFile->base64) {
                $file = $uploadedFile->base64;

                $extension = $uploadedFile->extension;

                $fileToUpload = uniqid('av-', true) . '.' . $extension;

                $folder =  __DIR__ . '/../../' . $_ENV['IMAGES_FOLDER'];

                if (!file_exists($folder) || !is_dir($folder) || !is_writable($folder)) {
                    throw new \RuntimeException('Impossible de sauvegarder l\'image.');
                }

                $fileUploaded = file_put_contents($folder . $fileToUpload, base64_decode($file));

                if (!$fileUploaded) {
                    throw new \RuntimeException('Image non sauvegardée.');
                }

                $user->setAvatar($fileToUpload);
            }
        }

        $user->setRoles([
           'ROLE_USER'
        ]);
        $user->setCreatedAt(new \DateTimeImmutable());

        return $user;
    }
}