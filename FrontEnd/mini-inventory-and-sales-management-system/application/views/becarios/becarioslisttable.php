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
                        <th>HORAS DE TRABAJO BECARIO TOTALES</th>
                        <th>HORAS DE TRABAJO BECARIO CUMPLIDAS</th>
                        <th>HORAS DE TRABAJO BECARIO ASIGNADAS</th>
                        <th>HORAS DE TRABAJO BECARIO FALTANTES</th>
                        <th>TRABAJOS ASIGNADOS</th>
                        <th>ACTUALIZAR HORAS DE TRABAJO BECARIO TOTALES</th>
                        <th>EDITAR INFORMACION DE BECARIO</th>
                        <th>ELIMINAR BECARIO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($allBecarios as $get): ?>
                    <tr>
                        <input type="hidden" value="<?=$get->id?>" class="curBecarioId">
                        <th class="becarioSN"><?=$sn?>.</th>
                        <td><span id="becarioName-<?=$get->id?>"><?=$get->name?></span></td>
                        <td><span id="becarioCode-<?=$get->id?>"><?=$get->code?></td>

                        <td class="<?=$get->totalhours >= 50 ? 'bg-danger' : ($get->totalhours >= 25 ? 'bg-warning' : '')?>">
                            <span id="totalhours-<?=$get->id?>"><?=$get->totalhours?></span>
                        </td>
                        <td>
                             <span id="checkedhours-<?=$get->id?>"><?=$get->checkedhours?></span>
                        </td>
                        <td>
                             <span id="assignedhours-<?=$get->id?>"><?=$get->assignedhours?></span>
                        </td>
                        <td>
                             <span id="missinghours-<?=$get->id?>"><?=$get->missinghours?></span>
                        </td>

                        <td><a class="pointer updateStock" id="stock-<?=$get->id?>">Actualizar cantidad</a></td>

                        <td class="text-center text-primary">
                            <span class="editBecario" id="edit-<?=$get->id?>"><i class="fa fa-pencil pointer"></i> </span>
                        </td>
                        <td class="text-center"><i class="fa fa-trash text-danger delBecario pointer"></i></td>
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
