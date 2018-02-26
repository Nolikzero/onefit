<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Service\Facebook\FacebookApi;
use AppBundle\Service\Facebook\FacebookApiTrait;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FriendsController extends Controller
{

    /**
     * @Route("/friends", name="friends")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $friends = $user->getFriends();

        // replace this example code with whatever you need
        return $this->render('friends/index.html.twig', [
            'friends' => $friends
        ]);
    }
}
