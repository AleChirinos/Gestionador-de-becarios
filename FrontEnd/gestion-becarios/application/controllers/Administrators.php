<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Administrators
 *
 */
class Administrators extends CI_Controller{

    public function __construct(){
        parent::__construct();

        $this->genlib->checkLogin();

        $this->genlib->superOnly();

        $this->load->model(['admin','semester']);
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    public function index(){
        $resData['semesters'] = $this->semester->getAll('name', 'ASC');
        $data['pageContent'] = $this->load->view('admin/admin', $resData, TRUE);
        $data['pageTitle'] = "Administrators";

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
     * laad_ = "Load all administrators"
     */
    public function laad_(){
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "first_name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";
        $gest=$this->input->get('gest',TRUE) ? $this->input->get('gest', TRUE) : "" ;
        $check=false;
        
        if ($gest!=="null"){
            $check=$this->admin->getAll($gest);
            if($check){
                $totalAdministrators = count($this->admin->getAll($gest));
                $updatedSem= $this->admin->updateAdminSemester($gest);
            } 
           
        } else {
            $check=false;
            $totalAdministrators = 0;
        }

        //count the total administrators in db (excluding the currently logged in admin)      
       
        $this->load->library('pagination');

        $pageNumber = $this->uri->segment(3, 0);//set page number to zero if the page number is not set in the third segment of uri

        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10;//show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit;//start from 0 if pageNumber is 0, else start from the next iteration

        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalAdministrators, "administrators/laad_", $limit, ['class'=>'lnp']);

        $this->pagination->initialize($config);//initialize the library class

        //get all customers from db
        
        $data['allAdministrators'] = $gest!=="null" && $check ? $this->admin->getAll($gest,$orderBy, $orderFormat, $start, $limit) : FALSE;
        $data['range'] = $totalAdministrators > 0 ? ($start+1) . "-" . ($start + count($data['allAdministrators'])) . " de " . $totalAdministrators : "";
        $data['links'] = $this->pagination->create_links();//page links
        $data['sn'] = $start+1;

        $json['adminTable'] = $this->load->view('admin/adminlist', $data, TRUE);//get view with populated customers table

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
     * To add new admin
     */
    public function add(){
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('firstName', 'First name', ['required', 'trim', 'max_length[20]', 'strtolower', 'ucfirst'], ['required'=>"required"]);
        $this->form_validation->set_rules('lastName', 'Last name', ['required', 'trim', 'max_length[20]', 'strtolower', 'ucfirst'], ['required'=>"required"]);
        $this->form_validation->set_rules('email', 'E-mail', ['trim', 'required', 'valid_email', 'is_unique[admin.email]', 'strtolower'],
                ['required'=>"required", 'is_unique'=>'E-mail exists']);
        $this->form_validation->set_rules('role', 'Role', ['required'], ['required'=>"required"]);
        $this->form_validation->set_rules('career', 'Carrera', ['required','max_length[50]'], ['required'=>"required"]);
        $this->form_validation->set_rules('semester', 'Semester', ['required'], ['required'=>"required"]);
        if($this->form_validation->run() !== FALSE){
            /**
             * insert info into db
             * function header: add($f_name, $l_name, $email,  $role, $career, $semester)
             */


            $inserted = $this->admin->add(set_value('firstName'), set_value('lastName'), set_value('email'),
                set_value('role'), set_value('career'), set_value('semester')/*, set_value('mobile2')*/);


            $json = $inserted ?
                ['status'=>1, 'msg'=>"Admin account successfully created"]
                :
                ['status'=>0, 'msg'=>"Oops! Unexpected server error! Pls contact administrator for help. Sorry for the embarrassment"];
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
     *
     */
    public function update(){
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('firstName', 'First name', ['required', 'trim', 'max_length[20]'], ['required'=>"required"]);
        $this->form_validation->set_rules('lastName', 'Last name', ['required', 'trim', 'max_length[20]'], ['required'=>"required"]);
        $this->form_validation->set_rules('email', 'E-mail', ['required', 'trim', 'valid_email', 'callback_crosscheckEmail['. $this->input->post('adminId', TRUE).']']);
        $this->form_validation->set_rules('role', 'Role', ['required', 'trim'], ['required'=>"required"]);
        $this->form_validation->set_rules('career', 'Carrera', ['required','max_length[50]'], ['required'=>"required"]);
        $this->form_validation->set_rules('semester', 'Semester', ['required'], ['required'=>"required"]);
        if($this->form_validation->run() !== FALSE){
            /**
             * update info in db
             * function header: update($admin_id, $first_name, $last_name, $email, $role,$career,$semester)
             */

            $admin_id = $this->input->post('adminId', TRUE);

            $updated = $this->admin->update( set_value('firstName'), set_value('lastName'), set_value('email')/*,
                    set_value('mobile1')*/, set_value('role'), set_value('career'),set_value('semester'),$admin_id);


            $json = $updated ?
                    ['status'=>1, 'msg'=>"Admin info successfully updated"]
                    :
                    ['status'=>0, 'msg'=>"Oops! Unexpected server error! Pls contact administrator for help. Sorry for the embarrassment"];
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


    public function suspend(){
        $this->genlib->ajaxOnly();

        $admin_id = $this->input->post('_aId');
        $new_status = $this->genmod->gettablecol('admin', 'account_status', 'id', $admin_id) == 1 ? 0 : 1;

        $done = $this->admin->suspend($admin_id, $new_status);

        $json['status'] = $done ? 1 : 0;
        $json['_ns'] = $new_status;
        $json['_aId'] = $admin_id;

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
        $item_id = $this->input->post('i', TRUE);
        $admin_id = $this->input->post('_aId');
        $new_value = $this->genmod->gettablecol('admin', 'deleted', 'id', $admin_id) == 1 ? 0 : 1;
        $json['_nv'] = $new_value;
        $json['_aId'] = $admin_id;
                if($admin_id){
                    $this->db->delete('admin', array('id' => $admin_id));
                    $json['status'] = 1;
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
     * Used as a callback while updating admin info to ensure 'mobile1' field does not contain a number already used by another admin
     * @param type $mobile_number
     * @param type $admin_id
     */
    /*public function crosscheckMobile($mobile_number, $admin_id){
        //check db to ensure number was previously used for admin with $admin_id i.e. the same admin we're updating his details
        $adminWithNum = $this->genmod->getTableCol('admin', 'id', 'mobile1', $mobile_number);

        if($adminWithNum == $admin_id){
            //used for same admin. All is well.
            return TRUE;
        }

        else{
            $this->form_validation->set_message('crosscheckMobile', 'This number is already attached to an administrator');

            return FALSE;
        }
    }*/

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /**
     * Used as a callback while updating admin info to ensure 'email' field does not contain an email already used by another admin
     * @param type $email
     * @param type $admin_id
     */
    public function crosscheckEmail($email, $admin_id){
        //check db to ensure email was previously used for admin with $admin_id i.e. the same admin we're updating his details
        $adminWithEmail = $this->genmod->getTableCol('admin', 'id', 'email', $email);

        if($adminWithEmail == $admin_id){
            //used for same admin. All is well.
            return TRUE;
        }

        else{
            $this->form_validation->set_message('crosscheckEmail', 'This email is already attached to an administrator');

            return FALSE;
        }
    }




}