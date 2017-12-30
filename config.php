<?php

//site
@define('SITE', 'www.eqpredictor.com');

@define('API_KEY', 'AIzaSyDpryDy8O7eB2pEyDcOXgju7A2Gl6LGkCU');
@define('USER_PASSWORD', 'AAlogics123');
@define('USER_NAME', 'farhanahmed1244@gmail.com');
@define('USER_NAME2', 'farhanahmed1244@gmail.com');

//server handling
if ($_SERVER['SERVER_NAME'] == "localhost") {
    @define('DB_HOST', 'localhost');
    @define('DB_USER', 'root');
    @define('DB_PASS', '');
    @define('DB_NAME', 'eadms');
} else {
    @define('DB_HOST', 'eqpredictor.com.mysql');
    @define('DB_USER', 'eqpredictor_com');
    @define('DB_PASS', 'u7PastagZrXwmSJJ5gjkHLj9');
    @define('DB_NAME', 'eqpredictor_com');
}


//environment
//@define('ENVIRONMENT', 'development');

?>
