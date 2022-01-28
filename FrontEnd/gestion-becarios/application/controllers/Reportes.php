<?php
defined('BASEPATH') OR exit('');



class Reportes extends CI_Controller{

    public function __construct(){
        parent::__construct();

        $this->genlib->checkLogin();

        $this->load->model(['becario','asignacion','trabajo','gestion']);
    }

    /**
     *
     */
    public function index(){
        $resData['gestiones'] = $this->gestion->getAll('name', 'DESC');

        $data['pageContent'] = $this->load->view('reportes/reportes', $resData, TRUE);
        $data['pageTitle'] = "Reportes";


        $this->load->view('main', $data);
    }

    public function cargarReportes(){
        $this->genlib->ajaxOnly();

        $this->load->helper('text');

        $value->$this->input->get('value', TRUE);
        $semester->$this->input->get('semester', TRUE);
        $option->$this->input->get('option', TRUE);

        $data['allInfo'] =  $option==="becario" ? $this->becario->getReport($value,$semester) : $this->trabajo->getReport($value,$semester) ;
        $data['type']=$option;
        $data['sn']=1;

        $json['reportesListTable'] = $this->load->view('reportes/reporteslisttable', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));   
    }



}