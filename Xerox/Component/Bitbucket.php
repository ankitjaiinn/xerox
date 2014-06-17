<?php

/**
 * Xerox
 *
 * 
 * Class Bitbucket
 * This class is the actual class which contains the logic to create the issue
 * in Bitbucket
 * 
 */

namespace Xerox\Component;

class Bitbucket extends \Xerox\Component {

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
    private $api_url = 'https://bitbucket.org/api/1.0/repositories';

    /**
     * Bitbucket Constructor
     *
     * @return Void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Bitbucket setRepository
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
     * Bitbucket setUsername
     *
     * @return Self Instance
     */
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * Bitbucket setPassword
     *
     * @return Self Instance
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * Bitbucket createIssue
     *
     * @return CURL response from the API
     */
    public function createIssue($title, $description = null) {
        
        $data = array();
        $data[] = 'title=' . $title;
        $data[] = 'content=' . $description;
        $postfields = implode('&', $data);

        $url = sprintf('%s/%s/%s/issues', $this->api_url, $this->owner, $this->repo);

        $c = curl_init();
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode("{$this->username}:{$this->password}")));
        curl_setopt($c, CURLOPT_USERAGENT, "xerox.bitbucket.api");
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);

        $response = array(
            'response' => curl_exec($c),
            'error' => curl_error($c)
        );
        curl_close($c);

        return $response;
    }

}