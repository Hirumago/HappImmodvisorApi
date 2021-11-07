<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Config\Security\PasswordHasherConfig;

#[AsController]
class LoginController extends AbstractController
{
    public function __invoke(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $email = $request->get('email');

        $em = $this->getDoctrine()->getManager()->getRepository(User::class);
        $user = $em->findOneBy([
            'email' => $email
        ]);

        $plaintextPassword = $request->get('password');

        if (!$passwordHasher->isPasswordValid($user, $plaintextPassword)) {
            throw new \RuntimeException('Identifiants incorrects.');
        }

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));

        return $user;
    }
}
