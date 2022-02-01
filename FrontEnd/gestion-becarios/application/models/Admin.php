<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Admin
 *
 */
class Admin extends CI_Model{
    public function __construct(){
        parent::__construct();
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
     * @param type $f_name
     * @param type $l_name
     * @param type $email
     * @param type $role
     * @param type $career
     * @param type $semester
     * @return boolean
     */
    public function add($f_name, $l_name, $email, $role, $career, $semester){
        $data = ['first_name'=>$f_name, 'last_name'=>$l_name, 'email'=>$email, 'role'=>$role,
            'career'=>$career, 'semester'=>$semester];

        //set the datetime based on the db driver in use
        $this->db->platform() == "sqlite3"
            ?
            $this->db->set('created_on', "datetime('now')", FALSE)
            :
            $this->db->set('created_on', "NOW()", FALSE);

        $this->db->insert('admin', $data);

        if($this->db->affected_rows() > 0){
            return $this->db->insert_id();
        }

        else{
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
     * @param type $m_name
     * @return boolean
     */
    public function addManagement($m_name){
        $data = ['m_name'=>$m_name];
        //set the datetime based on the db driver in use
        $this->db->platform() == "sqlite3"
            ?
            $this->db->set('created_on', "datetime('now')", FALSE)
            :
            $this->db->set('created_on', "NOW()", FALSE);
        $this->db->insert('management', $data);
        if($this->db->affected_rows() > 0){
            return $this->db->insert_id();
        }
        else{
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
     * @param type $admin_id
     * @return boolean
     */
    public function update_last_login($admin_id){
        $this->db->where('id', $admin_id);

        //set the datetime based on the db driver in use
        $this->db->platform() == "sqlite3"
            ?
            $this->db->set('last_login', "datetime('now')", FALSE)
            :
            $this->db->set('last_login', "NOW()", FALSE);

        $this->db->update('admin');

        if(!$this->db->error()){
            return TRUE;
        }

        else{
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
     * Get some details about an admin (stored in session)
     * @param type $email
     * @return boolean
     */
    public function get_admin_info($email){
        $this->db->select('id, first_name, last_name, role, career,semester');
        $this->db->where('email', $email);

        $run_q = $this->db->get('admin');

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
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
     * @param type $orderBy
     * @param type $orderFormat
     * @param type $start
     * @param type $limit
     * @return boolean
     */
    public function getAll($orderBy = "first_name", $orderFormat = "ASC", $start = 0, $limit = ""){
        $this->db->select('id, first_name, last_name, email, role, created_on, last_login, account_status, deleted, career,semester');

        $this->db->where("id != ", $_SESSION['admin_id']);

        $this->db->where("email != ", "demo@1410inc.xyz");//added to prevent people from removing the demo admin account
        $this->db->limit($limit, $start);
        $this->db->order_by($orderBy, $orderFormat);

        $run_q = $this->db->get('admin');

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
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
     * @param type $admin_id
     * @param type $new_status New account status
     * @return boolean
     */
    public function suspend($admin_id, $new_status){
        $this->db->where('id', $admin_id);
        $this->db->update('admin', ['account_status'=>$new_status]);

        if($this->db->affected_rows()){
            return TRUE;
        }

        else{
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
     * @param type $admin_id
     * @param type $new_value
     * @return boolean
     */
    public function delete($admin_id, $new_value){
        $this->db->where('id', $admin_id);
        $this->db->update('admin', ['deleted'=>$new_value]);

        if($this->db->affected_rows()){
            return TRUE;
        }

        else{
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
     * @param type $value
     * @return boolean
     */
    public function adminSearch($value){
        $q = "SELECT * FROM admin WHERE 
                id != {$_SESSION['admin_id']}
                    AND
                (
                MATCH(first_name) AGAINST(?)
                || MATCH(last_name) AGAINST(?)
                || MATCH(first_name, last_name) AGAINST(?)
                || MATCH(email) AGAINST(?)
                || first_name LIKE '%".$this->db->escape_like_str($value)."%'
                || last_name LIKE '%".$this->db->escape_like_str($value)."%' 
                || email LIKE '%".$this->db->escape_like_str($value)."%'
                || career LIKE '%".$this->db->escape_like_str($value)."%'
                || semester LIKE '%".$this->db->escape_like_str($value)."%'
                )";

        $run_q = $this->db->query($q, [$value, $value, $value, $value, $value, $value, $value, $value, $value]);

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
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

    public function update($first_name, $last_name, $email,/*$mobile1, $mobile2,*/ $role, $career ,$semester,$admin_id ){
        $data = ['first_name'=>$first_name, 'last_name'=>$last_name, /*'mobile1'=>$mobile1, 'mobile2'=>$mobile2,*/ 'email'=>$email,
            'role'=>$role,'career'=>$career, 'semester'=>$semester];

        $this->db->where('id', $admin_id);

        $this->db->update('admin', $data);

        return TRUE;
    }

    public function updateAdminSemester($semester){
        $data = ['semester'=>$semester];
        $this->db->where('id',$this->session->admin_id);
        $this->db->update('admin', $data);
        return TRUE;
    }




}