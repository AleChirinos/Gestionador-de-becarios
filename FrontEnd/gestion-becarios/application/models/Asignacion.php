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
    



    

    public function add($becarioName, $becarioCode, $trabajoName,$trabajoCode,$becarioId,$trabajoHours){


        $data = ['becarioName'=>$becarioName, 'becarioCode'=>$becarioCode, 'becarioId'=>$becarioId, 'trabajo_name'=>$trabajoName,'trabajo_code'=>$trabajoCode, 'hours'=>$trabajoHours,'accomplished'=>0];
                
        //set the datetime based on the db driver in use
        $this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('assignDate', "datetime('now')", FALSE) 
                : 
        $this->db->set('assignDate', "NOW()", FALSE);
        
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

    public function editByBecario($becarioCode,$becarioName,$becarioId){
               $data = ['becarioName'=>$becarioName,'becarioCode'=>$becarioCode];

               $this->db->where('becarioId', $becarioId);
               $this->db->update('asignaciones', $data);

               return TRUE;
           }


    public function editByTrabajoHour($trabajoId,$trabajoHours){
        $data = ['hours'=>$trabajoHours];

        $whereArray = array('trabajo_code' => $trabajoId, 'accomplished' => 0);

           $this->db->where($whereArray);
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

    public function getBecario($where_clause, $fields_to_fetch){
        $this->db->select($fields_to_fetch);

        $this->db->where($where_clause);

        $run_q = $this->db->get('asignaciones');

        return $run_q->num_rows() ? $run_q->row() : FALSE;
    }

    public function becariossearch($value){
        $q = "SELECT * FROM asignaciones WHERE accomplished LIKE 0 AND trabajo_code LIKE '%".$this->db->escape_like_str($value)."%' ";

        $run_q = $this->db->query($q, [$value, $value]);

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
            return FALSE;
        }
    }

    public function updateAsignacion($trabajoId,$becarioName, $hoursAssign){
        $data = ['hours'=>$hoursAssign, 'accomplished'=>1];

        $whereArray = array('trabajo_code' => $trabajoId, 'becarioName' => $becarioName);

        $this->db->where($whereArray);
        $this->db->update('asignaciones', $data);

        return TRUE;
    }






}