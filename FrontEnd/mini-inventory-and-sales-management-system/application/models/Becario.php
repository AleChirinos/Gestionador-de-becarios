<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Customer
 *
 */
class Becario extends CI_Model{
    public function __construct(){
        parent::__construct();
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    public function getAll($orderBy, $orderFormat, $start=0, $limit=''){
        $this->db->limit($limit, $start);
        $this->db->order_by($orderBy, $orderFormat);
        
        $run_q = $this->db->get('becarios');
        
        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
            return FALSE;
        }
    }
    

    public function add($becarioName, $becarioCode){
        $data = ['name'=>$becarioName, 'code'=>$becarioCode, 'totalhours'=>0, 'checkedhours'=>0, 'assignedhours'=>0,'missinghours'=>0];
                
        //set the datetime based on the db driver in use
        $this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('dateAdded', "datetime('now')", FALSE) 
                : 
        $this->db->set('dateAdded', "NOW()", FALSE);
        
        $this->db->insert('becarios', $data);
        
        if($this->db->insert_id()){
            return $this->db->insert_id();
        }
        
        else{
            return FALSE;
        }
    }

    public function becariosearch($value){
        $q = "SELECT * FROM becarios
            WHERE 
            name LIKE '%".$this->db->escape_like_str($value)."%'
            ";
        
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
    
    /**
     * To add to the number of an item in stock
     * @param type $itemId
     * @param type $numberToadd
     * @return boolean
     */

     public function updateTotalHours($becarioId, $totalHours){
             $q = "UPDATE becarios SET totalhours= ?  WHERE id = ?";


             $this->db->query($q, [$totalHours, $becarioId]);



             if($this->db->affected_rows() > 0){
                 return TRUE;
             }

             else{
                 return FALSE;
             }
     }

    public function incrementAssignedHours($becarioId, $numberToadd){
        $q = "UPDATE becarios SET assignedhours= assignedhours + ?  WHERE id = ?";


        $this->db->query($q, [$numberToadd, $becarioId]);



        
        if($this->db->affected_rows() > 0){
            return TRUE;
        }
        
        else{
            return FALSE;
        }
    }

    
    public function decrementAssignedHours($becarioId, $numberToRemove){
        $q = "UPDATE becarios SET assignedhours = assignedhours - ? WHERE id = ?";
        
        $this->db->query($q, [$numberToRemove, $becarioId]);
        
        if($this->db->affected_rows() > 0){
            return TRUE;
        }
        
        else{
            return FALSE;
        }
    }



   public function edit($becarioId,$becarioName, $becarioCode){
       $data = ['name'=>$becarioName, 'code'=>$becarioCode, ];

       $this->db->where('id', $itemId);
       $this->db->update('becarios', $data);
       
       return TRUE;
   }






}