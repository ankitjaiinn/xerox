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
     * @param Issue Class Object
     * @return Void
     */
    public function __construct(Issue $client) {
        parent::__construct($client);
    }

    /**
     * Bitbucket setAuth
     *
     * @param Curl Handler Object
     * @return Void
     */
    public function setAuth($ch) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode("{$this->username}:{$this->password}")));
    }

    /**
     * Bitbucket createIssue
     *
     * @param Issue Title and Description String
     * @return CURL response from the API
     */
    public function createIssue($title, $description = null) {

        $data = array();
        $data['title'] = $title;
        if (!is_null($description))
            $data['content'] = $description;

        $data = http_build_query($data);
        $url = sprintf('%s/%s/%s/issues', $this->api_url, $this->owner, $this->repo);
        return $this->client->request($url, 'POST', $data);
    }

    /**
     * Bitbucket parseResponse
     *
     * @param CURL response from the API
     * @return API parsed response String
     */
    public function parseResponse($response) {
        ob_start();
        echo "==========================================================================\n";
        if (isset($response['response']->resource_uri)) {
            echo "Issue URL: \t\t" . $response['response']->resource_uri . "\n";
            echo "Issue Title: \t\t" . $response['response']->title . "\n";
            echo "Issue Created at: \t" . $response['response']->utc_created_on . "\n";
        } else {
            throw new Exception("No response from API. Entered data may be incorrect");
        }
        echo "==========================================================================\n";
        $data = ob_get_contents();
        ob_get_clean();
        return $data;
    }

}