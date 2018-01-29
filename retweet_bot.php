<?php
    require "vendor/abraham/twitteroauth/autoload.php";
    use Abraham\TwitterOAuth\TwitterOAuth;

    $consumerKey = getenv('TWITTER_CONSUMER_KEY_CB'); // Consumer Key
    $consumerSecret = getenv('TWITTER_CONSUMER_SECRET_CB'); // Consumer Secret
    $accessToken = getenv('TWITTER_ACCESS_TOKEN_CB'); // Access Token
    $accessTokenSecret = getenv('TWITTER_ACCESS_TOKEN_SECRET_CB'); // Access Token Secret
    //connect to Twitter using credentials from env
    $connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
    // var_dump($connection);
    
    $catalina_search = $connection->get("search/tweets", ["q" => "Catalina Island", "result_type" => "recent", "count" => 20]);
    $catalina_tweets = get_object_vars($catalina_search);
    $num_of_tweets = count($catalina_tweets["statuses"]);
    // echo $num_of_tweets;

    for ($i = 0; $i < $num_of_tweets; $i++) {
        // $tweet_ids = get_object_vars(get_object_vars($catalina_search)["statuses"][$i])["id"];
        // echo $tweet_ids . "\n";
        echo $i . "\n";
        // $favorites = $connection->post("favorites/create", ["id" => "$tweet_ids"]);
        // $retweet = $connection->post("statuses/retweet/$tweet_ids");
    }

    // var_dump(get_object_vars(get_object_vars($catalina_search)["statuses"][0])["id"]);
    // echo($catalina_search)
    // $status = $connection->post("statuses/update", array("status" => "TEST"));

    // var_dump($status);//print the status from twitter to the console

?>