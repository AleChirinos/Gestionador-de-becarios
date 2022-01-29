<?php
defined('BASEPATH') OR exit('');
?>
<?php if($allReportInfo):?>
<?php $sn = 1; ?>
<div id="reportPopUpToPrint">
    <div class="row">
        <div class="col-xs-12 text-center text-uppercase">
            <center style='margin-bottom:5px'><img src="<?=base_url()?>public/images/upb_logo.png" alt="logo" class="img-responsive" width="60px"></center>
            <b>Gestionador de becarios</b>
            <div>Sistema creado por la Universidad Privada Boliviana</div>
        </div>
    </div>
    
    
    <div class="row" style="margin-top:2px">
        <div class="col-sm-12">
            <label>Entrada a la base de datos No:</label>
            <span><?=isset($ref) ? $ref : ""?></span>
		</div>
    </div>

    <div class="row" style="margin-top:2px">
        <div class="col-sm-12">
            <label>Nombre del trabajo:</label>
            <span><?=$trabajoName?></span>
		</div>
        <div class="col-sm-12">
            <label>Horas de valor del trabajo</label>
            <span><?=$trabajoHours?></span>
		</div>
    </div>

    <div class="row" style="margin-top:2px">
        <div class="col-sm-12">
            <label>Semestre del trabajo:</label>
            <span><?=$trabajoSemester?></span>
		</div>
        <div class="col-sm-12">
            <label>Estado del trabajo:</label>
            <span><?=isset($trabajoStatus) && $trabajoStatus==1 ? "Terminado" : "En proceso"?></span>
		</div>
    </div>
    
	<div class="row" style='font-weight:bold'>
		<div class="col-xs-4">Nombre del becario</div>
		<div class="col-xs-4">Codigo del becario</div>
		<div class="col-xs-4">Horas</div>
        <div class="col-xs-4">Fecha de asignacion</div>
	</div>
	<hr style='margin-top:2px; margin-bottom:0px'>
    <?php $horasAsign = 0; ?>
    <?php $horasCumplid = 0; ?>
    <?php $horasTot=0;?>
    <?php foreach($allReportInfo as $get):?>
        <div class="row">
            <div class="col-xs-4"><?=$get['becarioName']?></div>
            <div class="col-xs-4"><?=$get['becarioCode']?></div>
            <div class="col-xs-4"><?=$get['hours']?></div>
            <div class="col-xs-4"><?=isset($get['assignDate']) ? date('jS M, Y h:i:sa', strtotime($get['assignDate'])) : ""?></div>
        </div>
        <?php if (isset($get['accomplished']) && $get['accomplished']==1 ):?>
        <?php $horasCumplid += $get['hours'];?>
        <?php elseif(isset($get['accomplished']) && $get['accomplished']==0):?>
        <?php $horasAsign += $get['hours'];?>
        <?php endif;?>
        
    <?php endforeach; ?>
    <?php $horasTot=$horasAsign+$horasCumplid?>
    <hr style='margin-top:2px; margin-bottom:0px'>       
    <div class="row">
        <div class="col-xs-12 text-right">
            <b>Horas de trabajo cumplidas &#8358;<?=isset($horasCumplid) ? $horasCumplid : 0?></b>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-right">
            <b>Horas de trabajo asignadas &#8358;<?=isset($horasAsign) ? $horasAsign : 0?></b>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xs-12 text-right">
            <b>Horas totales &#8358;<?=isset($horasTot) ? $horasTot : 0?></b>
        </div>
    </div>
   
    
    <hr style='margin-top:5px; margin-bottom:0px'>
   
    <div class="row">
        <div class="col-xs-12 text-center">Fin del reporte de trabajo</div>
    </div>
</div>
<br class="hidden-print">
<div class="row hidden-print">
    <div class="col-sm-12">
        <div class="text-center">
            <button type="button" class="btn btn-primary ptr">
                <i class="fa fa-print"></i> Imprimir reporte
            </button>
            
            <button type="button" data-dismiss='modal' class="btn btn-danger">
                <i class="fa fa-close"></i> Cerrar
            </button>
        </div>
    </div>
</div>
<br class="hidden-print">
<?php endif;?>