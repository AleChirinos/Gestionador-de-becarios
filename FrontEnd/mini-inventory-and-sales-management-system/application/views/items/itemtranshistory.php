<?php defined('BASEPATH') OR exit('') ?>

<!--An item's transactions history--->
<div class="col-sm-4">
    <div class="row">
        <div class="col-sm-12 form-group-sm form-inline">
            <div class="col-sm-4">
                Mostrar
                <select id="itemPerPage" class="form-control">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
            </div>
            <div class="col-sm-4">
                <select id="sortItems" class="form-control">
                    <option value="">Ordenar por</option>
                    <option value="code-asc">Codigo del elemento</option>
                </select>
            </div>
            <div class="col-sm-4">
                <input type="search" id="itemSearch" class="form-control" placeholder="Search Items">
            </div>
        </div>
    </div>
    <br>
    
    <!--Row of item's transactions -->
    <div class="row">
        <div class="col-sm-12" id='itemTransHistoryTable'>
            
        </div>
    </div>
</div>
<!--End of an item's transactions history--->