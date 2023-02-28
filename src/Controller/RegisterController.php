<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

#[Route('/api', name: 'api_')]
class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register', methods:'post')]
    public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $entityManager = $doctrine->getManager();

        $requestData = json_decode($request->getContent());
        $user = new User();

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $requestData->password
        );

        $user->setEmail($requestData->email);
        $user->setPassword($hashedPassword);
        $user->setUsername($requestData->email);
        
        $entityManager->persist($user);
        $entityManager->flush();
  
        return $this->json(['message' => 'Registered Successfully']);
    }
}
