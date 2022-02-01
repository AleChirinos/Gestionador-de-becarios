<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Home
 *
 */
class Home extends CI_Controller {

    public function __construct(){
        parent::__construct();
        
        if(!empty($_SESSION['admin_id'])){
            redirect('dashboard');
        }
    }
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    public function index() {
        $this->output->set_header('Access-Control-Allow-Origin: *');
        $this->load->view('home');
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
    public function login(){
        $this->genlib->ajaxOnly();
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('email', 'E-mail', ['required', 'trim', 'valid_email']);
        
        if($this->form_validation->run() !== FALSE){
            $givenEmail = strtolower(set_value('email'));

            $account_status = $this->genmod->getTableCol('admin', 'account_status', 'email', $givenEmail);
            $deleted = $this->genmod->getTableCol('admin', 'deleted', 'email', $givenEmail);

            //allow log in if email matches and admin's account has not been suspended or deleted
            if($account_status != 0 && $deleted != 1){
                $this->load->model('admin');
                
                //set session details
                $admin_info = $this->admin->get_admin_info($givenEmail);
                
                if($admin_info){
                    foreach($admin_info as $get){
                        $admin_id = $get->id;
                        
                        $_SESSION['admin_id'] = $admin_id;
                        $_SESSION['admin_semester'] = $get->semester;
                        $_SESSION['admin_email'] = $givenEmail;
                        $_SESSION['admin_role'] = $get->role;
                        $_SESSION['admin_career'] = $get->career;
                        $_SESSION['admin_initial'] = strtoupper(substr($get->first_name, 0, 1));
                        $_SESSION['admin_name'] = $get->first_name . " " . $get->last_name;
                    }
                    
                    //update user's last log in time
                    $this->admin->update_last_login($admin_id);
                }
                
                $json['status'] = 1;//set status to return
            }
            
            else{//if  is not correct
                $json['msg'] = "Combinación de email incorrecta";
                $json['status'] = 0;
            }
        }
        
        else{//if form validation fails            
            $json['msg'] = "Uno o más campos han sido incorrectamente llenados o vacíos";
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
}