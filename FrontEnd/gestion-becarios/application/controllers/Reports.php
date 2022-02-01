<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Reports
 *
 */
class Reports extends CI_Controller{

    public function __construct(){
        parent::__construct();

        $this->genlib->checkLogin();

        $this->genlib->superOnly();
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    public function index(){
        $data['pageContent'] = $this->load->view('reports', '', TRUE);
        $data['pageTitle'] = "Reports";

        $this->load->view('main', $data);
    }

    public function gettablecol($selColName, $whereColName, $colValue)
    {
        $a = $this->genmod->gettablecol('semesters', $selColName, $whereColName, $colValue);

        $json['status'] = $a ? 1 : 0;
        $json['colVal'] = $a;

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

}