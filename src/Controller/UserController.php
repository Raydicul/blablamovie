<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Vote;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UserController extends AbstractFOSRestController
{
    /**
     * Affiche un utilisateur ainsi que ses choix de film
     *
     * @Rest\Get("/user/{id}", name="user_show")
     * @Rest\View(StatusCode = 200)
     */
    public function showAction(User $user)
    {
        return $this->view($user, Response::HTTP_OK);
    }

    /**
     * Ajoute un utilisateur
     *
     * @Rest\Post("/user", name="user_create")
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function createAction(User $user, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            return $this->view($validationErrors, Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        return $this->view($user, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('user_show',
                ['id' => $user->getId(), UrlGeneratorInterface::ABSOLUTE_URL])
        ]);
    }

    /**
     * Liste les utilisateur aillant votés
     *
     * @Rest\Get("/users-with-vote", name="user_with_vote")
     * @Rest\View(StatusCode = 200)
     */
    public function userWithVoteAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->getUsersWithVote();

        return $this->view($users, Response::HTTP_OK);
    }
}
