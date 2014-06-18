<?php

/**
 * Xerox
 *
 * 
 * Class CreateIssue
 * This is the action class will be loaded when a action is called from 
 * the command line
 * 
 */

namespace Xerox\Action;

class CreateIssue extends \Xerox\Action {

    /**
     * @var Issue Title
     */
    private $issue_title;
    
    /**
     * @var Issue Description
     */
    private $issue_description;
    
    /**
     * @var Repository URL
     */
    private $repository;
    
    /**
     * @var Repository Username
     */
    private $username;
    
    /**
     * @var Repository Password
     */
    private $password;

    /**
     * CreateIssue Constructor
     *
     * @return Void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * CreateIssue execute
     * This is the actual function which call the actual component and create 
     * an issue in the repository
     *
     */
    public function execute($params) {
        $this->parseArguments($params);
        $repo = parse_url($this->repository);

        switch ($repo['host']) {
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

    /**
     * CreateIssue execute
     * This function parse the arguments supplied from the command line
     *
     */
    private function parseArguments($params) {
	
		if (isset($params[1]) && isset($params[2])) {
			if( $params[1] == '-u' ) {
				$this->username = $params[2];
			} else if( $params[1] == '-p' ) {
				$this->password = $params[2];
			} else {
				throw new \Exception('Unknown option!');
			}
		}

		if (isset($params[3]) && isset($params[4])) {
			if( $params[3] == '-u' ) {
				$this->username = $params[4];
			} else if( $params[3] == '-p' ) {
				$this->password = $params[4];
			} else {
				throw new \Exception('Unknown option!');
			}
		}
		
		if( empty($this->username) ) {
			throw new \Exception('Username is undefined!');
		}
		
		if( empty($this->password) ) {
			throw new \Exception('Password is undefined!');
		}

		if (isset($params[5])) {
			$this->repository = $params[5];
		} else {
			throw new \Exception('Repository URL is undefined!');
		}

		if (isset($params[6])) {
			$this->issue_title = $params[6];
		} else {
			throw new \Exception('Issue Title is undefined!');
		}

		if (isset($params[7])) {
			$this->issue_description = $params[7];
		} else {
			$this->issue_description = '';
		}
		
    }

}