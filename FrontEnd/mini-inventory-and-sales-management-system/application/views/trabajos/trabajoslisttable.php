<?php defined('BASEPATH') OR exit('') ?>

<div class='col-sm-6'>
    <?= isset($range) && !empty($range) ? $range : ""; ?>
</div>

<div class='col-xs-12'>
    <div class="panel panel-primary">
        <!-- Default panel contents -->
        <div class="panel-heading">TABLA DE TRABAJOS</div>
        <?php if($allTrabajos): ?>
        <div class="table table-responsive">
            <table class="table table-bordered table-striped" style="background-color: #f5f5f5">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>NOMBRE</th>
                        <th>DESCRIPCION</th>
                        <th>HORAS TOTALES</th>
                        <th>BECARIOS ASIGNADOS</th>
                        <th colspan="4"> Acciones</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach($allTrabajos as $get): ?>
                    <tr>
                        <input type="hidden" value="<?=$get->id?>" class="curTrabajoId">
                        <th class="trabajoSN"><?=$sn?>.</th>
                        <td><span id="trabajoName-<?=$get->id?>"><?=$get->name?></span></td>
                        <td>
                            <span id="trabajoDesc-<?=$get->id?>" data-toggle="tooltip" title="<?=$get->description?>" data-placement="auto">
                                <?=word_limiter($get->description, 15)?>
                            </span>
                        </td>

                        <td>
                            <span id="workhours-<?=$get->id?>"><?=$get->workhours?></span>
                        </td>
                        
                        <td>
                          <ul>
                                                    <?php if(!is_bool($allAsignaciones)) {
                                                     foreach($allAsignaciones as $getIt)
                                                                                                        {
                                                                                                         if ($getIt->trabajo_name === $get->name && $getIt->accomplished==0 ) {
                                                                                                             echo '<li><a class="pointer delBecario" id="'.$getIt->becarioName.'">'.$getIt->becarioName.'</a></li>';
                                                                                                             }
                                                                                                        }
                                                    }

                                                    ?>

                                                    </ul>
                        </td>

                        <td class="text-center text-primary"><span class=" assignBecarios" id="asign-<?=$get->id?>" title="Añadir Becario"><i class="fa fa-user-plus fa-2x pointer" ></i></a></td>

                        <td class="text-center text-primary"><span class=" updateTrabajoHours" id="stock-<?=$get->id?>" title="Modificar horas de trabajo"><i class="fa fa-clock-o fa-2x pointer"></i></a></td>

                        <td class="text-center text-primary">
                            <span class="editTrabajo" id="edit-<?=$get->id?>"  title="Modificar información de trabajo"><i class="fa fa-pencil fa-2x pointer"></i> </span>
                        </td>
                        <td class="text-center"><i class="fa fa-trash fa-2x text-danger delTrabajo pointer"  title="Eliminar trabajo"></i></td>
                    </tr>
                    <?php $sn++; ?>
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

<!---Pagination div-->
<div class="col-sm-12 text-center">
    <ul class="pagination">
        <?= isset($links) ? $links : "" ?>
    </ul>
</div>