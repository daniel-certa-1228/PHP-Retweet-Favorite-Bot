<?php
    require "vendor/abraham/twitteroauth/autoload.php";
    use Abraham\TwitterOAuth\TwitterOAuth;

    $consumerKey = getenv('TWITTER_CONSUMER_KEY_CB'); // Consumer Key
    $consumerSecret = getenv('TWITTER_CONSUMER_SECRET_CB'); // Consumer Secret
    $accessToken = getenv('TWITTER_ACCESS_TOKEN_CB'); // Access Token
    $accessTokenSecret = getenv('TWITTER_ACCESS_TOKEN_SECRET_CB'); // Access Token Secret
    //connect to Twitter using credentials from env
    $connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
    // var_dump($connection);  //inspect the connection vars if necessary

    $already_tweeted = []; //array to store the ids of tweets that have already been retweeted (most recent 30);
    $home_check = $connection->get("statuses/home_timeline", ["count" => 30]); //connect to twitter and get the last 30 tweets
    //loop through the tweets and push the ids to $already_tweeted 
    for ($i = 0; $i < count($home_check); $i++) {
        $current_tweet_ids = (get_object_vars($home_check[$i]))["id"]; //parse the ids from the stdClass object
        array_push($already_tweeted, $current_tweet_ids);
    }
    $already_tweeted = array_reverse($already_tweeted);
    // var_dump($already_tweeted);  //inspect the $already_tweeted if necessary
    
    //search twitter for references to "Catalina Islnad"
    $catalina_search = $connection->get("search/tweets", ["q" => "Catalina Island", "result_type" => "recent", "count" => 30]);
    $catalina_tweets = get_object_vars($catalina_search); //parse the ids from the stdClass object
    $num_of_tweets = count($catalina_tweets["statuses"]); //generate a count based on the number of pulled tweets
    // echo $num_of_tweets;

    $new_ids = []; // array to store the ids of the searched tweets

    //loop through and push the ids of the searched tweets to $new_ids
    for ($i = 0; $i < $num_of_tweets; $i++) {
        $tweet_ids = get_object_vars(get_object_vars($catalina_search)["statuses"][$i])["id"];
        array_push($new_ids, $tweet_ids);
    }
    
    $new_ids = array_reverse($new_ids); //reverse order of tweet ids so most recent is first
    // var_dump($new_ids); //inspect array if necessary
    $tweet_count = 0;  //instatiate a counter to keep track of how many tweets were retweeted.

    for ($i = 0; $i < count($new_ids); $i++) {  
        if (!in_array($new_ids[$i], $already_tweeted)) {
            //compare the newly searched ids with the ids in the $already_tweeted array.  If the new staus has not been retweeted yet, then favorite and retweet commands are sent.
            $retweet_id = $new_ids[$i];
            // var_dump($retweet_id); 

            //TWITTER TOS NO LONGER ALLOWS AUTO-FAVORITING   
            // $favorites = $connection->post("favorites/create", ["id" => "$retweet_id"]);s
            // var_dump($favorites);
            
            $retweet = $connection->post("statuses/retweet/$retweet_id");
            // var_dump($retweet);  
            $tweet_count++;
            }
        }
    print "Catalina Bot retweeted $tweet_count statuses.\n";
?>