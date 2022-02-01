<?php defined('BASEPATH') OR exit('') ?>

<div class='col-sm-6'>
    <?= isset($range) && !empty($range) ? $range : ""; ?>
</div>

<div class='col-xs-12'>
    <div class="panel panel-primary">
        <div class="panel-heading" style="text-align:center; font-size: 20px;">INFORMACION DEL BECARIO</div>
        <!-- Default panel contents -->

        <?php if($allBecarioInfo):?>


            <div class="row">
                <div class='col-sm-6 ' ><h5><span id="subjectName">Nombre del becario: <?=$allBecarioInfo->name?></span></h5></div>
                <div class='col-sm-6'><h5><span id="subjectCode">Codigo del becario: <?=$allBecarioInfo->code?></span></h5></div>
            </div>
            <div class="row" >
                <div class='col-lg-6' ><h5><span id="subjectSemester">Semestre del becario: <?=$this->genmod->getTableCol('semesters', 'name', 'id', $allBecarioInfo->semester)?></span></h5></div>

            </div>

        <?php endif;?>

        <?php if($allTrabajosInfo): ?>

            <div class="table table-responsive">
                <table class="table table-bordered table-striped" style="background-color: #f5f5f5">


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
                    <?php foreach($allTrabajosInfo as $get): ?>

                        <tr>
                            <th class="countSN"><?=$sn?>.</th>
                            <td><span id="trabajoName"><?=$get->trabajo_name?></span></td>
                            <td>
                                <span id="workhours"><?=$get->hours?></span>
                            </td>
                            <td><span id="accomplished">
                                        <?php if($get->accomplished==1): ?>
                                            TERMINADO
                                        <?php else: ?>
                                            EN PROCESO
                                        <?php endif; ?>
                                    </span></td>
                            <td>
                                <span id="assignedDate"><?=$get->assignDate?></span>
                            </td>

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


        <?php if($allBecarioInfo):?>
            <div class="table table-responsive">
                <table class="table table-bordered table-striped" style="background-color: #f5f5f5">
                    <tr>
                        <th><h4>Horas cumplidas en total:<h4> </th>
                        <td><h4><span id="checkedhours"><?php if($this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'becarioCode', $allBecarioInfo->code,'accomplished',1)):?>
                                        <?=$this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'becarioCode', $allBecarioInfo->code,'accomplished',1)?>
                                    <?php else:?>
                                        <?=0?>
                                    <?php endif;?>
                </span></h4></td>
                    </tr>
                    <tr>
                        <th><h4>Horas asignadas en total: <h4></th>
                        <td><h4><span id="assignedhours"><?php if($this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'becarioCode', $allBecarioInfo->code,'accomplished',0)):?>
                                        <?=$this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'becarioCode', $allBecarioInfo->code,'accomplished',0)?>
                                    <?php else:?>
                                        <?=0?>
                                    <?php endif;?>
                    </span></h4></td>
                    </tr>
                    <tr>
                        <th><h4>Horas faltantes en total: <h4></th>
                        <td><h4><span id="missinghours"><?=$allBecarioInfo->missinghours?></span></h4></td>
                    </tr>
                    <th><h4>Horas totales del becario: <h4></th>
                    <td><h4><span id="totalhours"><?=$hoursTot?></span></h4></td>
                    </tr>
                </table>
            </div>
        <?php endif;?>
    </div>
    <!--- panel end-->
</div>

<!---Pagination div-->
<div class="col-sm-12 text-center">
    <ul class="pagination">
        <?= isset($links) ? $links : "" ?>
    </ul>
</div>