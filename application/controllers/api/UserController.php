<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include "smsGateway.php";

class UserController extends CI_Controller {

    private $_jsonData = array();

    public function __construct()
    {
        //parent
        parent::__construct();
        //model
        header('Content-Type: application/json');
        $this->load->library('uuid');
        $this->load->model('User', 'user');

    }

    //Function No 1 Login User
    public function loginUser()
    {
        $userAuthenticator  = $this->input->post('userAuthenticator');
        $userPassword       = base64_encode($this->input->post('userPassword'));
        $userDeviceToken    = $this->input->post('userDeviceToken');

        if(empty($userAuthenticator) || $userAuthenticator === false)
        {
            $this->_jsonData['status']  = 0;
            $this->_jsonData['message'] = "Error! User Email Or Number Is Missing";
            $this->_jsonData['data']    = array();
        }
        else if(empty($userPassword) || $userPassword ===  false)
        {
            $this->_jsonData['status']  = 0;
            $this->_jsonData['message'] = "Error! Password Is Missing";
            $this->_jsonData['data']    = array();
        }
        else if(empty($userDeviceToken) || $userDeviceToken === false)
        {
            $this->_jsonData['status']  = 0;
            $this->_jsonData['message'] = "Error! Device Token Is Missing";
            $this->_jsonData['data']    = array();
        }
        else
        {
            $accessToken = $this->getGeneratedAccessToken();
            $result      = $this->user->loginUserModel($userAuthenticator, $userPassword, $userDeviceToken, $accessToken);

            if($result === false)
            {
                $this->_jsonData['status']  = 0;
                $this->_jsonData['message'] = "Error! Your Credentials Are invlaid";
                $this->_jsonData['data']    = array();
            }
            else if($result === 2)
            {
                $this->_jsonData['status']  = 0;
                $this->_jsonData['message'] = "Sorry! You are a  un-verified user please verify yourself";
                $this->_jsonData['data']    = array();
            }
            else if($result === 3)
            {
                $this->_jsonData['status']  = 0;
                $this->_jsonData['message'] = "Error! You are blocked by admin please check your email for further information";
                $this->_jsonData['data']    = array();
            }
            else
            {
                $this->_jsonData['status']  = 1;
                $this->_jsonData['message'] = "Successfully! Login";
                $this->_jsonData['token']   = $accessToken;
                $this->_jsonData['data']    = $result;
            }
        }

        echo json_encode($this->_jsonData);
    }


    //Function No 2 Logo out Users
    public function logoutUser()
    {
        $userId = $this->input->post('userId');

        if(empty($userId) || $userId === false)
        {
            $this->_jsonData['status']  = 0;
            $this->_jsonData['message'] = "Error! User Id Is Missing";
            $this->_jsonData['data']    = array();
        }
        else if(!$this->user->userExistModel($userId))
        {
            $this->_jsonData['status']  = 0;
            $this->_jsonData['message'] = "Error! No user exist";
            $this->_jsonData['data']    = array();
        }
        else
        {
            $result = $this->user->logoutUserModel($userId);

            if($result)
            {
                $this->_jsonData['status']  = 1;
                $this->_jsonData['message'] = "Successfully Logout";
                $this->_jsonData['data']    = array();
            }
            else
            {
                $this->_jsonData['status']  = 0;
                $this->_jsonData['message'] = "Error! While Log Out";
                $this->_jsonData['data']    = array();
            }
        }
        echo json_encode($this->_jsonData);
    }


    //Function No 3 validate the input, create user and send otp code
    public function createUser()
    {
        $this->form_validation->set_data($_POST);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('userName',           'Username',         'required|min_length[5]|max_length[12]|is_unique[users.userName]');
        $this->form_validation->set_rules('userPhoneNumber',    'Phone Number',     'required|min_length[11]|is_unique[users.userPhoneNumber]');
        $this->form_validation->set_rules('categoryId',         'Category',         'required');
        $this->form_validation->set_rules('locationLatitude',   'Location Lat',     'required');
        $this->form_validation->set_rules('locationLongitude',  'Location Long',    'required');
        $this->form_validation->set_rules('locationName',       'Location Name',    'required');
        $this->form_validation->set_rules('userEmail',          'Email',            'required|valid_email|min_length[12]|is_unique[users.userEmail]');
        $this->form_validation->set_rules('userDeviceToken',    'Firebase',         'required');
        $this->form_validation->set_rules('userPassword',       'Password',         'required');
        $this->form_validation->set_rules('userFullName',       'Full name',        'required');


        if ($this->form_validation->run() === FALSE)
        {
            $this->_jsonData['status'] = 0;
            $this->_jsonData['message'] = validation_errors();
            $this->_jsonData['data'] = array();
        }
        else
        {
            $mobileNumber            = $this->input->post('userPhoneNumber');
            $data['userId']          = $this->uuid->v4();
            $data['userName']        = $this->input->post('userName');
            $data['userPhoneNumber'] = $this->input->post('userPhoneNumber');
            $data['categoryId']      = $this->input->post('categoryId');
            $data['userEmail']       = $this->input->post('userEmail');
            $data['userDeviceToken'] = $this->input->post('userDeviceToken');
            $data['userAccessToken'] = "";
            $data['userPassword']    = base64_encode($this->input->post('userPassword'));
            $data['userFullName']    = $this->input->post('userFullName');
            $data['createdAt']       = date('Y-m-d h:i:s');

            try
            {
                $userId = $this->user->createUserModel($data);
                if($userId)
                {
                    $data = array();
                    $data['locationId']        = $this->uuid->v4();
                    $data['locationLatitude']  = $this->input->post('locationLatitude');
                    $data['locationLongitude'] = $this->input->post('locationLongitude');
                    $data['locationName']      = $this->input->post('locationName');
                    $data['userId']            = $userId;
                    $data['createdAt']         = date('Y-m-d h:i:s');

                    $result = $this->user->insertModel($data, "locations");

                    if($result)
                    {
                        $data = array();
                        $data['userId']           = $userId;
                        $data['verificationId']   = $this->uuid->v4();
                        $data['verificationCode'] = mt_rand(123456, 999999);
                        $data['createdAt']        = date('Y-m-d h:i:s');
                        $code                     = $data['verificationCode'];

                        $result = $this->user->insertModel($data, "verifications");

                        if($result)
                        {
                            $response = $this->sendCode($code, $mobileNumber);
                            if($response)
                            {
                                $this->_jsonData['status']  = 1;
                                $this->_jsonData['message'] = "Success! We Send a OTP Code On Your Number";
                                $this->_jsonData['data']    = $userId;
                            }
                            else
                            {
                                $this->_jsonData['status']  = 0;
                                $this->_jsonData['message'] = "Error! OTP Code Can Not Be Send By The Server";
                                $this->_jsonData['data']    = $userId;
                            }

                        }
                        else
                        {
                            $this->_jsonData['status']  = 0;
                            $this->_jsonData['message'] = "1 Error! While registering";
                            $this->_jsonData['data']    = array();
                        }
                    }
                    else
                    {
                        $this->_jsonData['status']  = 0;
                        $this->_jsonData['message'] = "2 Error! While registering";
                        $this->_jsonData['data']    = array();
                    }

                }
                else
                {
                    $this->_jsonData['status']  = 0;
                    $this->_jsonData['message'] = "4 Error! While registering";
                    $this->_jsonData['data']    = array();
                }

            }
            catch(Exception $e)
            {
                $this->_jsonData['status']  = 0;
                $this->_jsonData['message'] = $e->getMessage();
                $this->_jsonData['data']    = array();
            }
        }

        echo json_encode($this->_jsonData);
    }


    //Function No 4 Get All Available User Type Or Categories
    public function getCategories()
    {
        try
        {
            $columns = "categoryId, categoryName, CONCAT('".base_url()."upload/images/category/', categoryImage) as categoryImage";
            $results = $this->user->getModel('categories', $columns);

            if(empty(array_filter($results)))
            {
                $this->_jsonData['status']  = 0;
                $this->_jsonData['message'] = "Sorry! No Category Found";
                $this->_jsonData['data']    = array();
            }
            else
            {
                $this->_jsonData['status']  = 1;
                $this->_jsonData['message'] = "Successfully found the categories";
                $this->_jsonData['data']    = $results;
            }
        }
        catch(Exception $e)
        {
            $this->_jsonData['status']  = 0;
            $this->_jsonData['message'] = $e->getMessage();
            $this->_jsonData['data']    = array();
        }

        echo json_encode($this->_jsonData);
    }


    //Function No 5 Get All Available Animals
    public function getAnimals()
    {
        try
        {
            $columns = "animalId, animalName, CONCAT('".base_url()."upload/images/animals/', animalImage) as animalImage";
            $results = $this->user->getModel('animals', $columns);

            if(empty(array_filter($results)))
            {
                $this->_jsonData['status']  = 0;
                $this->_jsonData['message'] = "Sorry! No Animal Found";
                $this->_jsonData['data']    = array();
            }
            else
            {
                $this->_jsonData['status']  = 1;
                $this->_jsonData['message'] = "Successfully found the animals";
                $this->_jsonData['data']    = $results;
            }
        }
        catch(Exception $e)
        {
            $this->_jsonData['status']  = 0;
            $this->_jsonData['message'] = $e->getMessage();
            $this->_jsonData['data']    = array();
        }

        echo json_encode($this->_jsonData);
    }


    //Function No 6 Verify user b using otp code
    public function userVerification()
    {
        $verificationCode = $this->input->post('verificationCode');
        $userId           = $this->input->post('userId');

        if(empty($userId) || $userId === false)
        {
            $this->_jsonData['status']  = 0;
            $this->_jsonData['message'] = "Error! No User Id Input";
            $this->_jsonData['data']    = array();
        }
        else if(empty($verificationCode) || $verificationCode === false)
        {
            $this->_jsonData['status']  = 0;
            $this->_jsonData['message'] = "Error! No Verification Code Input";
            $this->_jsonData['data']    = array();
        }
        else
        {
            try
            {
                $isUserExist = $this->user->userExistModel($userId);

                if($isUserExist === false)
                {
                    $this->_jsonData['status']  = 0;
                    $this->_jsonData['message'] = "Error! No User Exist";
                    $this->_jsonData['data']    = array();
                }
                else
                {

                    $results = $this->user->userVerificationModel($userId, $verificationCode);

                    if($results === false)
                    {
                        $this->_jsonData['status']  = 0;
                        $this->_jsonData['message'] = "Error! User Entered A Wrong A OTP";
                        $this->_jsonData['data']    = array();
                    }
                    else
                    {
                        $this->_jsonData['status']  = 1;
                        $this->_jsonData['message'] = "Successfully! Registered to our service";
                        $this->_jsonData['data']    = array();
                    }
                }
            }
            catch(Exception $e)
            {
                $this->_jsonData['status']  = 0;
                $this->_jsonData['message'] = $e->getMessage();
                $this->_jsonData['data']    = array();
            }
        }

        echo json_encode($this->_jsonData);
    }


    //Function No 7 Resend Code if code exit delete it and add new code in the database
    public function resendCode()
    {
        $userId             = $this->input->post('userId');
        $userPhoneNumber    = $this->input->post('userPhoneNumber');

        if(empty($userId) || $userId === false)
        {
            $this->_jsonData['status']  = 0;
            $this->_jsonData['message'] = "Error! No User Id Input";
            $this->_jsonData['data']    = array();
        }
        else if(empty($userPhoneNumber) || $userPhoneNumber === false)
        {
            $this->_jsonData['status']  = 0;
            $this->_jsonData['message'] = "Error! No User Phone Number Input";
            $this->_jsonData['data']    = array();
        }
        else
        {
            try
            {
                $isUserExist = $this->user->userExistModel($userId, true);

                if($isUserExist === false)
                {
                    $this->_jsonData['status']  = 0;
                    $this->_jsonData['message'] = "Error! No User Exist";
                    $this->_jsonData['data']    = array();
                }
                else
                {
                    $userData = $isUserExist;

                    if($userData->userPhoneNumber != $userPhoneNumber)
                    {
                        $this->user->updatePhoneNumberModel($userId, $userPhoneNumber);
                    }

                    $data['verificationId']   = $this->uuid->v4();
                    $data['userId']           = $userId;
                    $data['verificationCode'] = mt_rand(123456, 999999);
                    $data['createdAt']        = date('Y-m-d h:i:s');
                    $data['updatedAt']        = date('Y-m-d h:i:s');
                    $code                     = $data['verificationCode'];

                    $result = $this->user->resendCodeModel($data);

                    if($result)
                    {
                        $response = $this->sendCode($code, $userPhoneNumber);
                        if($response)
                        {
                            $this->_jsonData['status']  = 1;
                            $this->_jsonData['message'] = "Success! We just resend you a OTP Code";
                            $this->_jsonData['data']    = array();
                        }
                        else
                        {
                            $this->_jsonData['status']  = 0;
                            $this->_jsonData['message'] = "Error! Otp Cant be send for some internal issues";
                            $this->_jsonData['data']    = array();
                        }

                    }
                    else
                    {
                        $this->_jsonData['status']  = 0;
                        $this->_jsonData['message'] = "Error! No User Exist";
                        $this->_jsonData['data']    = array();
                    }
                }
            }
            catch(Exception $e)
            {
                $this->_jsonData['status']  = 0;
                $this->_jsonData['message'] = $e->getMessage();
                $this->_jsonData['data']    = array();
            }
        }

        echo json_encode($this->_jsonData);
    }


    //Function No 8 Zoo And Normal user's animal insertion
    public function userAnimals()
    {
        if(empty($_POST['userId']) || $_POST['userId'] === false)
        {
            $this->_jsonData['status']  = 0;
            $this->_jsonData['message'] = "Error! No User Id Input";
            $this->_jsonData['data']    = array();
        }
        else
        {
            try
            {
                $userId       = $this->input->post('userId');
                $isUserExist  = $this->user->userExistModel($userId);

                if($isUserExist === false)
                {
                    $this->_jsonData['status']  = 0;
                    $this->_jsonData['message'] = "Error! No User Exist";
                    $this->_jsonData['data']    = array();
                }
                else
                {
                    if(empty($_POST['animalId']) || $_POST['animalId'] === false)
                    {
                        $this->_jsonData['status']  = 0;
                        $this->_jsonData['message'] = "Error! No Animal Id Input";
                        $this->_jsonData['data']    = array();
                    }
                    else if(empty($_POST['animalQuantity']) || $_POST['animalQuantity'] === false)
                    {
                        $this->_jsonData['status']  = 0;
                        $this->_jsonData['message'] = "Error! No Pet Quantity Input";
                        $this->_jsonData['data']    = array();
                    }
                    else
                    {
                        $animalIds        = explode(",", $this->input->post('animalId'));
                        $animalQuantities = explode(",", $this->input->post('animalQuantity'));

                        for($i=0; $i<count($animalIds); $i++)
                        {
                            if(!empty($animalIds[$i]) && !empty($animalQuantities[$i]))
                            {
                                $data[$i]['usersanimalId']  = $this->uuid->v4();
                                $data[$i]['userId']         = $this->input->post('userId');
                                $data[$i]['animalId']       = $animalIds[$i];
                                $data[$i]['animalQuantity'] = $animalQuantities[$i];
                                $data[$i]['createdAt']      = date('Y-m-d h:i:s');

                            }
                        }

                        $result = $this->user->userAnimalsModel($data, $this->input->post('userId'));

                        if($result)
                        {
                            $this->_jsonData['status']  = 1;
                            $this->_jsonData['message'] = "Successfully Added the pets";
                            $this->_jsonData['data']    = $result;
                        }
                        else
                        {
                            $this->_jsonData['status']  = 0;
                            $this->_jsonData['message'] = "Error! While Inserting the Data";
                            $this->_jsonData['data']    = array();
                        }
                    }
                }
            }
            catch(Exception $e)
            {
                $this->_jsonData['status']  = 0;
                $this->_jsonData['message'] = $e->getMessage();
                $this->_jsonData['data']    = array();
            }

            echo json_encode($this->_jsonData);
        }
    }


    //Function No 9 Get Questions And Answers according to users animals
    public function getUserAnimalsQuestions()
    {
        $animalIds  = explode(",", $this->input->post("animalIds"));
        array_push($animalIds, "general");

        if(empty(array_filter($animalIds)))
        {
            $this->_jsonData['status']  = 0;
            $this->_jsonData['message'] = "Error! Atleast One Animal Id Required";
            $this->_jsonData['data']    = array();
        }
        else
        {
            $result     = $this->user->getUserAnimalsQuestionModel($animalIds);

            if($result)
            {
                $this->_jsonData['status']  = 1;
                $this->_jsonData['message'] = "Success! Questions And Answers";
                $this->_jsonData['data']    = $result;
            }
            else
            {
                $this->_jsonData['status']  = 0;
                $this->_jsonData['message'] = "Error! Getting The Questions";
                $this->_jsonData['data']    = array();
            }
        }


        echo json_encode($this->_jsonData);
    }


    //Function No Get Generated Access Token
    private function getGeneratedAccessToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(64));
    }


    //Function No 9 send verification Code
    private function sendCode($code, $mobileNumber)
    {
        $smsGateway = new SmsGateway(USER_NAME, USER_PASSWORD);

        $deviceID   = 70739;
        $number     = $mobileNumber;
        $message    = "EADMS! Thank You For Trusting Us, This is Your 6 Digit OTP: ".$code;

        $result     = $smsGateway->sendMessageToNumber($number, $message, $deviceID);

        return empty($result['response']['success']['result']['fails']);

    }



}
