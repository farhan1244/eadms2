<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller']    = 'HomeController';
$route['404_override']          = '';
$route['translate_uri_dashes']  = FALSE;

$route['create-user']           = 'api/UserController/createUser';
$route['get-categories']        = 'api/UserController/getCategories';
$route['verify-user']           = 'api/UserController/userVerification';
$route['login-user']            = 'api/UserController/loginUser';
$route['logout-user']           = 'api/UserController/logoutUser';
$route['get-animals']           = 'api/UserController/getAnimals';
$route['resend-code']           = 'api/UserController/resendCode';
$route['user-animals']          = 'api/UserController/userAnimals';
$route['user-questions']        = 'api/UserController/getUserAnimalsQuestions';




//Admin Routes

$route['admin-login']          = 'admin/AdminController/adminLogin';
$route['admin-dashboard']      = 'admin/AdminController/index';
$route['admin-logout']         = 'admin/AdminController/adminLogout';