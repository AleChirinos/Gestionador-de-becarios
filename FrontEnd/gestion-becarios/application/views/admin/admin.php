<?php
defined('BASEPATH') OR exit('');
?>

<div class="row hidden-print">
    <div class="col-sm-12">
        <div class="pwell">
            <!-- Header (add new admin, sort order etc.) -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2 fa fa-user-plus pointer" style="color:#337ab7" data-target='#addNewAdminModal' data-toggle='modal'>
                        Nuevo Usuario
                    </div>

                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="adminListPerPage">Mostrar</label>
                        <select id="adminListPerPage" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="adminListPerPage">por página</label>
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="adminListSortBy" class="control-label">Ordenar por</label> 
                        <select id="adminListSortBy" class="form-control">
                            <option value="first_name-ASC" selected>Nombre (A a Z)</option>
                            <option value="first_name-DESC">Nombre (Z a A)</option>
                            <option value="created_on-ASC">Fecha de creación(Más antiguos primero)</option>
                            <option value="created_on-DESC">Fecha de creación(Recientes primero)</option>
                            <option value="email-ASC">Correo - ascendiente</option>
                            <option value="email-DESC">Correo - descendiente</option>
                        </select>
                    </div>
                    <div class="col-sm-3 form-inline form-group-sm">
                        <label for="adminSearch"><i class="fa fa-search"></i></label>
                        <input type="search" id="adminSearch" placeholder="Buscar...." class="form-control">
                    </div>
                    <br><br><br>
                    <div class="col-sm-2 fa fa-user-plus pointer" style="color:#337ab7" data-target='#addNewManagementModal' data-toggle='modal'>
                        Nueva Gestión
                    </div>
                    <div class="col-sm-4 form-inline form-group-sm">
                        <label for="adminListSortBy" class="control-label">Gestiones</label>
                        <select id="adminListSortBy" class="form-control">
                            <option value="first_name-ASC" selected>Nombre (A a Z)</option>
                            <option value="first_name-DESC">Nombre (Z a A)</option>
                            <option value="created_on-ASC">Fecha de creación(Más antiguos primero)</option>
                            <option value="created_on-DESC">Fecha de creación(Recientes primero)</option>
                            <option value="email-ASC">Correo - ascendiente</option>
                            <option value="email-DESC">Correo - descendiente</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <hr>
            <!-- Header (sort order etc.) ends -->
            
            <!-- Admin list -->
            <div class="row">
                <div class="col-sm-12" id="allAdmin"></div>
            </div>
            <!-- Admin list ends -->
        </div>
    </div>
</div>


<!--- Modal to add new admin --->
<div class='modal fade' id='addNewAdminModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Añadir Nuevo Usuario</h4>
                <div class="text-center">
                    <i id="fMsgIcon"></i><span id="fMsg"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='addNewAdminForm' name='addNewAdminForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='firstName' class="control-label">Primer Nombre</label>
                            <input type="text" id='firstName' class="form-control checkField" placeholder="Primer Nombre">
                            <span class="help-block errMsg" id="firstNameErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='lastName' class="control-label">Apellido</label>
                            <input type="text" id='lastName' class="form-control checkField" placeholder="Apellido">
                            <span class="help-block errMsg" id="lastNameErr"></span>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='email' class="control-label">Correo Electrónico</label>
                            <input type="email" id='email' class="form-control checkField" placeholder="Correo Electrónico">
                            <span class="help-block errMsg" id="emailErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='role' class="control-label">Puesto</label>
                                <?php if($this->session->admin_role === "Super"): ?>
                                    <select class="form-control checkField" id='role'>
                                    <option value=''>Tipo de Usuario</option>
                                    <option value='Super'>Administrador</option>
                                    <option value='Jefe De Carrera'>Jefe de Carrera</option>
                                    <option value='Gestionador'>Gestionador</option>
                                <?php else: ?>
                                     <input  type="hidden" class="form-control checkField" id='role' value='Gestionador'>
                                    <br>
                                    <label for='career' class="control-label">Gestionador</label>
                                <?php endif; ?>
                            </select>
                            <span class="help-block errMsg" id="roleErr"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='career' class="control-label">Carrera</label>
                            <?php if($this->session->admin_role === "Super"): ?>
                                <input type="text" id='career' class="form-control checkField" placeholder="Carrera">
                            <?php else: ?>
                                <input type="hidden" id='career' class="form-control checkField" value="<?php echo $this->session->admin_career; ?>">
                                <br>
                                <label for='career' class="control-label"><?php echo $this->session->admin_career; ?></label>
                            <?php endif; ?>
                            <span class="help-block errMsg" id="careerErr"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='semester' class="control-label">Semestre</label>
                            <?php if($this->session->admin_role === "Super"): ?>
                                <input type="text" id='semester' class="form-control checkField" placeholder="Semestre">
                            <?php else: ?>
                                <input type="hidden" id='semester' class="form-control checkField" value="<?php echo $this->session->admin_semester; ?>">
                                <br>
                                <label for='semester' class="control-label"><?php echo $this->session->admin_semester; ?></label>
                            <?php endif; ?>
                            <span class="help-block errMsg" id="semesterErr"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="addNewAdminForm" class="btn btn-warning pull-left">Limpiar</button>
                <button type='button' id='addAdminSubmit' class="btn btn-primary">Añadir Usuario</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--- end of modal to add new admin --->


<!--- Modal to add new management --->
<div class='modal fade' id='addNewManagementModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Añadir Nueva Gestión</h4>
                <div class="text-center">
                    <i id="fMsgIcon"></i><span id="fMsg"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='addNewManagementForm' name='addNewManagementForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='m_name' class="control-label">Título de la Gestión</label>
                            <input type="text" id='m_name' class="form-control checkField" placeholder="Título de la Gestión">
                            <span class="help-block errMsg" id="mNameErr"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="addNewManagementForm" class="btn btn-warning pull-left">Limpiar</button>
                <button type='button' id='addManagementSubmit' class="btn btn-primary">Añadir Gestión</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--- end of modal to add new management --->



<!--- Modal for editing admin details --->
<div class='modal fade' id='editAdminModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Editar información de usuario</h4>
                <div class="text-center">
                    <i id="fMsgEditIcon"></i>
                    <span id="fMsgEdit"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='editAdminForm' name='editAdminForm' role='form'>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='firstNameEdit' class="control-label">Primer nombre</label>
                            <input type="text" id='firstNameEdit' class="form-control checkField" placeholder="Nombre">
                            <span class="help-block errMsg" id="firstNameEditErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='lastNameEdit' class="control-label">Apellido</label>
                            <input type="text" id='lastNameEdit' class="form-control checkField" placeholder="Apellido">
                            <span class="help-block errMsg" id="lastNameEditErr"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='emailEdit' class="control-label">Correo Electrónico</label>
                            <input type="email" id='emailEdit' class="form-control checkField" placeholder="Correo Electrónico">
                            <span class="help-block errMsg" id="emailEditErr"></span>
                        </div>
                        <div class="form-group-sm col-sm-6">
                            <label for='roleEdit' class="control-label">Puesto</label>
                            <select class="form-control checkField" id='roleEdit'>
                                <option value=''>Tipo de Usuario</option>
                                <option value='Super'>Administrador</option>
                                <option value='Jefe De Carrera'>Jefe de Carrera</option>
                                <option value='Gestionador'>Gestionador</option>
                            </select>
                            <span class="help-block errMsg" id="roleEditErr"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group-sm col-sm-6">
                            <label for='careerEdit' class="control-label">Carrera</label>
                            <input type="text" id='careerEdit' class="form-control checkField" placeholder="Carrera">
                            <span class="help-block errMsg" id="careerEditErr"></span>
                        </div>
                    </div>
                    <input type="hidden" id="adminId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" form="editAdminForm" class="btn btn-warning pull-left">Limpiar</button>
                <button type='button' id='editAdminSubmit' class="btn btn-primary">Actualizar</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--- end of modal to edit admin details --->
<script src="<?=base_url()?>public/js/admin.js"></script>