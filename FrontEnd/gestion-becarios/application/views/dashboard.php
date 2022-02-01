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
            <!-- end of sort and co div-->
        </div>
    </div>
    
    <hr>
    
    <!-- row of adding new item form and items list table-->
    <div class="row">
        <div class="col-sm-12">
            <!--Form to add/update an item-->
            
            
            <!--- Item list div-->
            <div class="col-sm-12" id="becariosDashListDiv">
                <!-- Item list Table-->
                <div class="row">
                    <div class="col-sm-12" id="becarioDashListTable"></div>
                </div>
                <!--end of table-->
                
            </div>
            <!--- End of item list div-->

        </div>
    </div>
    <!-- End of row of adding new item form and items list table-->
</div>


<div align="center"><img src="<?=base_url()?>public/images/upb_logo_transparent.png"  alt=""></div>
<script src="<?=base_url('public/js/dashboard.js')?>"></script>