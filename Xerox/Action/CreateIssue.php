<?php

namespace Xerox\Action;

class CreateIssue extends \Xerox\Action
{
    private $issue_title;
    private $issue_description;
    private $repository;
    private $username;
    private $password;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function execute($params) {
        $this->parseArguments($params);
        $repo = parse_url($this->repository);
        
        switch($repo['host']) {
            case 'github.com':
                $this->loadComponent('GitHub');
                $this->Component->GitHub->setRepository($this->repository)
                                        ->setUsername($this->username)
                                        ->setPassword($this->password);
                $data = $this->Component->GitHub->createIssue($this->issue_title, $this->issue_description);
                print_r($data);
                break;
            case 'bitbucket.org':
				$this->loadComponent('Bitbucket');
                $this->Component->Bitbucket->setRepository($this->repository)
                                        ->setUsername($this->username)
                                        ->setPassword($this->password);
                $data = $this->Component->Bitbucket->createIssue($this->issue_title, $this->issue_description);
                print_r($data);
                break;
            default:
                throw new \Exception('Unsupported Repository');
        }
    }
    
    private function parseArguments($params) {
        $this->issue_description = array_pop($params);
        $this->issue_title = array_pop($params);
        $this->repository = array_pop($params);
        $options = array(
            'username' => '-u',
            'password' => '-p'
        );
        foreach( $params as $index => $param ) {
            foreach( $options as $key => $option ) {
                if( $param === $option ) {
                    $this->{$key} = $params[$index + 1];
                }
            }
        }
    }
}