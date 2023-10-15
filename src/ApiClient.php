<?php
namespace spinshield\spinclient;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Exception;

/**
 * Class ApiClient
 * @package spinshield\spinclient
 */
class ApiClient
{
    /**
     * @var string
     */
    protected $api_login;
    
    /**
     * @var string
     */
    protected $api_password;
    
    /**
     * @var string
     */
    protected $endpoint;
    
    /**
     * ApiClient constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct($config)
    {
        if (!array_key_exists('endpoint', $config)) {
            throw new Exception("You must specify endpoint for API");
        }
        if (!array_key_exists('api_login', $config)) {
            throw new Exception("You must specify api_login");
        }
        if (!array_key_exists('api_password', $config)) {
            throw new Exception("You must specify api_password");
        }
        $this->api_login = $config['api_login'];
        $this->api_password = $config['api_password'];
        $this->endpoint = $config['endpoint'];
    }
    
    /**
     * Retrieves the game list.
     *
     * @param string $currency
     * @param int $list_type
     * @return mixed
     */
    public function getGameList(
        string $currency = "USD",
        int $list_type = 1
    ) {
        $response = $this->sendRequest('post', 'getGameList', [
            'show_additional' => true,
            'show_systems' => 0,
            'list_type' => $list_type,
            'currency' => strtoupper($currency),
        ]);
        return $response->getBody();
    }
    
    /**
     * Creates a player.
     *
     * @param string $username
     * @param string $userpassword
     * @param string $usernickname
     * @param string $currency
     * @return mixed
     */
    public function createPlayer(
        string $username,
        string $userpassword,
        string $usernickname,
        string $currency
    ) {
        $response = $this->sendRequest('post', 'createPlayer', [
            'user_username' => $username,
            'user_password' => $userpassword,
            'user_nickname' => $usernickname,
            'currency' => strtoupper($currency),
        ]);
        return $response->getBody();
    }
    
    /**
     * Retrieves free rounds.
     *
     * @param $username
     * @param $password
     * @param $currency
     * @return mixed
     */
    public function getFreeRounds($username, $password, $currency)
    {
        $response = $this->sendRequest('post', 'getFreeRounds', [
            'user_username' => $username,
            'user_password' => $password,
            'currency' => strtoupper($currency),
        ]);
        return $response->getBody();
    }
    
    /**
     * Adds free rounds.
     *
     * @param $username
     * @param $userpassword
     * @param $game_id
     * @param $currency
     * @param $freespins
     * @param $betlevel
     * @return mixed
     */
    public function addFreeRounds($username, $userpassword, $game_id, $currency, $freespins, $betlevel)
    {
        $response = $this->sendRequest('post', 'addFreeRounds', [
            'lang' => 'en',
            'user_username' => $username,
            'user_password' => $userpassword,
            'gameid' => $game_id,
            'freespins' => $freespins,
            'bet_level' => $betlevel,
            'currency' => strtoupper($currency),
        ]);
        return $response->getBody();
    }
    
    /**
     * Retrieves game information.
     *
     * @param $username
     * @param $userpassword
     * @param $game_id
     * @param $currency
     * @param $homeurl
     * @param $cashierurl
     * @param int $play_for_fun
     * @param $lang
     * @return mixed
     * @throws Exception
     */
    public function getGame(
        $username,
        $userpassword,
        $game_id,
        $currency,
        $homeurl,
        $cashierurl,
        int $play_for_fun,
        $lang
    ) {
        if ($play_for_fun > 1) {
            throw new Exception("play_for_fun should be 0 or 1 integer");
        }
        $response = $this->sendRequest('post', 'getGame', [
            'user_username' => $username,
            'user_password' => $userpassword,
            'gameid' => $game_id,
            'homeurl' => $homeurl,
            'cashierurl' => $cashierurl,
            'play_for_fun' => $play_for_fun,
            'lang' => $lang,
            'currency' => strtoupper($currency),
        ]);
        return $response->getBody();
    }
    
    /**
     * Sends a request to the API.
     *
     * @param $method
     * @param $api_method
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    protected function sendRequest($method, $api_method, $data)
    {
        $client = new \GuzzleHttp\Client();
        $options = [
            'form_params' => array_merge([
                'api_login' => $this->api_login,
                'api_password' => $this->api_password,
                'method' => $api_method,
            ], $data),
        ];
        return $client->request($method, $this->endpoint, $options);
    }
}