# Instapaper XAuth
I dug this out of an old project and thought it'd be worth saving. I remember
back then having real issues trying to find example code.

I did have a quick google and found
[InstapaperXAuth](https://github.com/kinlane/InstapaperXAuth) by [Kin
Lane](http://kinlane.com/2011/06/01/instapaper-full-api-with-xauth-php-class/) but
as the link says, it's a stripped down version of a Twitter OAuth package. This
project is custom built just to work with the Instapaper API, so it's only a handful
of lines long.

# Usage
    <?php
    require 'src/instapaper_xauth.php';
    $insta = new Instapaper_XAuth("CONSUMER_KEY", "CONSUMER_SECRET");
    var_dump($insta->login("USERNAME", "PASSWORD"));

All you need to pass in is your consumer key + secret, and the username and
password of the user you want to authenticate as.

If the credentials are valid, you'll recieve an array with two keys,
'oauth_token' and 'oauth_token_secret'. These can be used to make normal OAuth 
requests.

# Example OAuth request

   This library is so small because it doesn't handle making requests to the
   instapaper API at all. I personally like using
   [OAuth](http://pecl.php.net/package/oauth) in PECL for all of my OAuth related
   needs. Here's an example of how to verify your credentials:

    $api_url = 'https://www.instapaper.com/api/1/'; 
    $oauth = new OAuth("CONSUMER_KEY", "CONSUMER_SECRET", OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);                      
    $oauth->setToken("OAUTH_TOKEN", "OAUTH_TOKEN_SECRET" );           
    $oauth->fetch($api_url.'account/verify_credentials');                                
    echo $oauth->getLastResponse(); 

# Fin

There we have it, a request to the Instapaper API in just 6 lines of code :)


