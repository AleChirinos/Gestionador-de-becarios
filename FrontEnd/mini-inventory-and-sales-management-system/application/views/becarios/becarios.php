<?php
defined('BASEPATH') OR exit('');
?>

<div class="pwell hidden-print">   
    <div class="row">
        <div class="col-sm-12">
            <!-- sort and co row-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2 form-inline form-group-sm">
                        <button class="btn btn-primary btn-sm" id='createBecario'>Añadir nuevo becario</button>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="becariosListPerPage">Mostrar</label>
                        <select id="becariosListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label>por hoja</label>
                        <br><br><br>

                    </div>

                    <div class="col-sm-4 form-group-sm form-inline">
                        <label for="becariosListSortBy">Ordenar por</label>
                        <select id="becariosListSortBy" class="form-control">
                            <option value="name-ASC">Nombre del becario (A-Z)</option>
                            <option value="name-DESC">Nombre del becario (Z-A)</option>
                            <option value="code-ASC">Código del becario (Ascendente)</option>
                            <option value="code-DESC">Código del becario (Descendente)</option>
                            <option value="totalhours-DESC">Horas de trabajo Totales (Mayor cantidad primero)</option>
                            <option value="totalhours-ASC">Horas de trabajo Totales (Menor cantidad primero)</option>
                            <option value="checkedhours-DESC">Horas de trabajo Cumplidas (Mayor cantidad primero)</option>
                            <option value="checkedhours-ASC">Horas de trabajo Cumplidas (Menor cantidad primero)</option>
                            <option value="assignedhours-DESC">Horas de trabajo Asignadas (Mayor cantidad primero)</option>
                            <option value="assignedhours-ASC">Horas de trabajo Asignadas (Menor cantidad primero)</option>
                            <option value="missinghours-DESC">Horas de trabajo Faltantes (Mayor cantidad primero)</option>
                            <option value="missinghours-ASC">Horas de trabajo Faltantes (Menor cantidad primero)</option>
                        </select>
                    </div>

                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for='becarioSearch'><i class="fa fa-search"></i></label>
                        <input type="search" id="becarioSearch" class="form-control" placeholder="Buscar becarios">
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
                    <div class="col-sm-12" id="becariosListTable"></div>
                </div>
                <!--end of table-->
                <div class="col-sm-2  form-group-sm">
                   <span class="pointer text-primary">
                       <button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#reportIt'>
                           <i class="fa fa-newspaper-o"></i> Generar Reporte
                       </button>
                   </span>
                </div>
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

<div class="modal fade" id='reportIt' data-backdrop='static' role='dialog'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="close" data-dismiss='modal'>&times;</div>
                <h4 class="text-center">Generar Reporte</h4>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" id='clickToGen'>Generar</button>
                <button class="btn btn-danger" data-dismiss='modal'>Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--end of modal-->
<script src="<?=base_url()?>public/js/becarios.js"></script>