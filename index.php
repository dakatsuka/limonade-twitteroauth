<?php
session_start();


require_once dirname(__FILE__) . '/lib/limonade/lib/limonade.php';
require_once dirname(__FILE__) . '/lib/twitteroauth/twitteroauth/twitteroauth.php';


define('CONSUMER_KEY',    'xxxxxxxxx');
define('CONSUMER_SECRET', 'xxxxxxxxx');
define('CALLBACK_URI',    '/oauth/twitter/callback');


option('views_dir', dirname(__FILE__) . '/views');


dispatch('/', function() {
    if (isset($_SESSION['screen_name'])) {
        return redirect('/home');
    } else {
        return render('index.html.php');
    }
});


dispatch('/oauth/twitter', function() {
    $oa = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $request_token = $oa->getRequestToken(CALLBACK_URI);
    $_SESSION['request_token'] = $request_token['oauth_token'];
    $_SESSION['request_token_secret'] = $request_token['oauth_token_secret'];

    return redirect($oa->getAuthorizeURL($_SESSION['request_token']));
});


dispatch('/oauth/twitter/callback', function() {
    $oa = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['request_token'], $_SESSION['request_token_secret']);
    $access_token = $oa->getAccessToken();
    $_SESSION['screen_name'] = $access_token['screen_name'];
    $_SESSION['access_token'] = $access_token['oauth_token'];
    $_SESSION['access_token_secret'] = $access_token['oauth_token_secret'];
    return redirect('/home/');
});


dispatch('/home', function() {
    if (isset($_SESSION['screen_name'])) {
        $oa = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['access_token'], $_SESSION['access_token_secret']);
        $timeline = $oa->OAuthRequest('http://api.twitter.com/1/statuses/home_timeline.xml', 'GET', array('count' => 30));
        $timeline = simplexml_load_string($timeline);
        set('timeline', $timeline);
        return render('home.html.php');
    } else {
        return redirect('/');
    }
});


dispatch('/signout', function() {
    session_destroy();
    return redirect('/');
});

run();
?>
