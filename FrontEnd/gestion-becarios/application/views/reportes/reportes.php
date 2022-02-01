<?php
defined('BASEPATH') OR exit('');
?>

<div class="pwell hidden-print">   
    <div class="row">
        <div class="col-sm-12">
            <!-- sort and co row-->
            <div class="row">
                <div class="col-sm-12">
            
                    <div class="col-lg-4 form-group-sm form-inline">
                        <label for="groupList">Grupo de obtención de reportes</label>
                        <select  id="groupList" class="form-control">
                            <option value="null">Seleccionar orígen de reporte</option>
                            <option value="becario">Becarios</option>
                            <option value="trabajo">Trabajos</option>       
                        </select>
                    </div>

                    <div class="col-lg-3 form-group-sm form-inline">
                        <label for="gestList">Gestion de busqueda</label>
                        <select id="gestList" class="form-control selectedSemesterDefault" style="width: 50%">
                        <?php
                            if(!is_bool($gestiones)){
                                foreach($gestiones as $row)
                                {  
                                    echo '<option value="'.$row->id.'">'.$row->name.'</option>';
                                }   
                            }
                            
                        ?>
                        </select>
                    </div>

                    <div class="col-lg-5 form-group-sm form-inline">
                        <label for="searchOpt">Buscar información de</label>
                        <select class="form-control selectedCheckDefault" style="width: 50%" id="searchOpt">
                        <option value="null">Seleccionar sujeto</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- end of sort and co div-->
        </div>
    </div>
    
    <hr>
    
    <!-- row of adding new item form and items list table-->
    <div class="row">
        <div class="col-sm-12">
            <!--Form to add/update an item-->
            <div class="col-sm-4 hidden" id='createNewBecarioDiv'>
                <div class="well">
                    <button class="close cancelAddBecario">&times;</button><br>
                    <form name="addNewBecarioForm" id="addNewBecarioForm" role="form">
                        <div class="text-center errMsg" id='addCustErrMsg'></div>
                        
                        <br>
                        
                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="becarioCode">Código UPB del Becario</label>
                                <input type="text" id="becarioCode" name="becarioCode" placeholder="Ej: 51225" maxlength="80"
                                    class="form-control" onchange="checkField(this.value, 'becarioCodeErr')" autofocus>
                                <!--<span class="help-block"><input type="checkbox" id="gen4me"> auto-generate</span>-->
                                <span class="help-block errMsg" id="becarioCodeErr"></span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="becarioName">Nombre y apellido del becario</label>
                                <input type="text" id="becarioName" name="becarioName" placeholder="Ej: Patricio Vargas" maxlength="80"
                                    class="form-control" onchange="checkField(this.value, 'becarioNameErr')">
                                <span class="help-block errMsg" id="becarioNameErr"></span>
                            </div>
                        </div>
                        <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='career' class="control-label">Carrera</label>
                                <input type="hidden" id='career' class="form-control checkField" value="<?php echo $this->session->admin_career; ?>">
                                <br>
                                <label for='career' class="control-label"><?php echo $this->session->admin_career; ?></label>
                            <span class="help-block errMsg" id="careerErr"></span>
                        </div>
                        </div>
                        <br>
                        <div class="row text-center">
                            <div class="col-sm-6 form-group-sm">
                                <button class="btn btn-primary btn-sm" id="addNewBecario">Añadir becario</button>
                            </div>

                            <div class="col-sm-6 form-group-sm">
                                <button type="reset" id="cancelAddBecario" class="btn btn-danger btn-sm cancelAddBecario" form='addNewBecarioForm'>Cancelar</button>
                            </div>
                        </div>
                    </form><!-- end of form-->
                </div>
            </div>
            
            <!--- Item list div-->
            <div class="col-sm-12" id="becariosListDiv">
                <!-- Item list Table-->
                <div class="row">
                    <div class="col-sm-12" id="reportesListTable"></div>
                </div>
                <!--end of table-->
                
            </div>
            <!--- End of item list div-->

        </div>
    </div>
    <!-- End of row of adding new item form and items list table-->
</div>



<div id="updateMissingHoursModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center">Modificar horas no cumplidas de trabajo becario </h4>
                <div id="mhUpdateFMsg" class="text-center"></div>
            </div>
            <div class="modal-body">
                <form name="updateMissingHoursForm" id="updateMissingHoursForm" role="form">
                    <div class="row">
                        <div class="col-sm-4 form-group-sm">
                            <label>Nombre del estudiante</label>
                            <input type="text" readonly id="mhUpdateBecarioName" class="form-control">
                        </div>

                        <div class="col-sm-4 form-group-sm">
                            <label>Código UPB del estudiante</label>
                            <input type="text" readonly id="mhUpdateBecarioCode" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <div class="col-sm-6 form-group-sm">
                            <label for="mhUpdateMissingHours">Horas de trabajo becario faltantes</label>
                            <input type="number" id="mhUpdateMissingHours"
                                class="form-control checkField" min="0">
                            <span class="help-block errMsg" id="mhUpdateMissingHoursErr"></span>
                        </div>
                    </div>
                    <input type="hidden" id="mhUpdateBecarioId">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="mhUpdateSubmit">Modificar horas faltantes</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
    <!-- End of row of adding new item form and items list table-->
</div>



<div id="updateMissingHoursModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center">Modificar horas no cumplidas de trabajo becario </h4>
                <div id="mhUpdateFMsg" class="text-center"></div>
            </div>
            <div class="modal-body">
                <form name="updateMissingHoursForm" id="updateMissingHoursForm" role="form">
                    <div class="row">
                        <div class="col-sm-4 form-group-sm">
                            <label>Nombre del estudiante</label>
                            <input type="text" readonly id="mhUpdateBecarioName" class="form-control">
                        </div>

                        <div class="col-sm-4 form-group-sm">
                            <label>Código UPB del estudiante</label>
                            <input type="text" readonly id="mhUpdateBecarioCode" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <div class="col-sm-6 form-group-sm">
                            <label for="mhUpdateMissingHours">Horas de trabajo becario faltantes</label>
                            <input type="number" id="mhUpdateMissingHours"
                                class="form-control checkField" min="0">
                            <span class="help-block errMsg" id="mhUpdateMissingHoursErr"></span>
                        </div>
                    </div>
                    <input type="hidden" id="mhUpdateBecarioId">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="mhUpdateSubmit">Modificar horas faltantes</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<!--modal to edit item-->
<div id="editBecarioModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center">Editar información del becario</h4>
                <div id="editBecarioFMsg" class="text-center"></div>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="row">
                        <div class="col-sm-4 form-group-sm">
                            <label for="becarioNameEdit">Nombre del becario</label>
                            <input type="text" id="becarioNameEdit" placeholder="Nombre del Becario" autofocus class="form-control checkField">
                            <span class="help-block errMsg" id="becarioNameEditErr"></span>
                        </div>
                        
                        <div class="col-sm-4 form-group-sm">
                            <label for="becarioCode">Código UPB del becario</label>
                            <input type="text" id="becarioCodeEdit" class="form-control">
                            <span class="help-block errMsg" id="becarioCodeEditErr"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='career' class="control-label">Carrera</label>
                                <input type="hidden" id='career' class="form-control checkField" value="<?php echo $this->session->admin_career; ?>">
                                <br>
                                <label for='career' class="control-label"><?php echo $this->session->admin_career; ?></label>
                            <span class="help-block errMsg" id="careerErr"></span>
                        </div>
                        </div>

                    <input type="hidden" id="becarioIdEdit">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="editBecarioSubmit">Guardar informacion</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<!--end of modal-->
<script src="<?=base_url()?>public/js/reportes.js"></script>