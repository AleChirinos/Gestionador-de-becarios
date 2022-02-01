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


    public function add($becarioName, $becarioCode, $career, $semester){
        $data = ['name'=>$becarioName, 'code'=>$becarioCode,
            'career'=>$career, 'semester'=>$semester, 'totalhours'=>0, 'checkedhours'=>0, 'assignedhours'=>0,'missinghours'=>0];

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
        $q = "SELECT * FROM becarios WHERE name LIKE '%".$this->db->escape_like_str($value)."%' ";


        $run_q = $this->db->query($q, [$value, $value]);

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
            return FALSE;
        }
    }

    public function becarioSemsearch($value,$career){
        $q = "SELECT * FROM becarios WHERE semester= ? AND career=?";


        $run_q = $this->db->query($q, [$value, $career]);

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
            return FALSE;
        }
    }

    public function getBecarioInfo($where_clause, $fields_to_fetch){
        $this->db->select($fields_to_fetch);

        $this->db->where($where_clause);

        $run_q = $this->db->get('becarios');

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
     * To add to the number of an item in stock
     * @param type $itemId
     * @param type $numberToadd
     * @return boolean
     */

    public function updateMissingHours($becarioId, $missingHours){
        $q = "UPDATE becarios SET missinghours= ?, totalhours=missinghours+assignedhours+checkedhours WHERE id = ?";


        $this->db->query($q, [$missingHours, $becarioId]);



        if($this->db->affected_rows() > 0){
            return TRUE;
        }

        else{
            return FALSE;
        }
    }

    public function incrementAssignedHours($becarioCode, $numberToadd,$numberDisp){
        $numberRem=$numberDisp<=$numberToadd ? $numberDisp : $numberToadd ;



        $q = "UPDATE becarios SET assignedhours= assignedhours + ?, missinghours=missinghours - ?, totalhours=missinghours+assignedhours+checkedhours WHERE code = ?";
        $this->db->query($q, [$numberToadd, $numberRem, $becarioCode]);






        if($this->db->affected_rows() > 0){
            return TRUE;
        }

        else{
            return FALSE;
        }
    }


    public function decrementAssignedHours($becarioId, $numberToRemove,$numberassigned){
        $numberRem2= $numberassigned<=$numberToRemove ? $numberassigned : $numberToRemove;
        $q = "UPDATE becarios SET assignedhours = assignedhours - ?, missinghours=missinghours + ?, totalhours=missinghours+assignedhours+checkedhours WHERE id = ?";
        $this->db->query($q, [$numberRem2,$numberToRemove, $becarioId]);




        if($this->db->affected_rows() > 0){
            return TRUE;
        }

        else{
            return FALSE;
        }
    }

    public function actualizeAssignedHours($becarioCode){
        $q="UPDATE becarios,asignaciones SET becarios.assignedhours= (SELECT SUM(asignaciones.hours) FROM asignaciones WHERE becarios.code=asignaciones.becarioCode AND asignaciones.accomplished=0), becarios.totalhours=becarios.assignedhours+becarios.checkedhours+becarios.missinghours WHERE becarios.code = ? ";
        $this->db->query($q,[$becarioCode]);

        if($this->db->affected_rows() > 0){
            return TRUE;
        }

        else{
            return FALSE;
        }
    }

    public function actualizeAccomplishedHours($becarioCode){
        $q="UPDATE becarios,asignaciones SET becarios.checkedhours= (SELECT SUM(asignaciones.hours) FROM asignaciones WHERE becarios.code=asignaciones.becarioCode AND asignaciones.accomplished=1), becarios.totalhours=becarios.assignedhours+becarios.checkedhours+becarios.missinghours WHERE becarios.code = ?";
        $this->db->query($q,[$becarioCode]);

        if($this->db->affected_rows() > 0){
            return TRUE;
        }

        else{
            return FALSE;
        }
    }


    public function decrementMissingHours($becarioCode, $numberToadd,$numberDisp){
        $numberRem=$numberDisp<=$numberToadd ? $numberDisp : $numberToadd ;



        $q = "UPDATE becarios SET missinghours=missinghours - ?, totalhours=missinghours+assignedhours+checkedhours WHERE code = ?";
        $this->db->query($q, [$numberRem, $becarioCode]);



        if($this->db->affected_rows() > 0){
            return TRUE;
        }

        else{
            return FALSE;
        }
    }


    public function incrementMissingHours($becarioId, $numberToRemove){

        $q = "UPDATE becarios SET  missinghours=missinghours + ?, totalhours=missinghours+assignedhours+checkedhours WHERE id = ?";
        $this->db->query($q, [$numberToRemove, $becarioId]);

        if($this->db->affected_rows() > 0){
            return TRUE;
        }

        else{
            return FALSE;
        }
    }

    public function edit($becarioId,$becarioName, $becarioCode){
        $data = ['name'=>$becarioName, 'code'=>$becarioCode ];

        $this->db->where('id', $becarioId);
        $this->db->update('becarios', $data);

        return TRUE;
    }

    public function getBySemester($semester){
        $this->db->limit('', 0);
        $this->db->order_by('name', 'ASC');
        $this->db->where('semester', $semester);



        $run_q = $this->db->get('becarios');

        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
            return FALSE;
        }
    }


    public function getReport($value,$semester){

        $q="SELECT becarios.* , asignaciones.trabajo_name, asignaciones.accomplished, asignaciones.hours, asignaciones.assignDate FROM becarios INNER JOIN asignaciones ON becarios.id = asignaciones.becarioId  WHERE becarios.semester= ? AND becarios.id= ?";


        $run_q = $this->db->query($q, [$semester, $value]);



        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
            return FALSE;
        }
    }

    public function getBecarioReportById($value){

        $q="SELECT becarios.* , asignaciones.trabajo_name, asignaciones.accomplished, asignaciones.hours, asignaciones.assignDate FROM becarios INNER JOIN asignaciones ON becarios.id = asignaciones.becarioId  WHERE becarios.id= ? ";


        $run_q = $this->db->query($q, [$value]);



        if($run_q->num_rows() > 0){
            return $run_q->result();
        }

        else{
            return FALSE;
        }
    }







}