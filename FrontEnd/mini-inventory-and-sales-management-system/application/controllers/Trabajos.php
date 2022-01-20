<?php
defined('BASEPATH') OR exit('');
require_once 'functions.php';



class Trabajos extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        
        $this->genlib->checkLogin();
        
        $this->load->model(['trabajo','becario','asignacion']);
    }
    
    /**
     * 
     */
    public function index(){
        $resData['becarios'] = $this->becario->getAll('name', 'ASC');

        $data['pageContent'] = $this->load->view('trabajos/trabajos', $resData, TRUE);
        $data['pageTitle'] = "Trabajos";

        $this->load->view('main', $data);
    }
    

    public function cargarTrabajos(){
        $this->genlib->ajaxOnly();

        $this->load->helper('text');

        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        
        //count the total number of items in db
        $totalTrabajos= $this->db->count_all('trabajos');
        
        $this->load->library('pagination');
        
        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri
	
        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration


        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalTrabajos, "trabajos/cargarTrabajos", $limit, ['onclick'=>'return cargarTrabajos(this.href);']);
        
        $this->pagination->initialize($config);//initialize the library class
        
        //get all items from db
        $data['allTrabajos'] = $this->trabajo->getAll($orderBy, $orderFormat, $start, $limit);
        $data['allAsignaciones']=$this->asignacion->getAll("trabajo_name", "ASC");
        $data['range'] = $totalTrabajos > 0 ? "Mostrando " . ($start+1) . "-" . ($start + count($data['allTrabajos'])) . " de " . $totalTrabajos : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;

        
        $json['trabajosListTable'] = $this->load->view('trabajos/trabajoslisttable', $data, TRUE);//get view with populated items table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    

    
    public function add(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('trabajoName', 'Trabajo name', ['required', 'trim', 'max_length[80]', 'is_unique[trabajos.name]'],
                ['required'=>"required", 'is_unique'=>"Ya existe un un trabajo con ese nombre"]);
        $this->form_validation->set_rules('trabajoHours', 'Trabajo Hours', ['required', 'trim', 'numeric'], ['required'=>"required"]);

        
        if($this->form_validation->run() !== FALSE){
            $this->db->trans_start();//start transaction
            

            $insertedId = $this->trabajo->add(set_value('trabajoName'), set_value('trabajoDesc'), set_value('trabajoHours'));
            
            $trabajoName = set_value('trabajoName');
            $trabajoHours = set_value('trabajoHours');

            
            //insert into eventlog
            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $desc = "Creación del trabajo {$trabajoName} con horas requeridas {$trabajoHours}";
            
            $insertedId ? $this->genmod->addevent("Creación del trabajo", $insertedId, $desc, "trabajos", $this->session->admin_id) : "";
            
            $this->db->trans_complete();
            
            $json = $this->db->trans_status() !== FALSE ? 
                    ['status'=>1, 'msg'=>"Se creo el trabajo en el sistema correctamente"]
                    : 
                    ['status'=>0, 'msg'=>"Se generó un problema al crear el trabajo... Intente nuevamente"];
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
        $a = $this->genmod->gettablecol('trabajos', $selColName, $whereColName, $colValue);
        
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
    
    
    public function updateTrabajoHours(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('_tId', 'Trabajo ID', ['required', 'trim', 'numeric'], ['required'=>"required"]);
        $this->form_validation->set_rules('thUpdateTrabajoHours', 'Trabajo hours', ['required', 'trim', 'numeric'], ['required'=>"required"]);

        
        if($this->form_validation->run() !== FALSE){
            //update stock based on the update type

            $trabajoId = set_value('_tId');
            $thUpdateTrabajoHours = set_value('thUpdateTrabajoHours');

            
            $this->db->trans_start();
            
            $updated = $this->trabajo->updateTrabajoHours($trabajoId, $thUpdateTrabajoHours) ;

            
            //add event to log if successful

            $event = "Modificado de horas de trabajo a cumplir";
            

            $eventDesc = "<p>{$thUpdateTrabajoHours} horas de trabajo de {$this->genmod->gettablecol('trabajos', 'name', 'id', $trabajoId)} fueron establecidas</p>";
            
            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $updated ? $this->genmod->addevent($event, $trabajoId, $eventDesc, "trabajos", $this->session->admin_id) : "";
            
            $this->db->trans_complete();//end transaction
            
            $json['status'] = $this->db->trans_status() !== FALSE ? 1 : 0;
            $json['msg'] = $updated ? "La cantidad de horas del trabajo han sido modificadas" : "Se generó un problema al modificar las horas de trabajo ...Intente nuevamente";
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
        
        $this->form_validation->set_rules('_tId', 'Trabajo ID', ['required', 'trim', 'numeric']);
        $this->form_validation->set_rules('trabajoName', 'Trabajo Name', ['required', 'trim',
            'callback_crosscheckName['.$this->input->post('_tId', TRUE).']'], ['required'=>'required']);
        $this->form_validation->set_rules('trabajoDesc', 'Trabajo Description', ['trim']);

        if($this->form_validation->run() !== FALSE){
            $trabajoId = set_value('_tId');
            $trabajoName = set_value('trabajoName');
            $trabajoDesc = set_value('trabajoDesc');

            //update item in db
            $updated = $this->trabajo->edit($trabajoId, $trabajoName, $trabajoDesc);
            $updated2 = $this->asignacion->editByTrabajo($trabajoName,$trabajoId);

            $json['status'] = $updated && $updated2 ? 1 : 0;

            //add event to log
            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $desc = "El trabajo {$trabajoName} fue editado";
            
            $this->genmod->addevent("Edición del trabajo", $trabajoId, $desc, 'trabajos', $this->session->admin_id);
        }
        
        else{
            $json['status'] = 0;
            $json = $this->form_validation->error_array();
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }



    public function assignBecario(){
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('_tId', 'Trabajo ID', ['required', 'trim', 'numeric']);
        $this->form_validation->set_rules('becarioName', 'Becario Name', ['required', 'trim',
                'callback_crosscheckBecarioTrabajo['.$this->input->post('trabajoName', TRUE).']'], ['required'=>'required']);


        if($this->form_validation->run() !== FALSE){
        $becarioName = set_value('becarioName');
         $becarioCode = set_value('becarioCode');
         $trabajoName = set_value('trabajoName');
         $trabajoCode = set_value('_tId');
         $becarioId=set_value('_bId');
         $hoursToAdd=set_value('trabajoHours');
         $hoursDisp=set_value('becHours');

         $this->db->trans_start();//start transaction

          //$insertedId = $this->asignacion->add($becarioName, $becarioCode, $trabajoName, $trabajoCode,$becarioId);

          $hoursToRest = $hoursToAdd>=$hoursDisp ? $hoursDisp : $hoursToAdd;

          $modifiedHours= $this->becario->incrementAssignedHours($becarioCode,$hoursToRest);

          $desc = "Inscripción del becario {$becarioName} de código UPB {$becarioCode} al trabajo {$trabajoName} ";

        //$insertedId ? $this->genmod->addevent("Asignacion de becario a trabajo", $insertedId, $desc, "becarios", $this->session->admin_id) : "";

         $this->db->trans_complete();

         $json = $this->db->trans_status() !== FALSE ?
         ['status'=>1, 'msg'=>"Se agregó el becario al trabajo correctamente"]
         :
         ['status'=>0, 'msg'=>"Se generó un problema al asignar al becario al trabajo...Intente nuevamente"];


         }else{
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
    
    public function crosscheckName($trabajoName, $trabajoId){
        //check db to ensure name was previously used for the item we are updating
        $trabajoConNombre = $this->genmod->getTableCol('trabajos', 'id', 'name', $trabajoName);
        

        if(!$trabajoConNombre || ($trabajoConNombre == $trabajoId)){
            return TRUE;
        }
        
        else{//if it exist
            $this->form_validation->set_message('crosscheckName', 'Ya existe un trabajo registrado con este nombre');
                
            return FALSE;
        }
    }


    public function crosscheckBecarioTrabajo($becarioName,$trabajoName){
            //check db to ensure name was previously used for the item we are updating
            $trabajoConBecario = $this->genmod->getTableColMultiQuery('asignaciones', 'asignId', 'becarioName','trabajo_name',$becarioName, $trabajoName);



            if(!$trabajoConBecario){
                return TRUE;
            }

            else{
                $this->form_validation->set_message('crosscheckBecarioTrabajo', 'El becario ya esta registrado en este trabajo');

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
        $trabajo_id = $this->input->post('t', TRUE);
        $trabajo_name = $this->input->post('tn', TRUE);
        $trabajo_hours= $this->input->post('th', TRUE);


        
        if($trabajo_id && $trabajo_name){

            $check=$this->asignacion->getBecarios(['trabajo_name'=>$trabajo_name], ['becarioId']);

             if($check){
                         foreach($check as $item):
                         $becarioInfo=$this->becario->getBecarioInfo(['id'=>$item->becarioId], ['assignedhours']);
                         if($becarioInfo){
                            foreach($becarioInfo as $item2):
                            if($item2->assignedhours <= $trabajo_hours){
                                $this->becario->decrementAssignedHours($item->becarioId,$item2->assignedhours);
                            } else {
                                $this->becario->decrementAssignedHours($item->becarioId,$trabajo_hours);
                            }


                            endforeach;
                         }



                         endforeach;
             }




            $this->db->delete('trabajos', array('id' => $trabajo_id));
            $this->db->delete('asignaciones', array('trabajo_name' => $trabajo_name));


            
            $json['status'] = 1;
        }
        
        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

        public function report(){
            //get all transactions from db ranging from $from_date to $to_date


        }
}