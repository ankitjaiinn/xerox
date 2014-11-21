<?php

/**
 * Xerox
 *
 * 
 * Abstract Class Component
 * Set the member variables and defined some abstract method that needs to be 
 * defined by the child class
 */
abstract class Component {

    /**
     * @var Username String
     */
    protected $username;

    /**
     * @var Password String
     */
    protected $password;

    /**
     * @var Repository Owner String
     */
    protected $owner;

    /**
     * @var Repository Name String
     */
    protected $repo;

    /**
     * @var IssuesClient Object
     */
    protected $client;

    /**
     * Component Constructor
     *
     * @return Void
     */
    public function __construct(Issue $client) {
        $this->client = $client;
    }

    /**
     * setRepository
     *
     * @return Self Instance
     */
    public function setRepository($repository) {
        $url_components = parse_url($repository);

        if (!empty($url_components['path']) && $url_components['path'] !== '/') {
            $path = explode('/', $url_components['path']);
            $this->owner = $path[1];

            if (isset($path[2])) {
                $this->repo = $path[2];
            } else {
                throw new Exception("Repository name not found");
            }
        } else {
            throw new Exception("Owner and Repository name not found");
        }

        return $this;
    }

    /**
     * setUsername
     *
     * @return Self Instance
     */
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * setPassword
     *
     * @return Self Instance
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * setAuth
     *
     * @param Curl Handler Object
     */
    abstract public function setAuth($curl_handler);

    /**
     * createIssue
     *
     * @param Issue Title and Description String
     */
    abstract public function createIssue($title, $description);

    /**
     * parseResponse
     *
     * @param Response from the API
     */
    abstract public function parseResponse($response);
}