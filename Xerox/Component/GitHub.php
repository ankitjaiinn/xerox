<?php

/**
 * Xerox
 *
 * 
 * Class GitHub
 * This class is the actual class which contains the logic to create the issue
 * in GitHub
 * 
 */

namespace Xerox\Component;

class GitHub extends \Xerox\Component {

    /**
     * @var Repository Username
     */
    private $username;
    
    /**
     * @var Repository Password
     */
    private $password;
    
    /**
     * @var Repository Owner
     */
    private $owner;
    
    /**
     * @var Repository Name
     */
    private $repo;
    
    /**
     * @var API URL
     */
    private $api_url = 'https://api.github.com';

    /**
     * GitHub Constructor
     *
     * @return Void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * GitHub setRepository
     *
     * @return Self Instance
     */
    public function setRepository($repository) {
        $url_components = parse_url($repository);
        $path = explode('/', $url_components['path']);
        $this->owner = $path[1];
        $this->repo = $path[2];
        return $this;
    }

    /**
     * GitHub setUsername
     *
     * @return Self Instance
     */
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * GitHub setPassword
     *
     * @return Self Instance
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * GitHub createIssue
     *
     * @return CURL response from the API
     */
    public function createIssue($title, $description = null) {
        $data = array();
        $data['title'] = $title;
        if (!is_null($description))
            $data['body'] = $description;

        $data = json_encode($data);
        $url = sprintf('%s/repos/%s/%s/issues', $this->api_url, $this->owner, $this->repo);

        $c = curl_init();

        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($c, CURLOPT_USERAGENT, "xerox.github.api");
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);

        $response = array(
            'responce' => curl_exec($c),
            'error' => curl_error($c)
        );
        curl_close($c);

        return $response;
    }

}