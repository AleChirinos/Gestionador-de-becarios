<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Items
 *
 */



class Semesters extends CI_Controller{

    public function __construct(){
        parent::__construct();

        $this->genlib->checkLogin();

        $this->genlib->superOnly();

        $this->load->model(['item']);
    }

    /**
     *
     */
    public function index(){
        $data['pageContent'] = $this->load->view('semesters/semesters', '', TRUE);
        $data['pageTitle'] = "Semesters";

        $this->load->view('main', $data);
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /**
     * "lilt" = "load Items List Table"
     */
    public function lilt(){
        $this->genlib->ajaxOnly();

        $this->load->helper('text');

        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";

        //count the total number of semesters in db
        $totalItems = $this->db->count_all('semesters');

        $start=0;

        //get all semesters from db
        $data['allItems'] = $this->item->getAll($orderBy, $orderFormat, $start, "");
        $data['range'] = $totalItems > 0 ? "Mostrando " . ($start+1) . "-" . ($start + count($data['allItems'])) . " de " . $totalItems : "";
        $data['sn'] = $start+1;

        $json['itemsListTable'] = $this->load->view('semesters/itemslisttable', $data, TRUE);//get view with populated semesters table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */



    public function add(){
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('itemName', 'Item name', ['required', 'trim', 'max_length[80]'],
                ['required'=>"required"]);
        $this->form_validation->set_rules('itemCode', 'Item Code', ['required', 'trim', 'max_length[20]'],
                ['required'=>"required"]);

        if($this->form_validation->run() !== FALSE){
            $this->db->trans_start();//start transaction

            /**
             * insert info into db
             * function header: add($itemName,  $itemCode)
             */
            $insertedId = $this->item->add(set_value('itemName'),  set_value('itemCode'));

            $itemName = set_value('itemName');


            //insert into eventlog
            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $desc = "Addition of  quantities of a new item '{$itemName}' with a unit price of  to stock";

            $insertedId ? $this->genmod->addevent("Creation of new item", $insertedId, $desc, "semesters", $this->session->admin_id) : "";

            $this->db->trans_complete();

            $json = $this->db->trans_status() !== FALSE ?
                    ['status'=>1, 'msg'=>"Item successfully added"]
                    :
                    ['status'=>0, 'msg'=>"Oops! Unexpected server error! Please contact administrator for help. Sorry for the embarrassment"];
        }

        else{
            //return all error messages
            $json = $this->form_validation->error_array();//get an array of all errors

            $json['msg'] = "One or more required fields are empty or not correctly filled";
            $json['status'] = 0;
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


    /**
     * Primarily used to check whether an item already has a particular random career being generated for a new item
     * @param type $selColName
     * @param type $whereColName
     * @param type $colValue
     */
    public function gettablecol($selColName, $whereColName, $colValue){
        $a = $this->genmod->gettablecol('semesters', $selColName, $whereColName, $colValue);

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
    public function gcoandqty(){
        $json['status'] = 0;

        $itemCode = $this->input->get('_iC', TRUE);

        if($itemCode){
            $item_info = $this->item->getItemInfo(['career'=>$itemCode], ['quantity', 'unitPrice']);

            if($item_info){
                $json['availQty'] = (int)$item_info->quantity;
                $json['unitPrice'] = $item_info->unitPrice;
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


    public function updatestock(){
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('_iId', 'Item ID', ['required', 'trim', 'numeric'], ['required'=>"required"]);
        $this->form_validation->set_rules('_upType', 'Update type', ['required', 'trim', 'in_list[newStock,deficit]'], ['required'=>"required"]);
        $this->form_validation->set_rules('qty', 'Quantity', ['required', 'trim', 'numeric'], ['required'=>"required"]);

        if($this->form_validation->run() !== FALSE){
            //update stock based on the update type
            $updateType = set_value('_upType');
            $itemId = set_value('_iId');
            $qty = set_value('qty');

            $this->db->trans_start();

            $updated = $updateType === "deficit"
                    ?
                $this->item->deficit($itemId, $qty)
                    :
                $this->item->newstock($itemId, $qty);

            //add event to log if successful
            $stockUpdateType = $updateType === "deficit" ? "Deficit" : "New Stock";

            $event = "Stock Update ($stockUpdateType)";

            $action = $updateType === "deficit" ? "removed from" : "added to";//action that happened

            $eventDesc = "<p>{$qty} quantities of {$this->genmod->gettablecol('semesters', 'name', 'id', $itemId)} was {$action} stock</p>
                Reason: <p>{}</p>";

            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $updated ? $this->genmod->addevent($event, $itemId, $eventDesc, "semesters", $this->session->admin_id) : "";

            $this->db->trans_complete();//end transaction

            $json['status'] = $this->db->trans_status() !== FALSE ? 1 : 0;
            $json['msg'] = $updated ? "Stock successfully updated" : "Unable to update stock at this time. Please try again later";
        }

        else{
            $json['status'] = 0;
            $json['msg'] = "One or more required fields are empty or not correctly filled";
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

        $this->form_validation->set_rules('_iId', 'Item ID', ['required', 'trim', 'numeric']);
        $this->form_validation->set_rules('itemName', 'Item Name', ['required', 'trim',
            'callback_crosscheckName['.$this->input->post('_iId', TRUE).']'], ['required'=>'required']);
        $this->form_validation->set_rules('itemCode', 'Item Code', ['required', 'trim',
            'callback_crosscheckCode['.$this->input->post('_iId', TRUE).']'], ['required'=>'required']);
        $this->form_validation->set_rules('itemPrice', 'Item Unit Price', ['required', 'trim', 'numeric']);


        if($this->form_validation->run() !== FALSE){
            $itemId = set_value('_iId');

            $itemPrice = set_value('itemPrice');
            $itemName = set_value('itemName');
            $itemCode = $this->input->post('itemCode', TRUE);

            //update item in db
            $updated = $this->item->edit($itemId, $itemName,  $itemPrice);

            $json['status'] = $updated ? 1 : 0;

            //add event to log
            //function header: addevent($event, $eventRowId, $eventDesc, $eventTable, $staffId)
            $desc = "Details of item with career '$itemCode' was updated";

            $this->genmod->addevent("Item Update", $itemId, $desc, 'semesters', $this->session->admin_id);
        }

        else{
            $json['status'] = 0;
            $json = $this->form_validation->error_array();
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    public function selectSession(){
        $this->genlib->ajaxOnly();

        $admin_id = $this->session->admin_id;
        echo(['$admin_id']);
        $semester_id = $this->input->post('_aId');
        if($this->session->admin_semester === $semester_id){
            $new_status = 1;
        } else{
            $new_status = 0;
        }

        $done = $this->item->suspend($semester_id, $admin_id);

        $json['status'] = $done ? 1 : 0;
        $json['_ns'] = $new_status;
        $json['_aId'] = $semester_id;

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

   /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    public function crosscheckName($itemName, $itemId){
        //check db to ensure name was previously used for the item we are updating
        $itemWithName = $this->genmod->getTableCol('semesters', 'id', 'name', $itemName);

        //if item name does not exist or it exist but it's the name of current item
        if(!$itemWithName || ($itemWithName == $itemId)){
            return TRUE;
        }

        else{//if it exist
            $this->form_validation->set_message('crosscheckName', 'There is an item with this name');

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
    public function crosscheckCode($item_code, $item_id){
        //check db to ensure item career was previously used for the item we are updating
        $item_with_code = $this->genmod->getTableCol('semesters', 'id', 'career', $item_code);

        //if item career does not exist or it exist but it's the career of current item
        if(!$item_with_code || ($item_with_code == $item_id)){
            return TRUE;
        }

        else{//if it exist
            $this->form_validation->set_message('crosscheckCode', 'There is an item with this career');

            return FALSE;
        }
    }

    public function changeSemester(){
        $this->genlib->ajaxOnly();

        $admin_id = $this->input->post('_aId');
        $semester_id = $this->input->post('_sId');
        $semester_selected = $this->input->post('_sSlct');
        $semester_career= $this->input->post('_sCrr');
        $done = $this->item->changeSemester($admin_id, $semester_id, $semester_career);

        $json['status'] = $done ? 1 : 0;
        $json['_ns'] = 1;

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
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
        $item_id = $this->input->post('i', TRUE);

        if($item_id){
            $this->db->where('id', $item_id)->delete('semesters');

            $json['status'] = 1;
        }

        //set final output
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

        public function report(){
            //get all transactions from db ranging from $from_date to $to_date


        }
}