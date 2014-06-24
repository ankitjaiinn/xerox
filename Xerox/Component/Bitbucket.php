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

require_once APPLICATION_PATH . DS . 'Xerox\Component.php';

class Bitbucket extends Component {

	public $api_url = 'https://bitbucket.org/api/1.0/repositories';
	
    /**
     * Bitbucket Constructor
     *
     * @return Void
     */
    public function __construct(Issue $client) {
        parent::__construct($client);
    }
	
	public function setAuth($ch) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode("{$this->username}:{$this->password}")));
	}

    /**
     * Bitbucket createIssue
     *
     * @return CURL response from the API
     */
    public function createIssue($title, $description = null) {
        
		$data = array();
        $data['title'] = $title;
        if(!is_null($description))
            $data['content'] = $description;
			
		$data = http_build_query($data);
        $url = sprintf('%s/%s/%s/issues', $this->api_url, $this->owner, $this->repo);
        return $this->client->request($url, 'POST', $data);
		
    }
}