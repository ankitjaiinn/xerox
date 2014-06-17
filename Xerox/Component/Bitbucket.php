<?php

namespace Xerox\Component;

class Bitbucket extends \Xerox\Component
{
    private $username;
    private $password;
    private $owner;
    private $repo;
    private $repository;
    private $repository_parsed;
    private $api_url = 'https://bitbucket.org/api/1.0/repositories';
    
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
            $data['content'] = $description;

		$fieldsStr = '';
		foreach($data as $key=>$value) {
			$fieldsStr .= $key.'='.$value.'&';
		}
		rtrim($fieldsStr, '&');	
		
        
		$url = sprintf('%s/%s/%s/issues', $this->api_url, $this->owner, $this->repo);
        
        $c = curl_init();
        
        //curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($c, CURLOPT_USERPWD, base64_encode("{$this->username}:{$this->password}"));
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Authorization: Basic '.base64_encode("{$this->username}:{$this->password}"))); //YXZpbnVzOmJpdHBhc3MxOQ==
		curl_setopt($c, CURLOPT_USERAGENT, "xerox.api");
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $fieldsStr);
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