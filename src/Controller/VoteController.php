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
use Symfony\Component\HttpKernel\Exception\HttpException;

class VoteController extends AbstractFOSRestController
{
    /**
     * Affiche un vote
     *
     * @Rest\Get("/vote/{id}", name="vote_show")
     * @Rest\View(StatusCode = 200)
     */
    public function showAction(Vote $vote)
    {
        return $this->view($vote, Response::HTTP_OK);
    }

    /**
     * Affiche le meilleur film
     *
     * @Rest\Get("/top-vote", name="vote_show_top")
     * @Rest\View(StatusCode = 200)
     * @Rest\QueryParam(
     *     name="startDate"
     * )
     * @Rest\QueryParam(
     *     name="endDate"
     * )
     */
    public function showTopAction($startDate = null, $endDate = null)
    {
        if ($startDate !== null && $endDate !== null && !empty($startDate) && !empty($endDate)) {
            $topVote = $this->getDoctrine()->getRepository(Vote::class)->getTopFromDateRange($startDate, $endDate);
        } else {
            $topVote = $this->getDoctrine()->getRepository(Vote::class)->getTop();
        }

        return $this->view($topVote, Response::HTTP_OK);
    }

    /**
     * Ajoute un vote à un utilisateur
     *
     * @Rest\Post("/vote/{id}", name="vote_add")
     * @Rest\RequestParam(
     *     name="imdbID",
     *     requirements="^tt*\d{7}",
     *     nullable=false,
     *     description="L'imdbID doit respecter le format : tt0105236"
     * )
     * @Rest\View(StatusCode = 201)
     */
    public function addVoteAction(User $user, $imdbID)
    {
        $userVotes = $this->getDoctrine()
            ->getRepository(Vote::class)
            ->findBy(['user' => $user]);

        if ($userVotes !== null && count($userVotes) >= 3) {
            throw new HttpException(403, "Vote impossible, l'utilisateur a déjà 3 votes.");
        }

        $vote = new Vote();
        $vote->setImdbID($imdbID);
        $vote->setUser($user);

        $em = $this->getDoctrine()->getManager();

        $em->persist($vote);
        $em->flush();

        return $this->view($vote, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('vote_show',
                ['id' => $vote->getId(), UrlGeneratorInterface::ABSOLUTE_URL])
        ]);
    }

    /**
     * Supprime un vote
     *
     * @Rest\Delete("/vote/{id}", name="vote_delete")
     * @Rest\View(StatusCode = 204)
     */
    public function deleteVoteAction(Vote $vote)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($vote);
        $em->flush();

        return $this->view([], Response::HTTP_NO_CONTENT);
    }
}
