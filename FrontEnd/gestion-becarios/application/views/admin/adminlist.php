<?php
defined('BASEPATH') OR exit('');


?>



<?php echo isset($range) && !empty($range) ? "Mostrando ".$range : ""?>
<div class="panel panel-primary">
    <div class="panel-heading">CUENTAS DE USUARIOS</div>
    <?php if($allAdministrators):?>
    <div class="table table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>NOMBRE</th>
                    <th>CORREO</th>
                    <th>TIPO DE USUARIO</th>
                    <th>CARRERA</th>
                    <th>GESTIÓN</th>
                    <th>FECHA DE CREACIÓN</th>
                    <th>ÚLTIMO INICIO DE SESIÓN</th>
                    <th>EDITAR</th>
                    <th>ESTADO DE CUENTA</th>
                    <th>ELIMINAR</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($allAdministrators as $get):?>
                <?php if($get->deleted == "0" && $get->role =="Gesti" && $this->session->admin_career ===$get->career ):?>
                    <tr>
                        <th class="adminSN"><?=$sn?>.</th>
                        <td class="adminName"><?=$get->first_name ." ". $get->last_name?></td>
                        <td class="hidden firstName"><?=$get->first_name?></td>
                        <td class="hidden lastName"><?=$get->last_name?></td>
                        <td class="adminEmail"><?=mailto($get->email)?></td>
                        <td class="adminRole"><?=$get->role?></td>
                        <td class="adminCareer"><?=$get->career?></td>
                        <td class="adminSemester"><?=$get->semester?></td>
                        <td><?=date('jS M, Y h:i:sa', strtotime($get->created_on))?></td>
                        <td>
                            <?=$get->last_login === "0000-00-00 00:00:00" ? "---" : date('jS M, Y h:i:sa', strtotime($get->last_login))?>
                        </td>
                        <td class="text-center editAdmin" id="edit-<?=$get->id?>">
                            <i class="fa fa-pencil pointer"></i>
                        </td>
                        <td class="text-center suspendAdmin text-success" id="sus-<?=$get->id?>">
                            <?php if($get->account_status === "1"): ?>
                                <i class="fa fa-toggle-on pointer"></i>
                            <?php else: ?>
                                <i class="fa fa-toggle-off pointer"></i>
                            <?php endif; ?>
                        </td>
                        <td class="text-center text-danger deleteAdmin" id="del-<?=$get->id?>">
                            <?php if($get->deleted === "1"): ?>
                                <a class="fa fa-trash deleteAdmin pointer">Deshacer eliminación</a>
                            <?php else: ?>
                                <i class="fa fa-trash pointer"></i>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php $sn++;?>
                <?php endif;?>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <?php else:?>
        Sin cuentas de usuario.
    <?php endif; ?>
</div>

<?php if($this->session->admin_role === "Super"): ?>
<br>
    <?php echo isset($range) && !empty($range) ? "Mostrando ".$range : ""?>
    <div class="panel panel-primary">
        <div class="panel-heading">CUENTAS DE JEFES DE CARRERA</div>
        <?php if($allAdministrators):?>
            <div class="table table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Nº</th>
                        <th>NOMBRE</th>
                        <th>CORREO</th>
                        <th>TIPO DE USUARIO</th>
                        <th>CARRERA</th>
                        <th>GESTIÓN</th>
                        <th>FECHA DE CREACIÓN</th>
                        <th>ÚLTIMO INICIO DE SESIÓN</th>
                        <th>EDITAR</th>
                        <th>ESTADO DE CUENTA</th>
                        <th>ELIMINAR</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($allAdministrators as $get):?>
                        <?php if($get->deleted == "0" && $get->role !=="Gesti"):?>
                            <tr>
                                <th class="adminSN"><?=$sn?>.</th>
                                <td class="adminName"><?=$get->first_name ." ". $get->last_name?></td>
                                <td class="hidden firstName"><?=$get->first_name?></td>
                                <td class="hidden lastName"><?=$get->last_name?></td>
                                <td class="adminEmail"><?=mailto($get->email)?></td>
                                <td class="adminRole"><?=$get->role?></td>
                                <td class="adminCareer"><?=$get->career?></td>
                                <td class="adminSemester"><?=$get->semester?></td>
                                <td><?=date('jS M, Y h:i:sa', strtotime($get->created_on))?></td>
                                <td>
                                    <?=$get->last_login === "0000-00-00 00:00:00" ? "---" : date('jS M, Y h:i:sa', strtotime($get->last_login))?>
                                </td>
                                <td class="text-center editAdmin" id="edit-<?=$get->id?>">
                                    <i class="fa fa-pencil pointer"></i>
                                </td>
                                <td class="text-center suspendAdmin text-success" id="sus-<?=$get->id?>">
                                    <?php if($get->account_status === "1"): ?>
                                        <i class="fa fa-toggle-on pointer"></i>
                                    <?php else: ?>
                                        <i class="fa fa-toggle-off pointer"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center text-danger deleteAdmin" id="del-<?=$get->id?>">
                                    <?php if($get->deleted === "1"): ?>
                                        <a class="fa fa-trash deleteAdmin pointer">Deshacer eliminación</a>
                                    <?php else: ?>
                                        <i class="fa fa-trash pointer"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php $sn++;?>
                        <?php endif;?>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        <?php else:?>
            Sin cuentas de usuario.
        <?php endif; ?>
    </div>
<?php endif; ?>
