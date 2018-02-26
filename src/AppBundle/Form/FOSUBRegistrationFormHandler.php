<?php

namespace AppBundle\Form;

use AppBundle\Service\Facebook\FacebookApiTrait;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\FacebookResourceOwner;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Form\FOSUBRegistrationFormHandler as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBRegistrationFormHandler extends BaseClass
{
    use FacebookApiTrait;

    protected function setUserInformation(UserInterface $user, UserResponseInterface $userInformation)
    {

        $user = parent::setUserInformation($user, $userInformation); // TODO: Change the autogenerated stub
        if($userInformation->getResourceOwner() instanceof FacebookResourceOwner){
            $this->facebookApi->setAccessToken($userInformation->getAccessToken());
            $user->setFriends($this->getFacebookFriends());
        }

        return $user;
    }
}