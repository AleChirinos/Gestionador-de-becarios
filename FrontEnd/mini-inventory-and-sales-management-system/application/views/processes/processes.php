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
                        <button class="btn btn-primary btn-sm" id='createProcess'>Añadir Nuevo Proceso</button>
                    </div>

                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="processesListPerPage">Mostrar</label>
                        <select id="processesListPerPage" class="form-control">
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
                    </div>

                    <div class="col-sm-4 form-group-sm form-inline">
                        <label for="processesListSortBy">Ordenar por</label>
                        <select id="processesListSortBy" class="form-control">
                            <option value="name-ASC">Nombre del proceso (A-Z)</option>
                            <option value="code-ASC">Código del proceso (Ascendente)</option>
                            <option value="unitPrice-DESC">Precio por lote (El más alto primero)</option>
                            <option value="quantity-DESC">Cantidad (El más alto primero)</option>
                            <option value="name-DESC">Nombre del proceso (Z-A)</option>
                            <option value="code-DESC">Código del proceso (Descendente)</option>
                            <option value="unitPrice-ASC">Precio por lote (El mas bajo primero)</option>
                            <option value="quantity-ASC">Cantidad (El mas bajo primero)</option>
                        </select>
                    </div>

                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for='processSearch'><i class="fa fa-search"></i></label>
                        <input type="search" id="processSearch" class="form-control" placeholder="Buscar procesos">
                    </div>
                </div>
            </div>
            <!-- end of sort and co div-->
        </div>
    </div>

    <hr>

    <!-- row of adding new process form and processes list table-->
    <div class="row">
        <div class="col-sm-12">
            <!--Form to add/update an process-->
            <div class="col-sm-4 hidden" id='createNewProcessDiv'>
                <div class="well">
                    <button class="close cancelAddProcess">&times;</button><br>
                    <form name="addNewProcessForm" id="addNewProcessForm" role="form">
                        <div class="text-center errMsg" id='addCustErrMsg'></div>

                        <br>

                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="processCode">Código del Proceso</label>
                                <input type="text" id="processCode" name="processCode" placeholder="Código del proceso" maxlength="80"
                                       class="form-control" onchange="checkField(this.value, 'processCodeErr')" autofocus>
                                <!--<span class="help-block"><input type="checkbox" id="gen4me"> auto-generate</span>-->
                                <span class="help-block errMsg" id="processCodeErr"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="processName">Tipo de Proceso</label>
                                <input type="text" id="processName" name="processName" placeholder="Tipo del proceso" maxlength="80"
                                       class="form-control" onchange="checkField(this.value, 'processNameErr')">
                                <span class="help-block errMsg" id="processNameErr"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="processQuantity">Cantidad de Lotes Trabajadas</label>
                                <input type="number" id="processQuantity" name="processQuantity" placeholder="Cantidad"
                                       class="form-control" min="0" onchange="checkField(this.value, 'processQuantityErr')">
                                <span class="help-block errMsg" id="processQuantityErr"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="unitPrice">Bs. Precio por Lote</label>
                                <input type="text" id="processPrice" name="processPrice" placeholder="Precio por Lote" class="form-control"
                                       onchange="checkField(this.value, 'processPriceErr')">
                                <span class="help-block errMsg" id="processPriceErr"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 form-group-sm">
                                <label for="processDescription" class="">Descrición</label>
                                <select class="form-control checkField" id="processDescription">
                                     <option value="">---</option>
                                     <option value="Abonado">Abonado</option>
                                     <option value="Siembra">Siembra</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row text-center">
                            <div class="col-sm-6 form-group-sm">
                                <button class="btn btn-primary btn-sm" id="addNewProcess">Añadir Proceso</button>
                            </div>

                            <div class="col-sm-6 form-group-sm">
                                <button type="reset" id="cancelAddProcess" class="btn btn-danger btn-sm cancelAddProcess" form='addNewProcessForm'>Cancelar</button>
                            </div>
                        </div>
                    </form><!-- end of form-->
                </div>
            </div>

            <!--- Process list div-->
            <div class="col-sm-12" id="processesListDiv">
                <!-- Process list Table-->
                <div class="row">
                    <div class="col-sm-12" id="processesListTable"></div>
                </div>
                <!--end of table-->
            </div>
            <!--- End of process list div-->

        </div>
    </div>
    <!-- End of row of adding new process form and processes list table-->
</div>

<!--modal to update stock-->
<div id="updateStockModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center">Actualizar Stock</h4>
                <div id="stockUpdateFMsg" class="text-center"></div>
            </div>
            <div class="modal-body">
                <form name="updateStockForm" id="updateStockForm" role="form">
                    <div class="row">
                        <div class="col-sm-4 form-group-sm">
                            <label>Tipo del Proceso</label>
                            <input type="text" readonly id="stockUpdateProcessName" class="form-control">
                        </div>

                        <div class="col-sm-4 form-group-sm">
                            <label>Código del Proceso</label>
                            <input type="text" readonly id="stockUpdateProcessCode" class="form-control">
                        </div>

                        <div class="col-sm-4 form-group-sm">
                            <label>Cantidad de Áreas</label>
                            <input type="text" readonly id="stockUpdateProcessQInStock" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-6 form-group-sm">
                            <label for="stockUpdateType">Actualizar Tipo</label>
                            <select id="stockUpdateType" class="form-control checkField">
                                <option value="">---</option>
                                <option value="newStock">Nuevo Stock</option>
                                <option value="deficit">Déficit</option>
                            </select>
                            <span class="help-block errMsg" id="stockUpdateTypeErr"></span>
                        </div>

                        <div class="col-sm-6 form-group-sm">
                            <label for="stockUpdateQuantity">Cantidad</label>
                            <input type="number" id="stockUpdateQuantity" placeholder="Actualizar cantidad"
                                   class="form-control checkField" min="0">
                            <span class="help-block errMsg" id="stockUpdateQuantityErr"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="stockUpdateDescription" class="">Descripción</label>
                            <textarea class="form-control checkField" id="stockUpdateDescription" placeholder="Actualizar descripción"></textarea>
                            <span class="help-block errMsg" id="stockUpdateDescriptionErr"></span>
                        </div>
                    </div>

                    <input type="hidden" id="stockUpdateProcessId">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="stockUpdateSubmit">Actualizar</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<!--end of modal-->



<!--modal to edit process-->
<div id="editProcessModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center">Editar proceso</h4>
                <div id="editProcessFMsg" class="text-center"></div>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="row">
                        <div class="col-sm-4 form-group-sm">
                            <label for="processNameEdit">Tipo del Proceso</label>
                            <input type="text" id="processNameEdit" placeholder="Process Name" autofocus class="form-control checkField">
                            <span class="help-block errMsg" id="processNameEditErr"></span>
                        </div>

                        <div class="col-sm-4 form-group-sm">
                            <label for="processCode">Código del proceso</label>
                            <input type="text" id="processCodeEdit" class="form-control">
                            <span class="help-block errMsg" id="processCodeEditErr"></span>
                        </div>

                        <div class="col-sm-4 form-group-sm">
                            <label for="unitPrice">Precio por Lote</label>
                            <input type="text" id="processPriceEdit" name="processPrice" placeholder="Precio por lote" class="form-control checkField">
                            <span class="help-block errMsg" id="processPriceEditErr"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 form-group-sm">
                            <label for="processDescriptionEdit" class="">Descripción (Opcional)</label>
                            <textarea class="form-control" id="processDescriptionEdit" placeholder="Optional Process Description"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="processIdEdit">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="editProcessSubmit">Guardar</button>
                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<!--end of modal-->
<script src="<?=base_url()?>public/js/processes.js"></script>