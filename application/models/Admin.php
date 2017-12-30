<?php

class Admin extends CI_Model
{
    public function adminLoginModel($adminEmail, $adminPassword)
    {
        $this->db->select("adminId, adminName, adminEmail, CONCAT('".base_url()."upload/images/admin/', adminImage) as adminImage");
        $this->db->where(['adminEmail' => $adminEmail, 'adminPassword' => $adminPassword]);
        $result = $this->db->get('admin');

        return $result->row_array();
    }

    public function registeredUserCount()
    {
        $this->db->select('COUNT(userId) as count');
        $this->db->group_by('categoryId');
        $results = $this->db->get('users');

        return $results->result_array();
    }

}