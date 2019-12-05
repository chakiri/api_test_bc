<?php

namespace App\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Swagger\Annotations as SWG;

/**
 * Advert ApiBundle controller
 */
class SecurityController extends AbstractController
{
    /**
     * Authentication JWT
     * @Route("/login", name="api_security_login", methods={"POST"})
     *
     * @SWG\Post(
     *     summary="Get token from authentication",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Bad credential",
     *     ),
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          format="application/json",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="username", type="string", example="email"),
     *              @SWG\Property(property="password", type="string", example="password")
     *          )
     *      )
     * )
     */
    public function login(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'username' => $user->getEmail(),
            'roles' => $user->getRoles()
        ]);
    }

}
