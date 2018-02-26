<?php

namespace AppBundle\Security\Core;


use AppBundle\Service\Facebook\FacebookApiTrait;
use AppBundle\Service\FacebookApi;
use FOS\UserBundle\Model\User;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class FOSConnectUserProvider
 * @package AppBundle\Security\Core
 */
class FOSConnectUserProvider extends FOSUBUserProvider implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use FacebookApiTrait;

    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Expected an instance of FOS\UserBundle\Model\User, but got "%s".', get_class($user)));
        }

        $property = $this->getProperty($response);

        // Symfony <2.5 BC
        if (method_exists($this->accessor, 'isWritable') && !$this->accessor->isWritable($user, $property)
            || !method_exists($this->accessor, 'isWritable') && !method_exists($user, 'set'.ucfirst($property))) {
            throw new \RuntimeException(sprintf("Class '%s' must have defined setter method for property: '%s'.", get_class($user), $property));
        }

        $username = $response->getUsername();

        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $this->disconnect($previousUser, $response);
        }

        if ($user->getFacebookID()) {
            $this->facebookApi->setAccessToken($response->getAccessToken());
            $user->setFriends($this->getFacebookFriends());
        }

        $this->userManager->updateUser($user);
    }


    /**
     * @param UserResponseInterface $response
     * @return \FOS\UserBundle\Model\UserInterface|null|UserInterface
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        /**
         * @var \AppBundle\Entity\User $user
         */

        $user = parent::loadUserByOAuthUserResponse($response);


        if ($user->getFacebookID()) {
            $this->facebookApi->setAccessToken($response->getAccessToken());
            $user->setFriends($this->getFacebookFriends());
        }

        return $user;
    }
}