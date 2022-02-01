<?php defined('BASEPATH') OR exit('') ?>

<div class='col-sm-6'>
    <?= isset($range) && !empty($range) ? $range : ""; ?>
</div>

<div class='col-xs-12'>
    <div class="panel panel-primary">
        <!-- Default panel contents -->
        <div class="panel-heading">TABLA DE BECARIOS</div>
        <?php if($allBecarios): ?>
        <div class="table table-responsive">
            <table class="table table-bordered table-striped" style="background-color: #f5f5f5">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>NOMBRE</th>
                        <th>CÓDIGO</th>
                        <th>HORAS TOTALES</th>
                        <th>HORAS CUMPLIDAS</th>
                        <th>HORAS ASIGNADAS</th>
                        <th>HORAS FALTANTES</th>
                        <th>TRABAJOS ASIGNADOS</th>
                        <th>TRABAJOS TERMINADOS</th>
                        <?php if($this->session->admin_role !== "Gesti") {?>
                        <th colspan="3"> ACCIONES</th>
                        <?php };?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($allBecarios as $get): ?>
                        <?php if($this->session->admin_career ===$get->career):?>
                            <tr>
                                <input type="hidden" value="<?=$get->id?>" class="curBecarioId">
                                <th class="becarioSN"><?=$sn?>.</th>
                                <td><span id="becarioName-<?=$get->id?>"><?=$get->name?></span></td>
                                <td><span id="becarioCode-<?=$get->id?>"><?=$get->code?></td>
                                <td>
                                    <span id="totalhours-<?=$get->id?>"><?=$get->totalhours?></span>
                                </td>
                                <td>
                                    <span id="checkedhours-<?=$get->id?>"><?=$get->checkedhours?></span>
                                </td>
                                <td>
                                    <span id="assignedhours-<?=$get->id?>"><?=$get->assignedhours?></span>
                                </td>
                                <td class="<?=$get->missinghours >= 50 ? 'bg-danger' : ($get->missinghours >= 25 ? 'bg-warning' : '')?>">
                                    <span id="missinghours-<?=$get->id?>"><?=$get->missinghours?></span>
                                </td>
                                <td>
                                    <ul>
                                        <?php if(!is_bool($allAsignaciones)) {
                                            foreach($allAsignaciones as $getIt)
                                            {
                                                if ($getIt->becarioName === $get->name && $getIt->accomplished==0 && $this->session->admin_career ===$get->career) {
                                                    echo '<li><a class="pointer delBecario" id="'.$getIt->trabajo_name.'">'.$getIt->trabajo_name.'</a></li>';
                                                }
                                            }
                                        }?>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <?php if(!is_bool($allAsignaciones)) {
                                            foreach($allAsignaciones as $getIt)
                                            {
                                                if ($getIt->becarioName === $get->name && $getIt->accomplished==1 && $this->session->admin_career ===$get->career) {
                                                    echo '<li><a class="pointer delBecario" id="'.$getIt->trabajo_name.'">'.$getIt->trabajo_name.'</a></li>';
                                                }
                                            }
                                        }?>
                                    </ul>
                                </td>
                                <?php if($this->session->admin_role !== "Gesti") {?>
                                <td class="text-center text-primary"><span class=" updateMissingHours" id="stock-<?=$get->id?>" title="Modificar horas de trabajo becario a cumplir"><i class="fa fa-clock-o fa-2x pointer"></i></span></td>
                                <td class="text-center text-primary">
                                    <span class="editBecario" id="edit-<?=$get->id?>" title="Modificar información de becario"><i class="fa fa-pencil fa-2x pointer"></i> </span>
                                </td>
                                <td class="text-center"><i class="fa fa-trash fa-2x text-danger delBecario pointer" title="Eliminar becario" ></i></td>
                                <?php };?>
                            </tr>
                            <?php $sn++; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- table div end-->
        <?php else: ?>
        <ul><li>Sin elementos</li></ul>
        <?php endif; ?>
    </div>
    <!--- panel end-->
</div>


