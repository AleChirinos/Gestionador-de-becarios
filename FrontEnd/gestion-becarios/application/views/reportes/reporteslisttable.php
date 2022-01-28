<?php defined('BASEPATH') OR exit('') ?>

<div class='col-sm-6'>
    <?= isset($range) && !empty($range) ? $range : ""; ?>
</div>

<div class='col-xs-12'>
    <div class="panel panel-primary">
        <?php if($type==="becario"): ?>
        <div class="panel-heading">TABLA DE REPORTE DEL BECARIO</div>
        <?php else: ?>
        <div class="panel-heading">TABLA DE REPORTE DEL TRABAJO</div>
        <?php endif; ?>
        <!-- Default panel contents -->
       
        <?php if($allInfo): ?>
        <div class="table table-responsive">
            <table class="table table-bordered table-striped" style="background-color: #f5f5f5">

            <?php if($type==="becario"): ?>
                <thead>

                    <tr>
                        <th>Nº</th>
                        <th>NOMBRE DEL TRABAJO</th>
                        <th>HORAS DE VALOR</th>
                        <th>CUMPLIDO</th>
                        <th>FECHA DE ASIGNACION</th>                
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($allInfo as $get): ?>
                        
                            <tr>
                                <input type="hidden" value="<?=$get->id?>" class="curTrabajoId">
                                <th class="countSN"><?=$sn?>.</th>
                                <td><span id="trabajoName-<?=$get->id?>"><?=$get->trabajo_name?></span></td>
                                <td>
                                    <span id="workhours-<?=$get->id?>"><?=$get->hours?></span>
                                </td>
                                <td><span id="trabajoName-<?=$get->id?>">
                                <?php if($get->accomplished==1): ?>
                                TERMINADO
                                <?php else: ?>
                                EN PROCESO
                                <?php endif; ?>
                            </span></td>
                                <td>
                                    <span id="assignedDate-<?=$get->id?>"><?=$get->assignDate?></span>
                                </td>
                               
                            </tr>
                            <?php $sn++; ?>
                       
                    <?php endforeach; ?>
                </tbody>
            <?php else: ?>

                <thead>

                    <tr>
                        <th>Nº</th>
                        <th>NOMBRE DEL BECARIO</th>
                        <th>CODIGO DEL BECARIO</th>
                        <th>CUMPLIDO</th>
                        <th>FECHA DE ASIGNACION</th>  
                        <th>HORAS REALIZADAS</th>              
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($allInfo as $get): ?>
                        
                            <tr>
                                <input type="hidden" value="<?=$get->id?>" class="curBecarioId">
                                <th class="countSN"><?=$sn?>.</th>
                                <td><span id="becarioName-<?=$get->id?>"><?=$get->becarioName?></span></td>
                                <td><span id="becarioCode-<?=$get->id?>"><?=$get->becarioCode?></span></td>
                                <td><span id="accomplished-<?=$get->id?>">
                                <?php if($get->accomplished==1): ?>
                                    TERMINADO
                                <?php else: ?>
                                EN PROCESO
                                <?php endif; ?>
                            </span></td>
                                <td>
                                    <span id="assignedDate-<?=$get->id?>"><?=$get->assignDate?></span>
                                </td>
                                <td>
                                    <span id="hours-<?=$get->id?>"><?=$get->hours?></span>
                                </td>
                               
                            </tr>
                            <?php $sn++; ?>
                       
                    <?php endforeach; ?>
                </tbody>
            <?php endif; ?>

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
