<?php
defined('BASEPATH') OR exit('');

$current_items = [];

if(isset($becarios) && !empty($becarios)){
    foreach($becarios as $get){
        $current_items[$get->code] = $get->name;
         echo '<script>';
         echo 'console.log('. json_encode( $get->name ) .')';
         echo '</script>';
    }
}
?>

<script>
    var currentBecarios = <?=json_encode($current_items)?>;
    console.log(currentBecarios);
</script>


<div class="pwell hidden-print">   
    <div class="row">
        <div class="col-sm-12">
            <!-- sort and co row-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2 form-inline form-group-sm">
                        <button class="btn btn-primary btn-sm" id='createTrabajo'>Añadir nuevo trabjo</button>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="trabajosListPerPage">Mostrar</label>
                        <select id="trabajosListPerPage" class="form-control">
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
                        <label for="trabajosListSortBy">Ordenar por</label>
                        <select id="trabajosListSortBy" class="form-control">
                            <option value="name-ASC">Nombre del trabajo (A-Z)</option>
                            <option value="name-DESC">Nombre del trabajo (Z-A)</option>
                            <option value="workhours-DESC">Horas de trabajo (Mayor cantidad primero)</option>
                            <option value="workhours-ASC">Horas de trabajo (Menor cantidad primero)</option>
                        </select>
                    </div>

                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for='trabajoSearch'><i class="fa fa-search"></i></label>
                        <input type="search" id="trabajoSearch" class="form-control" placeholder="Buscar trabajo">
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
            <div class="col-sm-4 hidden" id='createNewTrabajoDiv'>
                <div class="well">
                    <button class="close cancelAddTrabajo">&times;</button><br>
                    <form name="addNewTrabajoForm" id="addNewTrabajoForm" role="form">
                        <div class="text-center errMsg" id='addCustErrMsg'></div>
                        
                        <br>
                        
                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="trabajoName">Nombre del trabajo</label>
                                <input type="text" id="trabajoName" name="trabajoName" placeholder="Ej: Limpiar la mesa" maxlength="80"
                                    class="form-control" onchange="checkField(this.value, 'trabajoNameErr')">
                                <span class="help-block errMsg" id="trabajoNameErr"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="trabajoDescription" class="">Descripción (Opcional)</label>
                                <textarea class="form-control" id="trabajoDescription" name="trabajoDescription" rows='4'
                                    placeholder="Ej: Limpiar cada mesa de la cafetería del campus de Achocalla"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="trabajoHours">Cantidad de horas de trabajo</label>
                                <input type="number" id="trabajoHours" name="trabajoHours" placeholder="Horas de trabajo"
                                    class="form-control" min="0" onchange="checkField(this.value, 'trabajoHoursErr')">
                                <span class="help-block errMsg" id="trabajoHoursErr"></span>
                            </div>
                        </div>

                        <br>
                        <div class="row text-center">
                            <div class="col-sm-6 form-group-sm">
                                <button class="btn btn-primary btn-sm" id="addNewTrabajo">Añadir trabajo</button>
                            </div>

                            <div class="col-sm-6 form-group-sm">
                                <button type="reset" id="cancelAddTrabajo" class="btn btn-danger btn-sm cancelAddTrabajo" form='addNewTrabajoForm'>Cancelar</button>
                            </div>
                        </div>
                    </form><!-- end of form-->
                </div>
            </div>
            
            <!--- Item list div-->
            <div class="col-sm-12" id="trabajosListDiv">
                <!-- Item list Table-->
                <div class="row">
                    <div class="col-sm-12" id="trabajosListTable"></div>
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



<div id="updateTrabajoHoursModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center">Modificar horas del trabajo </h4>
                <div id="thUpdateFMsg" class="text-center"></div>
            </div>
            <div class="modal-body">
                <form name="updateTrabajoHoursForm" id="updateTrabajoHoursForm" role="form">
                    <div class="row">
                        <div class="col-sm-4 form-group-sm">
                            <label>Nombre del trabajo</label>
                            <input type="text" readonly id="thUpdateTrabajoName" class="form-control">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6 form-group-sm">
                            <label for="thUpdateTrabajoHours">Horas de trabajo</label>
                            <input type="number" id="thUpdateTrabajoHours"
                                class="form-control checkField" min="0">
                            <span class="help-block errMsg" id="thUpdateTrabajoHoursErr"></span>
                        </div>
                    </div>

                    <input type="hidden" id="thUpdateTrabajoId">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="thUpdateSubmit">Modificar horas de trabajo</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<!--modal to edit item-->
<div id="editTrabajoModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center">Editar información del trabajo</h4>
                <div id="editTrabajoFMsg" class="text-center"></div>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="row">
                        <div class="col-sm-4 form-group-sm">
                            <label for="trabajoNameEdit">Nombre del trabajo</label>
                            <input type="text" id="trabajoNameEdit" placeholder="Nombre del trabajo" autofocus class="form-control checkField">
                            <span class="help-block errMsg" id="trabajoNameEditErr"></span>
                        </div>
                        
                        
                        <div class="col-sm-12 form-group-sm">
                            <label for="trabajoDescriptionEdit" class="">Descripción (Opcional)</label>
                            <textarea class="form-control" id="trabajoDescriptionEdit" placeholder="Descripción opcional del trabajo"></textarea>
                        </div>
    
                    </div>

                    <input type="hidden" id="trabajoIdEdit">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="editTrabajoSubmit">Guardar informacion</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div id="checkTrabajoModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center">Marcar trabajo como cumplido</h4>
                <div id="checkTrabajoFMsg" class="text-center"></div>
            </div>
            <div class="modal-body">
               <!-- <form role="form">
                    <div class="row">
                        <div class="col-sm-4 form-group-sm">
                            <label for="checkNameEdit">Nombre del trabajo</label>
                            <input type="text" id="trabajoNameEdit" placeholder="Nombre del trabajo" autofocus class="form-control checkField">
                            <span class="help-block errMsg" id="trabajoNameEditErr"></span>
                        </div>
                        
                        
                        <div class="col-sm-12 form-group-sm">
                            <label for="trabajoDescriptionEdit" class="">Descripción (Opcional)</label>
                            <textarea class="form-control" id="trabajoDescriptionEdit" placeholder="Descripción opcional del trabajo"></textarea>
                        </div>
    
                    </div>

                    <input type="hidden" id="trabajoIdEdit">
                </form> -->
                
            </div>
            <div class="modal-footer">
                    
            <button class="btn btn-primary" id="checkTrabajoSubmit">Finalizar trabajo</button> 
            <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>



<!--modal to edit item-->
<div id="addBecarioTrabajoModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center">Asignar becario al trabajo</h4>
                <div id="becTrabajoFMsg" class="text-center"></div>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="row">


                     <div class="col-sm-4 form-group-sm">
                            <label for="selectedBecarioDefault">Becario</label>
                            <select class="form-control" id="selectedBecarioDefault" onchange="selectedBecario(this)">
                            <option selected>Selecciona a tu becario:</option>
                             <?php

                                foreach($becarios as $row)
                                        {
                                          echo '<option value="'.$row->code.'">'.$row->name.'</option>';
                                 }
                              ?>
                            </select>
                             <span class="help-block errMsg" id="selectedBecarioDefaultErr"></span>
                        </div>

                        <div class="col-sm-4 form-group-sm">
                            <label for="becarioAssignHours">Horas del trabajo</label>
                            <input type="number" readonly id="becarioAssignHours"
                            class="form-control checkField" min="0">
                            <span class="help-block errMsg" id="becarioAssignHoursErr"></span>
                        </div>

                        <div class="col-sm-4 form-group-sm">
                            <label for="becarioDisHours">Horas faltantes (becario) </label>
                            <input type="number" readonly id="becarioDisHours"
                            class="form-control checkField" min="0">
                            <span class="help-block errMsg" id="becarioDisHoursErr"></span>
                        </div>
                     </div>
                     <input type="hidden" id="trabajoBecName">
                    <input type="hidden" id="trabajoNameBec">
                    <input type="hidden" id="trabajoIdBec">
                    <input type="hidden" id="becId">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="assignBecarioSubmit">Guardar informacion</button>
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
<script src="<?=base_url()?>public/js/trabajos.js"></script>