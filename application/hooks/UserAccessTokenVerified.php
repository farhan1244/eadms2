<?php

class UserAccessTokenVerified
{
    private $CI         = "";
    private $_jsonData  = [];

    public function verifyToken()
    {
        $this->CI   = & get_instance();

        $loginUrl       = base_url()."login-user";
        $signUpUrl      = base_url()."create-user";
        $categoryUrl    = base_url()."get-categories";
        $verifyUserUrl  = base_url()."verify-user";
        $currentUrl     = base_url(uri_string());

        if($currentUrl !== $loginUrl && $currentUrl !== $signUpUrl && $currentUrl !== $categoryUrl && $verifyUserUrl !== $currentUrl)
        {
            $this->CI->load->model("User", "user");

            $accessToken =  $this->CI->input->get_request_header('Authentication', TRUE);
            $result      =  $this->CI->user->checkAccessToken($accessToken);

            if(!$result)
            {
                $this->_jsonData['status']  = 0;
                $this->_jsonData['message'] = "Sorry! Access Token Is Not valid";
                $this->_jsonData['data']    = [];

                echo json_encode($this->_jsonData);
                exit;
            }
        }
    }
}