<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$hook['post_controller_constructor'] = array(
    'class'    => 'UserAccessTokenVerified',
    'function' => 'verifyToken',
    'filename' => 'UserAccessTokenVerified.php',
    'filepath' => 'hooks',
    'params'   => array()
);
