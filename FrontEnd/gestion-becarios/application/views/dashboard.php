<?php
defined('BASEPATH') OR exit('');
?>

<div class="pwell hidden-print">   
    <div class="row">
        <div class="col-sm-12">
            <!-- sort and co row-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-lg-6 form-group-lg form-inline" >
                        <h4>Bienvenido al sistema de gestión de becarios de la UPB</h4>
                        <h6>Ingrese un código de estudiante para revisar su información</h6>
                        <label for='becarioDashSearch'><i class="fa fa-search"></i></label>
                        <input type="search" id="becarioDashSearch" class="form-control" placeholder="Ingresar código">
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <hr>
    
    <div class="row">
        <div class="col-sm-12">     
            
            <div class="col-sm-12" id="becariosDashListDiv">
                
                <div class="row">
                    <div class="col-sm-12" id="becarioDashListTable"></div>
                </div>
               
                
            </div>
           

        </div>
    </div>

    <div class="row margin-top-5">
    <div class="col-lg-5">
        <div class="panel panel-hash">
            <div class="panel-heading"><i class="fa fa-exchange"></i> Trabajos terminados con más becarios</div>
            <?php if($trabajosTerminados): ?>
            <table class="table table-striped table-responsive table-hover">
                <thead>
                    <tr>
                        <th>Nombre del trabajo</th>
                        <th>N° de estudiantes inscritos</th>
                        <th>Fecha de conclusión del trabajo</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($trabajosTerminados as $get):?>
                    <tr>
                        <td><?=$get->name?></td>
                        <td><?=$get->totBec?></td>
                        <td><?=$get->lastUpdated?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            Sin registros
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="panel panel-hash">
            <div class="panel-heading"><i class="fa fa-exchange"></i> Trabajos asignables con más becarios inscritos</div>
            <?php if($trabajosAsignados): ?>
            <table class="table table-striped table-responsive table-hover">
                <thead>
                    <tr>
                        <th>Nombre del trabajo</th>
                        <th>Horas de valor</th>
                        <th>N° de estudiantes inscritos</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($trabajosAsignados as $get):?>
                    <tr>
                        <td><?=$get->name?></td>
                        <td><?=$get->workhours?></td>
                        <td><?=$get->totBec?></td>
                        
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            Sin registros
            <?php endif; ?>
        </div>
    </div>
    </div>

    <div class="row margin-top-5">
    <div class="col-lg-5">
        <div class="panel panel-hash">
            <div class="panel-heading"><i class="fa fa-user"></i> Becarios con más horas a cumplir</div>
            <?php if($becariosConMasHorasFaltantes): ?>
            <table class="table table-striped table-responsive table-hover">
                <thead>
                    <tr>
                        <th>Nombre del estudiante</th>
                        <th>Código de estudiante</th>
                        <th>Horas faltantes</th>
                        <th>Gestión</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($becariosConMasHorasFaltantes as $get):?>
                    <tr>
                        <td><?=$get->name?></td>
                        <td><?=$get->code?></td>
                        <td><?=$get->missinghours?></td>
                        <td><?=$this->genmod->getTableCol('semesters','name','id',$get->semester) ? $this->genmod->getTableCol('semesters','name','id',$get->semester) : "" ?>
                        
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            Sin registros
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="panel panel-hash">
            <div class="panel-heading"><i class="fa fa-exchange"></i> Trabajos de mayor valor de horas</div>
            <?php if($trabajosPorHour): ?>
            <table class="table table-striped table-responsive table-hover">
                <thead>
                    <tr>
                        <th>Nombre del trabajo</th>
                        <th>Descripción del trabajo</th>
                        <th>Horas de valor</th>
                        <th>Disponibilidad</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($trabajosPorHour as $get):?>
                    <tr>
                        <td><?=$get->name?></td>
                        <td><?=$get->description?></td>
                        <td><?=$get->workhours?></td>
                        <td><?php if($get->accomplished && $get->accomplished==1): ?>
                                        TERMINADO
                                        <?php elseif($get->accomplished && $get->accomplished==0): ?>
                                        EN PROCESO
                                        <?php else:?>
                                            SIN BECARIOS
                                        <?php endif; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            Sin registros
            <?php endif; ?>
        </div>
    </div>
    </div>

    
    
    
    
    
</div>


<div align="center"><img src="<?=base_url()?>public/images/upb_logo_transparent.png"  alt=""></div>