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
class LogoutController extends AbstractController
{
    public function __invoke(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);
        $this->get('request_stack')->getSession()->invalidate();

        return array('user'=> null);
    }
}
