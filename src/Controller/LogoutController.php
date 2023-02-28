<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[Route('/api', name: 'api_')]
class LogoutController
{
    private $jwtManager;
    private $eventDispatcher;

    public function __construct(JWTTokenManagerInterface $jwtManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->jwtManager = $jwtManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/logout", name="logout", methods={"POST"})
     */
    public function logout(Request $request): Response
    {
        $token = str_replace('Bearer ', '', $request->headers->get('Authorization'));

        if ($token) {
            $event = new JWTDecodedEvent(['token' => $token]);
            $this->eventDispatcher->dispatch($event, Events::JWT_DECODED);
        }

        return new Response('Logged out successfully');
    }
}
