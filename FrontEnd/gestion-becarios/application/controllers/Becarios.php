<?php
defined('BASEPATH') OR exit('');



class Becarios extends CI_Controller{

    public function __construct(){
        parent::__construct();

        $this->genlib->checkLogin();

        $this->load->model(['becario','asignacion']);
    }

    /**
     *
     */
    public function index(){

        $data['pageContent'] = $this->load->view('becarios/becarios', '', TRUE);
        $data['pageTitle'] = "Becarios";

        $this->load->view('main', $data);
    }


    public function cargarBecarios(){
        $this->genlib->ajaxOnly();

        $this->load->helper('text');

        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";

        //count the total number of items in db
        $totalBecarios= $this->db->count_all('becarios');

        $this->load->library('pagination');

        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri

        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration


        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalBecarios, "becarios/cargarBecarios", $limit, ['onclick'=>'return cargarBecarios(this.href);']);

        $this->pagination->initialize($config);//initialize the library class

        //get all items from db
        $data['allBecarios'] = $this->becario->getAll($orderBy, $orderFormat, $start, $limit);
        $data['allAsignaciones']=$this->asignacion->getAll("trabajo_name", "ASC");
        $data['range'] = $totalBecarios > 0 ? "Mostrando " . ($start+1) . "-" . ($start + count($data['allBecarios'])) . " de " . $totalBecarios : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;


        $json['becariosListTable'] = $this->load->view('becarios/becarioslisttable', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }



    public function add(){
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('becarioName', 'Becario name', ['required', 'trim', 'max_length[80]', 'is_unique[becarios.name]'],
            ['required'=>"required", 'is_unique'=>"Ya existe un becario de ese nombre"]);
        $this->form_validation->set_rules('becarioCode', 'Becario Code', ['required', 'trim', 'max_length[20]', 'is_unique[becarios.code]'],
            ['required'=>"required", 'is_unique'=>"Ya existe un becario con el código indicado"]);


        if($this->form_validation->run() !== FALSE){
            $this->db->trans_start();//start transaction


            $insertedId = $this->becario->add(set_value('becarioName'), set_value('becarioCode'));

            $becarioName = set_value('becarioName');
            $becarioCode = set_value('becarioCode');


            //insert into eventlog
            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $desc = "Inscripción del becario {$becarioName} de código UPB {$becarioCode}";

            $insertedId ? $this->genmod->addevent("Inscripción de becario", $insertedId, $desc, "becarios", $this->session->admin_id) : "";

            $this->db->trans_complete();

            $json = $this->db->trans_status() !== FALSE ?
                ['status'=>1, 'msg'=>"Se inscribió al becario al sistema correctamente"]
                :
                ['status'=>0, 'msg'=>"Se generó un problema al inscribir al becario...Intente nuevamente"];
        }

        else{

            $json = $this->form_validation->error_array();

            $json['msg'] = "Verifique que todos los campos hayan sido llenados correctamente";
            $json['status'] = 0;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /**
     * Primarily used to check whether an item already has a particular random code being generated for a new item
     * @param type $selColName
     * @param type $whereColName
     * @param type $colValue
     */
    public function gettablecol($selColName, $whereColName, $colValue){
        $a = $this->genmod->gettablecol('becarios', $selColName, $whereColName, $colValue);

        $json['status'] = $a ? 1 : 0;
        $json['colVal'] = $a;


        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /**
     *
     */
    public function getcodenameandhours(){
        $json['status'] = 0;

        $becarioCode = $this->input->get('_bC', TRUE);


        if($becarioCode){
            $becario_info = $this->becario->getBecarioInfo(['code'=>$becarioCode], ['missinghours','name','id']);

            if($becario_info){
                $json['missinghours'] = $becario_info->missinghours;
                $json['name'] = $becario_info->name;
                $json['becarioId'] = $becario_info->id;
                $json['status'] = 1;
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }



    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    public function updateMissingHours(){
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('_bId', 'Becario ID', ['required', 'trim', 'numeric'], ['required'=>"required"]);
        $this->form_validation->set_rules('mhUpdateMissingHours', 'Becario missing hours', ['required', 'trim', 'numeric'], ['required'=>"required"]);


        if($this->form_validation->run() !== FALSE){
            //update stock based on the update type

            $becarioId = set_value('_bId');
            $mhUpdateMissingHours = set_value('mhUpdateMissingHours');


            $this->db->trans_start();

            $updated = $this->becario->updateMissingHours($becarioId, $mhUpdateMissingHours) ;


            //add event to log if successful

            $event = "Modificado de horas de trabajo becario a cumplir";


            $eventDesc = "<p>{$mhUpdateMissingHours} horas a cumplir de trabajo becario para {$this->genmod->gettablecol('becarios', 'name', 'id', $becarioId)} fueron establecidas</p>";

            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $updated ? $this->genmod->addevent($event, $becarioId, $eventDesc, "becarios", $this->session->admin_id) : "";

            $this->db->trans_complete();//end transaction

            $json['status'] = $this->db->trans_status() !== FALSE ? 1 : 0;
            $json['msg'] = $updated ? "La cantidad de horas de trabajo becario a cumplir ha sido modificada" : "Se generó un problema al modificar las horas de trabajo becario a cumplir...Intente nuevamente";
        }

        else{
            $json['status'] = 0;
            $json['msg'] = "Verifique que todos los campos hayan sido llenados correctamente";
            $json = $this->form_validation->error_array();
        }


        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /*
     ********************************************************************************************************************************
     ********************************************************************************************************************************
     ********************************************************************************************************************************
     ********************************************************************************************************************************
     ********************************************************************************************************************************
     */

    public function edit(){
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('_bId', 'Becario ID', ['required', 'trim', 'numeric']);
        $this->form_validation->set_rules('becarioName', 'Becario Name', ['required', 'trim',
            'callback_crosscheckName['.$this->input->post('_bId', TRUE).']'], ['required'=>'required']);
        $this->form_validation->set_rules('becarioCode', 'Becario Code', ['required', 'trim',
            'callback_crosscheckCode['.$this->input->post('_bId', TRUE).']'], ['required'=>'required']);

        if($this->form_validation->run() !== FALSE){
            $becarioId = set_value('_bId');
            $becarioName = set_value('becarioName');
            $becarioCode = set_value('becarioCode');

            //update item in db
            $updated = $this->becario->edit($becarioId, $becarioName, $becarioCode);

            $updated2 = $this->asignacion->editByBecario($becarioCode,$becarioName,$becarioId);

            $json['status'] = $updated && $updated2 ? 1 : 0;

            //add event to log
            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $desc = "El becario {$becarioCode} fue editado";

            $this->genmod->addevent("Edición de becario", $becarioId, $desc, 'becarios', $this->session->admin_id);
        }

        else{
            $json['status'] = 0;
            $json = $this->form_validation->error_array();
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }




    /*
     ********************************************************************************************************************************
     ********************************************************************************************************************************
     ********************************************************************************************************************************
     ********************************************************************************************************************************
     ********************************************************************************************************************************
     */

    public function crosscheckName($becarioName, $becarioId){
        //check db to ensure name was previously used for the item we are updating
        $becarioConNombre = $this->genmod->getTableCol('becarios', 'id', 'name', $becarioName);


        if(!$becarioConNombre || ($becarioConNombre == $becarioId)){
            return TRUE;
        }

        else{//if it exist
            $this->form_validation->set_message('crosscheckName', 'Ya existe un becario registrado con este nombre');

            return FALSE;
        }
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    /**
     *
     * @param type $item_code
     * @param type $item_id
     * @return boolean
     */
    public function crosscheckCode($becario_code, $becario_id){
        //check db to ensure item code was previously used for the item we are updating
        $becarioConCodigo = $this->genmod->getTableCol('becarios', 'id', 'code', $becario_code);

        //if item code does not exist or it exist but it's the code of current item
        if(!$becarioConCodigo || ($becarioConCodigo == $becario_id)){
            return TRUE;
        }

        else{//if it exist
            $this->form_validation->set_message('crosscheckCode', 'Ya existe un becario registrado con este nombre');

            return FALSE;
        }
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    public function delete(){
        $this->genlib->ajaxOnly();

        $json['status'] = 0;
        $becario_id = $this->input->post('b', TRUE);
        $becario_name = $this->input->post('bn', TRUE);

        if($becario_id && $becario_name){

            $this->db->delete('becarios', array('id' => $becario_id));
            $this->db->delete('asignaciones', array('becarioName' => $becario_name));
            $json['status'] = 1;
        }

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function report(){
        //get all transactions from db ranging from $from_date to $to_date


    }
}