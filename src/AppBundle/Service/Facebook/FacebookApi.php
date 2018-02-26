<?

namespace AppBundle\Service\Facebook;


use Facebook\Facebook;

/**
 * Class FacebookApi
 * @package AppBundle\Service
 */
class FacebookApi
{

    /**
     * @var Facebook
     */
    private $fb;

    /**
     * @var string
     */
    private $access_token;

    /**
     * FacebookApi constructor.
     * @param $facebook_client_id
     * @param $facebook_client_secret
     */
    function __construct($facebook_client_id, $facebook_client_secret)
    {

        $this->fb = new Facebook([
            'app_id' => $facebook_client_id,
            'app_secret' => $facebook_client_secret,
            'default_graph_version' => 'v2.12',
        ]);
    }

    /**
     * @param string $access_token
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * @param $endpoint
     * @param null $accessToken
     * @param null $eTag
     * @param null $graphVersion
     * @return \Facebook\FacebookResponse
     */
    private function get($endpoint, $accessToken = null, $eTag = null, $graphVersion = null){
        if(!$accessToken){
            $accessToken = $this->access_token;
        }
        return $this->fb->get($endpoint, $accessToken, $eTag, $graphVersion);
    }

    /**
     * @return array
     */
    public function getFriends(){
        $response = $this->get("/me/taggable_friends");
        $body = $response->getDecodedBody();
        return $body['data'];
    }
}