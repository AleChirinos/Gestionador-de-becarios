<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Search
 *
 */

class Search extends CI_Controller{
    protected $value;

    public function __construct() {
        parent::__construct();

        //$this->gen->checklogin();

        $this->genlib->ajaxOnly();

        $this->load->model(['item','becario', 'trabajo','asignacion','admin','semester']);

        $this->load->helper('text');

        $this->value = $this->input->get('v', TRUE);

        $this->value2=$this->input->get('s',TRUE);
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    public function index(){
        /**
         * function will call models to do all kinds of search just to check whether there is a match for the searched value
         * in the search criteria or not. This applies only to global search
         */



        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    public function semesterSearch(){
        $data['allItems'] = $this->semester->semestersearch($this->value);
        $data['sn'] = 1;
        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('semesters/itemslisttable', $data, TRUE) : "No se encontraron resultados";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    public function adminsearch(){
        $data['allAdministrators'] = $this->admin->adminSearch($this->value);
        $data['sn'] = 1;


        $json['allAdmin'] = $data['allAdministrators'] ? $this->load->view('admin/adminlist', $data, TRUE) : "No se encontraron resultados";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function itemSearch(){
        $data['allItems'] = $this->item->itemsearch($this->value);
        $data['sn'] = 1;
        $data['cum_total'] = $this->item->getItemsCumTotal();

        $json['itemsListTable'] = $data['allItems'] ? $this->load->view('items/itemslisttable', $data, TRUE) : "No match found";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function becarioSearch(){

        $data['allBecarios'] = $this->becario->becariosearch($this->value);

        $data['sn'] = 1;
        $data['allAsignaciones']=$this->asignacion->getAll("trabajo_name", "ASC");

        $json['becariosListTable'] = $data['allBecarios'] ? $this->load->view('becarios/becarioslisttable', $data, TRUE) : "No existen coincidencias";


        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function becarioSemSearch(){

        $data['allBecarios'] = $this->becario->becarioSemsearch($this->value,$this->session->admin_career);
        
        $json['semester']=$this->session->admin_career;
        $json['allData'] = $data['allBecarios'];
        $json['status'] = 1;

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function trabajoSearch(){

        $data['allTrabajos'] = $this->trabajo->trabajosearch($this->value);

        $data['sn'] = 1;
        $data['mark']=1;
        $data['allAsignaciones']=$this->asignacion->getAll("trabajo_name", "ASC");

        $json['trabajosListTable'] = $data['allTrabajos'] ? $this->load->view('trabajos/trabajoslisttable', $data, TRUE) : "No existen coincidencias";


        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    public function asignadoSearch(){

        $data['allAsignados'] = $this->asignacion->becariossearch($this->value);

        $data['sn'] = 1;
        $data['mark']= 1;

        $json['allAsignados'] = $data['allAsignados'];

        $json['status'] = $data['allAsignados'] ? 1 : 0;

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    public function dataSearch(){
        $data['allData'] = $this->value==="becario" ? $this->becario->getBySemester($this->value2) : $this->trabajo->getBySemester($this->value2);

        $data['sn'] = 1;

        $json['allData'] = $data['allData'];
        $json['status'] = $data['allData'] ? 1 : 0;

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */



    public function transSearch(){
        $data['allTransactions'] = $this->transaction->transsearch($this->value);
        $data['sn'] = 1;

        $json['transTable'] = $data['allTransactions'] ? $this->load->view('transactions/transtable', $data, TRUE) : "No match found";

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function becarioPublicSearch(){
        $data['allBecarioInfo'] = $this->becario->getBecarioInfo(['code'=>$this->value], ['code','semester','missinghours','name','id']);
        $data['allTrabajosInfo']= $this->asignacion->getBecarios(['becarioCode'=>$this->value], ['trabajo_name','hours','assignDate','accomplished']);
        $misHours=$this->genmod->getTableCol('becarios', 'missinghours', 'code', $this->value);
        $asigHours=$this->genmod->getTableCol('asignaciones', 'hours', 'becarioCode', $this->value) ? $this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'becarioCode', $this->value,'accomplished',0) : 0;
        $accoHours=$this->genmod->getTableCol('asignaciones', 'hours', 'becarioCode', $this->value) ? $this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'becarioCode', $this->value,'accomplished',1) : 0;

        $data['hoursTot']= $misHours + $asigHours + $accoHours;
        $data['sn'] = 1;
        $json['becarioInfoListTable'] = $data['allBecarioInfo'] ? $this->load->view('dashboardlisttable', $data, TRUE) : "No existen coincidencias";
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    public function otherSearch(){
       

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
}
