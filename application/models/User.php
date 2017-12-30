<?php

class User extends CI_Model
{
    public function getModel($table, $columns)
    {
        $this->db->select($columns);
        return $this->db->get($table)->result_array();
    }

    public function createUserModel($data)
    {
        $result = $this->db->insert('users', $data);

        if($result)
        {
            return $data['userId'];
        }
        else
        {
            return false;
        }
    }

    public function insertModel($data, $table)
    {
        return $this->db->insert($table, $data);
    }

    public function userExistModel($userId, $withData = 0)
    {
        $user = $this->db->get_where('users', ['userId' => $userId]);

        if($withData === 0)
        {
            return $user->num_rows() > 0? true: false;
        }
        else
        {
            return $user->num_rows() > 0? $user->row(): false;
        }
    }

    public function resendCodeModel($data)
    {
        $userId = $data['userId'];
        $isRowExist = $this->db->get_where('verifications', ['userId' => $userId]);

        if($isRowExist->num_rows() > 0)
        {
            $this->db->where('userId', $userId);
            $this->db->delete('verifications');
        }

        return $this->db->insert('verifications', $data);
    }

    public function userVerificationModel($userId, $verificationCode)
    {
        $this->db->where(['userId' => $userId, 'verificationCode' => $verificationCode]);

        $result = $this->db->get('verifications');

        if($result->num_rows() > 0)
        {
            $verificationId = $result->row()->verificationId;

            $this->db->where('verificationId', $verificationId);
            $this->db->delete('verifications');//Deleting a Verification Row So no conflict in future

            $this->db->where('userId', $userId);
            $result = $this->db->update('users', ['userIsVerified' => 1, 'updatedAt' => date('Y-m-d h:i:s')]);

            if($result)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public function updatePhoneNumberModel($userId, $userPhoneNumber)
    {
        $this->db->where('userId', $userId);

        return $this->db->update('users', ['userPhoneNumber' => $userPhoneNumber]);
    }

    public function userAnimalsModel($data, $userId)
    {
        $result =  $this->db->insert_batch('usersanimals', $data);

        if($result)
        {
            $result = $this->userGetModel($userId);
            return $result;
        }
        else
        {
            return false;
        }
    }

    public function userCategoryModel($userId)
    {
        $this->db->select('c.categoryName');
        $this->db->from('users as u');
        $this->db->where('userId', $userId);
        $this->db->join('categories as c', 'c.categoryId = u.categoryId', 'inner');
        $result = $this->db->get()->row();

        return $result->categoryName;
    }

    public function userGetModel($userId)
    {
        $this->db->select('u.userId, u.userName, u.userFullName, u.userPhoneNumber, u.userEmail, u.createdAt, u.userDeviceToken');
        $this->db->select("c.categoryName, l.locationName, l.locationLongitude, l.locationLatitude, '1' as locationIsSafe");
        $this->db->select("IFNULL(GROUP_CONCAT(a.animalId), '') as animalId");
        $this->db->from('users as u');
        $this->db->join('categories as c', 'c.categoryId = u.categoryId', 'inner');
        $this->db->join('locations as l', 'l.userId = u.userId', 'inner');
        $this->db->join('usersanimals as a', 'a.userId = u.userId', 'left');
        $this->db->where(['u.userId' => $userId, 'u.userIsVerified' => '1']);
       // return $this->db->get_compiled_select();
        return $this->db->get()->row_array();
    }

    public function loginUserModel($userAuthenticator, $userPassword, $userDeviceToken, $accessToken)
    {
        $this->db->select('userId, userIsVerified, userIsBlocked');
        $this->db->where("(userEmail = '$userAuthenticator' OR userPhoneNumber = '$userAuthenticator') AND userPassword = '$userPassword'");

        $result = $this->db->get('users')->row();

        if(empty($result->userId))
        {
            return false;
        }
        else if($result->userIsVerified === '0')
        {
            return 2;
        }
        else if($result->userIsBlocked === '1')
        {
            return 3;
        }
        else
        {
            $this->db->where('userId', $result->userId);
            $this->db->update('users', ['userDeviceToken' => $userDeviceToken, 'userAccessToken' => $accessToken]);
            return $this->userGetModel($result->userId);

        }
    }

    public function logoutUserModel($userId)
    {
        $this->db->where('userId', $userId);
        return $this->db->update('users', ['userDeviceToken' => ""]);
    }

    public function getUserAnimalsQuestionModel($animalIds)
    {
        $this->db->select("q.questionId, q.questionText, q.questionAnswerType");
        $this->db->select("GROUP_CONCAT(a.answerId) as answerIds");
        $this->db->select("GROUP_CONCAT(a.answerText) as answerText");
        $this->db->from("questions as q");
        $this->db->join("answers as a", "a.answerQuestionId = q.questionId", "inner");
        $this->db->where_in("q.animalId", $animalIds);
        $this->db->group_by("q.questionId");
        $this->db->order_by("q.createdAt", "DESC");
        $results = $this->db->get();

        return $results->result_array();
    }

    public function checkAccessToken($accessToken)
    {
        $result = $this->db->get_where("users", ['userAccessToken' => $accessToken]);

        if($result->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}

?>