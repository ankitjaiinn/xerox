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
 
require_once APPLICATION_PATH . DS . 'Xerox\Component.php';

class Github extends Component {

	
	public $api_url = 'https://api.github.com';
	
    /**
     * GitHub Constructor
     *
     * @return Void
     */
    public function __construct(Issue $client) {
        parent::__construct($client);
    }
	
	public function setAuth($ch) {
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
	}

    /**
     * GitHub createIssue
     *
     * @return CURL response from the API
     */
    public function createIssue($title, $description = null) {
        
		$data = array();
        $data['title'] = $title;
        if(!is_null($description))
            $data['body'] = $description;

        $data = json_encode($data);
        $url = sprintf('%s/repos/%s/%s/issues', $this->api_url, $this->owner, $this->repo);
        return $this->client->request($url, 'POST', $data);
		
    }

}