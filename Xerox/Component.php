<?php

/**
 * Xerox
 *
 * 
 * Class Component
 * 
 */



abstract class Component {

	/**
     * @var Repository Username
     */
    protected $username;
    
    /**
     * @var Repository Password
     */
    protected $password;
    
    /**
     * @var Repository Owner
     */
    protected $owner;
    
    /**
     * @var Repository Name
     */
    protected $repo;
	
	/**
     * Component Constructor
     *
     * @return Void
     */
	 
	/**
	 * @var IssuesClient
	 */
	protected $client;

	
    public function __construct(Issue $client) {
        $this->client = $client;
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
	
	abstract public function setAuth($curl_handler);
	
	abstract public function createIssue($title, $description);

}