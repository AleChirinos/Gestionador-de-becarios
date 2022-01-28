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
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    
    
}
