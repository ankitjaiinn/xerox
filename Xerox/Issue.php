<?php

/**
 * Xerox
 *
 * 
 * Class Issue
 * This class will parse the arguments which are passed from the command line and 
 * load the desired component based on the name found in the repository URL
 * 
 */
class Issue {

    /**
     * @var method String
     */
    protected $method;

    /**
     * @var Username String
     */
    protected $username;

    /**
     * @var Password String
     */
    protected $password;

    /**
     * @var URL String
     */
    protected $repository;

    /**
     * @var Issue Title String
     */
    protected $issue_title;

    /**
     * @var Issue Description String
     */
    protected $issue_description;

    /**
     * @var component Object
     */
    protected $Component;

    /**
     * Issue Constructor
     *
     * @return Void
     */
    public function __construct($params) {
        $this->parseArguments($params);
    }

    /**
     * Issue loadComponent
     *
     * @param String $name Load the component class
     * @return Void
     */
    protected function loadComponent($name) {

        $filename = APPLICATION_PATH . DS . 'Xerox\Component\\' . $name . '.php';
        if (file_exists($filename)) {
            require_once $filename;
        } else {
            throw new Exception('Unsupported Repository!');
        }

        $this->Component = new $name($this);
    }

    /**
     * Issue parseArguments
     * This function parse the arguments supplied from the command line
     *
     * @param Array $params 
     * @return Void
     */
    protected function parseArguments($params) {

        if (isset($params[0])) {
            $this->method = $params[0];
        } else {
            throw new Exception('Method is undefined!');
        }

        if (isset($params[1]) && isset($params[2])) {
            if ($params[1] == '-u') {
                $this->username = $params[2];
            } else if ($params[1] == '-p') {
                $this->password = $params[2];
            } else {
                throw new Exception('Unknown option!');
            }
        }

        if (isset($params[3]) && isset($params[4])) {
            if ($params[3] == '-u') {
                $this->username = $params[4];
            } else if ($params[3] == '-p') {
                $this->password = $params[4];
            } else {
                throw new Exception('Unknown option!');
            }
        }

        if (empty($this->username)) {
            throw new Exception('Username is undefined!');
        }

        if (empty($this->password)) {
            throw new Exception('Password is undefined!');
        }

        if (isset($params[5])) {
            $this->repository = $params[5];
        } else {
            throw new Exception('Repository URL is undefined!');
        }

        if (isset($params[6])) {
            $this->issue_title = $params[6];
        } else {
            throw new Exception('Issue Title is undefined!');
        }

        if (isset($params[7])) {
            $this->issue_description = $params[7];
        } else {
            $this->issue_description = '';
        }
    }

    /**
     * Action execute
     *
     * @return response string 
     */
    public function execute() {

        $repo = parse_url($this->repository);

        $url_components = parse_url($this->repository);

        $host = explode('.', $url_components['host']);
        $class = ucwords(strtolower($host[count($host) - 2]));

        $this->loadComponent($class);

        $this->Component->setRepository($this->repository)
                ->setUsername($this->username)
                ->setPassword($this->password);

        $data = null;

        switch ($this->method) {
            case 'create-issue':
                $data = $this->Component->createIssue($this->issue_title, $this->issue_description);
                break;

            default:
                throw new Exception('Method does not exist\'s');
        }

        return $data;
    }

    /**
     * Issue request
     * do a request and return String
     *
     * @param string $url
     * @param string $method GET POST 
     * @param array $data
     * @return response String
     */
    public function request($url, $method, $data) {

        $ch = curl_init();

        $this->Component->setAuth($ch);

        curl_setopt($ch, CURLOPT_USERAGENT, "xerox.issue.api");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        switch ($method) {
            case 'GET':
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                if (count($data))
                    $url .= '?' . http_build_query($data);
                break;

            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if (count($data))
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = array(
            'response' => json_decode(curl_exec($ch)),
            'error' => curl_error($ch)
        );
        curl_close($ch);

        return $this->Component->parseResponse($response);
    }

}