<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class HomeController extends CI_Controller
{
    public function index()
    {
//        $url = base_url()."admin-dashboard";
//        redirect($url);
        $this->load->view('coming_soon/index');
    }

    public function getCreate()
    {
        $this->load->library('uuid');
        $this->load->model('User', 'user');

        $data['animalId'] = $this->uuid->v4();
        $data['animalName'] = $this->input->post('name');
        $data['animalPredicationQuality'] = $this->input->post('pred');
        $data['createdAt'] = date('Y-m-d h:i:s');
        $data['updatedAt'] = date('Y-m-d h:i:s');

        echo $this->user->insertModel($data, 'animals');
    }

    public function coe()
    {
        $arr[0] = ["user" => "USER", "id" => "ID"];
        $arr2[1] = ["user2" => "USER2", "id2" => "ID2"];

        $array = array_merge($arr, $arr2);
        $category['name'] = "Name";
        array_unshift($array, $category);
        echo json_encode($array);
    }
}

?>