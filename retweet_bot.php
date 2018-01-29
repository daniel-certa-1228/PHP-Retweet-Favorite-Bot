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

    $already_tweeted = [];
    $home_check = $connection->get("statuses/home_timeline", ["count" => 20]);

    for ($i = 0; $i < count($home_check); $i++) {
        $current_tweet_ids = (get_object_vars($home_check[$i]))["id"];
        array_push($already_tweeted, $current_tweet_ids);
    }
    // var_dump($already_tweeted);
    
    $catalina_search = $connection->get("search/tweets", ["q" => "Catalina Island", "result_type" => "recent", "count" => 20]);
    $catalina_tweets = get_object_vars($catalina_search);
    $num_of_tweets = count($catalina_tweets["statuses"]);
    // echo $num_of_tweets;
    $new_ids = [];

    for ($i = 0; $i < $num_of_tweets; $i++) {
        $tweet_ids = get_object_vars(get_object_vars($catalina_search)["statuses"][$i])["id"];
        array_push($new_ids, $tweet_ids);
    }

    $new_ids = array_reverse($new_ids);
    // var_dump($new_ids);

    for ($i = 0; $i < count($new_ids); $i++) {
        if (!in_array($new_ids[$i], $already_tweeted)) {
            $retweet_id = $new_ids[$i];
            $favorites = $connection->post("favorites/create", ["id" => "$retweet_id"]);
            $retweet = $connection->post("statuses/retweet/$retweet_id");
            var_dump($retweet_id);
        }
    }
?>