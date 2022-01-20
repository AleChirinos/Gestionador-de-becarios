<?php
defined('BASEPATH') OR exit('');
?>

<div class="row latestStuffs">
    <div class="col-sm-4">
        <div class="panel panel-info">
            <div class="panel-body latestStuffsBody" style="background-color: #5cb85c">
                <div class="pull-left"><i class="fa fa-exchange"></i></div>
                <div class="pull-right">
                    
                    <div class="latestStuffsText">Total De Elementos Vendidos</div>
                </div>
            </div>
            <div class="panel-footer text-center" style="color:#5cb85c">Número Total De Elementos Vendidos</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="panel panel-info">
            <div class="panel-body latestStuffsBody" style="background-color: #f0ad4e">
                <div class="pull-left"><i class="fa fa-tasks"></i></div>
                <div class="pull-right">

                    <div class="latestStuffsText pull-right">Total de Transacciones</div>
                </div>
            </div>
            <div class="panel-footer text-center" style="color:#f0ad4e">Número Total de Transacciones</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="panel panel-info">
            <div class="panel-body latestStuffsBody" style="background-color: #337ab7">
                <div class="pull-left"><i class="fa fa-shopping-cart"></i></div>
                <div class="pull-right">
     
                    <div class="latestStuffsText pull-right">Elementos en Inventario</div>
                </div>
            </div>
            <div class="panel-footer text-center" style="color:#337ab7">Número de Elementos en Inventario</div>
        </div>
    </div>
</div>

<div align="center"><img src="<?=base_url()?>public/images/ema.png"  alt=""></div>

<script src="<?=base_url('public/js/chart.js'); ?>"></script>
<script src="<?=base_url('public/js/dashboard.js')?>"></script>