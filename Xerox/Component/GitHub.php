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
     * @param Issue Class Object
     * @return Void
     */
    public function __construct(Issue $client) {
        parent::__construct($client);
    }

    /**
     * GitHub setAuth
     *
     * @param Curl Handler Object
     * @return Void
     */
    public function setAuth($ch) {
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
    }

    /**
     * GitHub createIssue
     *
     * @param Issue Title and Description String
     * @return CURL response from the API
     */
    public function createIssue($title, $description = null) {

        $data = array();
        $data['title'] = $title;
        if (!is_null($description))
            $data['body'] = $description;

        $data = json_encode($data);
        $url = sprintf('%s/repos/%s/%s/issues', $this->api_url, $this->owner, $this->repo);
        return $this->client->request($url, 'POST', $data);
    }

    /**
     * GitHub parseResponse
     *
     * @param CURL response from the API
     * @return API parsed response String
     */
    public function parseResponse($response) {
        ob_start();
        echo "==========================================================================\n";
        if (isset($response['response']->message)) {
            echo 'API Message: ' . $response['response']->message . "\n";
        } else if (isset($response['response']->id)) {
            echo "ID: \t\t\t" . $response['response']->id . "\n";
            echo "Issue URL: \t\t" . $response['response']->url . "\n";
            echo "Issue Title: \t\t" . $response['response']->title . "\n";
            echo "Issue Created at: \t" . $response['response']->created_at . "\n";
        } else {
            throw new Exception("No response from API. Entered data may be incorrect");
        }
        echo "==========================================================================\n";
        $data = ob_get_contents();
        ob_get_clean();
        return $data;
    }

}