<?php defined('BASEPATH') OR exit('') ?>

<div class='col-sm-6'>
    <?= isset($range) && !empty($range) ? $range : ""; ?>
</div>

<div class='col-xs-12'>
    <div class="panel panel-primary">


        <?php if($type==="becario"): ?>
            <div class="panel-heading" style="text-align:center;">TABLA DE REPORTE DEL BECARIO</div>
        <?php elseif($type==="trabajo"): ?>
            <div class="panel-heading" style="text-align:center;" >TABLA DE REPORTE DEL TRABAJO</div>
        <?php else: ?>
            <div class="panel-heading" style="text-align:center;">TABLA DE REPORTE</div>
        <?php endif; ?>
        <!-- Default panel contents -->

        <?php if($allSubjectInfo):?>

            <?php if($type==="becario"): ?>
                <div class="row">
                    <input type="hidden" value="<?=$type?>" id="reportType">
                    <div class='col-sm-6 ' ><h5><span id="subjectName">Nombre del becario: <?=$allSubjectInfo->name?></span></h5></div>
                    <div class='col-sm-6'><h5><span id="subjectCode">Codigo del becario: <?=$allSubjectInfo->code?></span></h5></div>
                </div>
                <div class="row" >
                    <div class='col-lg-6' ><h5><span id="subjectSemester">Semestre del becario: <?=$this->genmod->getTableCol('semesters', 'name', 'id', $allSubjectInfo->semester)?></span></h5></div>

                </div>
            <?php elseif($type==="trabajo"): ?>

                <div class="row">
                    <input type="hidden" value="<?=$type?>" id="reportType">
                    <div class='col-sm-6 ' ><h5><span id="subjectName">Nombre del trabajo: <?=$allSubjectInfo->name?></span></h5></div>
                    <div class='col-sm-6'><h5><span id="subjectDesc">Descripción del trabajo: <?=$allSubjectInfo->description?></span></h5></div>
                </div>
                <div class="row" >
                <div class='col-sm-4' ><h5><span id="subjectSemester">Semestre del trabajo: <?=$this->genmod->getTableCol('semesters', 'name', 'id', $allSubjectInfo->semester)?></span></h5></div>
                <div class='col-sm-4' ><h5><span id="subjectHours">Horas del trabajo: <?=$allSubjectInfo->workhours?></span></h5></div>
                <?php if($allInfo): ?>
                    <div class='col-sm-4' ><h5><span id="subjectAccomplished">Estado del trabajo:
                    <?php if($allInfo[0]->accomplished==1): ?>
                        TERMINADO
                    <?php elseif($allInfo[0]->accomplished==0): ?>
                        EN PROCESO
                    <?php endif;?>
                </span></h5></div>
                    </div>
                <?php endif;?>

            <?php endif; ?>
        <?php endif;?>

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


        <?php if($allSubjectInfo):?>
            <div class="table table-responsive">
                <table class="table table-bordered table-striped" style="background-color: #f5f5f5">
                    <?php if($type==="becario"): ?>
                        <?php $horasAsign = 0; ?>
                        <?php $horasCumplid = 0; ?>
                        <?php $horasTot=0;?>

                        <tr>
                            <th><h4>Horas cumplidas en total:<h4> </th>
                            <td><h4><span id="checkedhours-<?=$allSubjectInfo->id?>"><?php if($this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'becarioCode', $allSubjectInfo->code,'accomplished',1)):?>
                                            <?=$this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'becarioCode', $allSubjectInfo->code,'accomplished',1)?>
                                            <?php $horasCumplid = $this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'becarioCode', $allSubjectInfo->code,'accomplished',1);?>
                                        <?php else:?>
                                            <?=0?>
                                        <?php endif;?>
                </span></h4></td>
                        </tr>
                        <tr>
                            <th><h4>Horas asignadas en total: <h4></th>
                            <td><h4><span id="assignedhours-<?=$allSubjectInfo->id?>"><?php if($this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'becarioCode', $allSubjectInfo->code,'accomplished',0)):?>
                                            <?=$this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'becarioCode', $allSubjectInfo->code,'accomplished',0)?>
                                            <?php $horasAsign = $this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'becarioCode', $allSubjectInfo->code,'accomplished',0); ?>
                                        <?php else:?>
                                            <?=0?>
                                        <?php endif;?>
                    </span></h4></td>
                        </tr>
                        <tr>
                            <th><h4>Horas faltantes en total: <h4></th>
                            <td><h4><span id="missinghours-<?=$allSubjectInfo->id?>"><?=$allSubjectInfo->missinghours?></span></h4></td>
                        </tr>
                        <th><h4>Horas totales del becario: <h4></th>
                        <td><h4><span id="totalhours-<?=$allSubjectInfo->id?>">
                <?php $horasTot=$horasAsign+$horasCumplid+$allSubjectInfo->missinghours?>
                <?=$horasTot?>
                </span></h4></td>
                        </tr>
                    <?php elseif($type==="trabajo"): ?>

                        <tr>
                            <th><h4>Horas cumplidas del trabajo en total:<h4> </th>
                            <td><h4><span id="checkedWorkhours-<?=$allSubjectInfo->id?>">
                <?php if($this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'trabajo_code', $allSubjectInfo->id,'accomplished',1)):?>
                    <?=$this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'trabajo_code', $allSubjectInfo->id,'accomplished',1)?>
                <?php else:?>
                    <?=0?>
                <?php endif;?>
                </span></h4></td>

                        </tr>
                        <tr>

                            <th><h4>Horas asignadas del trabajo en total: <h4></th>
                            <td><h4><span id="assignedWorkhours-<?=$allSubjectInfo->id?>">
                <?php if($this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'trabajo_code', $allSubjectInfo->id,'accomplished',0)): ?>
                    <?=$this->genmod->getTableColMultiple('asignaciones', 'SUM(hours)', 'trabajo_code', $allSubjectInfo->id,'accomplished',0)?>
                <?php else:?>
                    <?=0?>
                <?php endif;?>
                </span></h4></td>
                        </tr>

                    <?php endif; ?>
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
