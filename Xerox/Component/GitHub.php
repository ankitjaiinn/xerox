<?php

namespace Xerox\Component;

class GitHub extends \Xerox\Component
{
    private $username;
    private $password;
    private $owner;
    private $repo;
    private $repository;
    private $repository_parsed;
    private $api_url = 'https://api.github.com';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function setRepository($repository) {
        $this->repository = $repository;
        $this->repository_parsed = parse_url($this->repository);
        $path = explode('/', $this->repository_parsed['path']);
        $this->owner = $path[1];
        $this->repo = $path[2];
        return $this;
    }
    
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }
    
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }
    
    public function createIssue($title, $description = null) {
        $data = array();
        $data['title'] = $title;
        if(!is_null($description))
            $data['body'] = $description;

        $data = json_encode($data);
        $url = sprintf('%s/repos/%s/%s/issues', $this->api_url, $this->owner, $this->repo);
        
        $c = curl_init();
        
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($c, CURLOPT_USERAGENT, "xerox.api");
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