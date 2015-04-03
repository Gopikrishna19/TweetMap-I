<?php
    // Get all the required PHP Twitter Streaming API
    require_once "OauthPhirehose.php";

    // Get the database confiuration
    require_once "../ajax/common.php";
    
    const table_name = "tweets";
    
    set_time_limit(0);
    
    /**
    * Create a consumer extending the API 
    *
    **/
    class Consumer extends OauthPhirehose {
        private $cobj;
    
        protected $count = 0;
    
        public function __construct($a, $b, $c) {
            parent::__construct($a, $b, $c);
            // Create an object to connect with mysql
            // Open connection
            $this->cobj = new mysqli(db_host, db_user, db_pass, db_name);
        }
    
        //
        // Get all the tweet status' from the Twitter Streaming API
        // Implement enqueueStatus($status)
        //
        public function enqueueStatus($status) {
            // parse the given json object into php object;
            $data = json_decode($status);
    
            // If not delete status push the status into mysql
            $cond = !isset($data->delete) && !isset($data->warning);

            // filter data
            $cond = $cond && !($data->id == 0 || trim($data->user->name) == "" || trim($data->text) == "" || $data->geo == NULL);
            if($cond) {
                // keep a track for count
                ++$this->count;
    
                echo str_pad($this->count, 12, " ", STR_PAD_LEFT).": Getting Tweet: ".$data->id_str." Inserting... ";
    
                // Get the required details
                $id = $data->id_str;
                $idi = $data->id;
                $user = $data->user->name;
                $coord = $data->coordinates->coordinates;
                $lat = $coord[1];
                $lng = $coord[0];
                $tweet = $data->text;
    
                // Create a prepared statement for mysql
                $stmt = $this->cobj->prepare("insert into ".table_name." values(?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdds", $id, $user, $lat, $lng, $tweet);
                // Insert the details
                $stmt->execute();
    
                // If error skip it. Go to next object
                if($stmt->errno) echo "skipped: ".$stmt->error;
                else echo "done. \n";
    
                // close the execution
                $stmt->close();
            }
        }
    }
    
    // Define the consumer and OAuth Keys
    define("TWITTER_CONSUMER_KEY", "b0w50Tgn01ViFWE9ID7FLB4Sl");
    define("TWITTER_CONSUMER_SECRET", "tRzN5tcSCTo2WHfeBpoD8Xflkji4CMsMw2OF6ceSCByRHmsYM8");
    
    define("OAUTH_TOKEN", "173047050-o1Q4wSy123ZvYoL6IwNB6pWKLymB5cWjGETkX8Fx");
    define("OAUTH_SECRET", "661jD0UmDxsYrcxYHqPYJRvVmPpueExDATgYKeF6WnavP");
    
    // stablish a connection to the Twitter API
    $sc = new Consumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_SAMPLE);

    // Filter only english language tweets
    $sc->setLang("en");

    // Start Streaming
    $sc->consume();
?>