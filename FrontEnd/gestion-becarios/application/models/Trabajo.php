<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Customer
 *
 */
class Trabajo extends CI_Model{
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
        
        $run_q = $this->db->get('trabajos');
        
        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
            return FALSE;
        }
    }
    

    public function add($trabajoName, $trabajoDesc, $trabajoHours){
        $data = ['name'=>$trabajoName, 'description'=>$trabajoDesc, 'workhours'=>$trabajoHours];
                
        //set the datetime based on the db driver in use
        $this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('dateAdded', "datetime('now')", FALSE) 
                : 
        $this->db->set('dateAdded', "NOW()", FALSE);
        
        $this->db->insert('trabajos', $data);
        
        if($this->db->insert_id()){
            return $this->db->insert_id();
        }
        
        else{
            return FALSE;
        }
    }

    public function trabajosearch($value){
        $q = "SELECT * FROM trabajos WHERE name LIKE '%".$this->db->escape_like_str($value)."%' ";

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

     public function updateTrabajoHours($trabajoId, $trabajoHours){
             $q = "UPDATE trabajos SET workhours= ? WHERE id = ?";


             $this->db->query($q, [$trabajoHours,$trabajoId]);

             if($this->db->affected_rows() > 0){
                 return TRUE;
             }

             else{
                 return FALSE;
             }
     }

   public function edit($trabajoId, $trabajoName, $trabajoDesc){
       $data = ['name'=>$trabajoName, 'description'=>$trabajoDesc ];

       $this->db->where('id', $trabajoId);
       $this->db->update('trabajos', $data);
       
       return TRUE;
   }

}