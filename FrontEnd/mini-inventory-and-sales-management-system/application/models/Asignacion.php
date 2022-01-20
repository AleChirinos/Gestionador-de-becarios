<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Customer
 *
 */
class Asignacion extends CI_Model{
    public function __construct(){
        parent::__construct();
    }


    public function getAll($orderBy, $orderFormat){

            $this->db->order_by($orderBy, $orderFormat);


            $run_q = $this->db->get('asignaciones');

            if($run_q->num_rows() > 0){
                return $run_q->result();
            }

            else{
                return FALSE;
            }
        }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    



    

    public function add($becarioName, $becarioCode, $trabajoName,$trabajoCode,$becarioId){


        $data = ['becarioName'=>$becarioName, 'becarioCode'=>$becarioCode, 'becarioId'=>$becarioId, 'trabajo_name'=>$trabajoName,'trabajo_code'=>$trabajoCode,'accomplished'=>0];
                
        //set the datetime based on the db driver in use
        $this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('dateAdded', "datetime('now')", FALSE) 
                : 
        $this->db->set('dateAdded', "NOW()", FALSE);
        
        $this->db->insert('asignaciones', $data);
        
        if($this->db->insert_id()){
            return $this->db->insert_id();
        }
        
        else{
            return FALSE;
        }
    }


    public function editByTrabajo($trabajoName,$trabajoId){
           $data = ['trabajo_name'=>$trabajoName];

           $this->db->where('trabajo_code', $trabajoId);
           $this->db->update('asignaciones', $data);

           return TRUE;
       }

    public function editByBecario($becarioName,$becarioCode,$becarioId){
               $data = ['becarioName'=>$becarioCode,'becarioCode'=>$becarioCode];

               $this->db->where('becarioId', $becarioId);
               $this->db->update('asignaciones', $data);

               return TRUE;
           }

    public function getBecarios($where_clause, $fields_to_fetch){
                $this->db->select($fields_to_fetch);

                $this->db->where($where_clause);

                $run_q = $this->db->get('asignaciones');

                if($run_q->num_rows() > 0){
                            return $run_q->result();
                        }

                else{
                            return FALSE;
                }
            }




}