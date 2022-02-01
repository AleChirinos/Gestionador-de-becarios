<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Report
 *
 */
class Semester extends CI_Model{
    
    public function __construct(){
        parent::__construct();
    }

    public function getAll($orderBy, $orderFormat, $start=0, $limit=''){
        $this->db->limit($limit, $start);
        $this->db->order_by($orderBy, $orderFormat);


        $run_q = $this->db->get('semesters');

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
            return FALSE;
        }
    }

    public function semestersearch($value){
        $q = "SELECT * FROM semesters WHERE name LIKE '%".$this->db->escape_like_str($value)."%' ";


        $run_q = $this->db->query($q, [$value, $value]);

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
            return FALSE;
        }
    }

    public function getSemesterInfo($where_clause, $fields_to_fetch){
        $this->db->select($fields_to_fetch);

        $this->db->where($where_clause);

        $run_q = $this->db->get('semesters');

        return $run_q->num_rows() ? $run_q->row() : FALSE;
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
    public function changeSemester($admin_id, $semester_id){
        $this->db->where('id', $admin_id);
        $this->db->update('admin', ['semester'=>$semester_id]);

        if($this->db->affected_rows()){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }


}
