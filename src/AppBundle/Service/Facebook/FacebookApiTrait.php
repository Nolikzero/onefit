<?
namespace AppBundle\Service\Facebook;


use AppBundle\Service\Facebook\FacebookApi;

trait FacebookApiTrait
{
    /**
     * @var FacebookApi
     */
    private $facebookApi;

    /**
     * @param FacebookApi $facebookApi
     */
    public function setFacebookApi(FacebookApi $facebookApi)
    {
        $this->facebookApi = $facebookApi;
    }

    /**
     * @return array
     */
    public function getFacebookFriends()
    {
        return $this->facebookApi->getFriends();
    }
}