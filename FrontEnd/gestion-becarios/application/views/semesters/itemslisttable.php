<?php defined('BASEPATH') OR exit('') ?>

<div class='col-sm-6'>
    <?= isset($range) && !empty($range) ? $range : ""; ?>
</div>

<div class='col-xs-12'>
    <div class="panel panel-primary">
        <!-- Default panel contents -->
        <div class="panel-heading">LISTA GESTIONES <?php echo " ". $this->session->admin_career;?></div>
        <?php if($allItems): ?>
        <div class="table table-responsive">
            <table class="table table-bordered table-striped" style="background-color: #f5f5f5">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>NOMBRE</th>
                        <th>CÓDIGO</th>
                        <th>ESCOGER GESTIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($allItems as $get): ?>
                    <?php if($this->session->admin_career ===$get->career):?>
                    <tr>
                        <input type="hidden" value="<?=$get->id?>" class="curItemId">
                        <th class="itemSN"><?=$sn?>.</th>
                        <td><span id="itemName-<?=$get->id?>"><?=$get->name?></span></td>
                        <td><span id="itemCode-<?=$get->id?>"><?=$get->career?></td>
                        <td class="text-center selectSemester text-success" id="<?=$this->session->admin_id?>-<?=$get->id?>-<?=$get->selected?>-<?=$get->career?>">
                            <?php if($get->selected != 0): ?>
                                <i class="fa fa-toggle-on pointer"></i>
                            <?php else: ?>
                                <i class="fa fa-toggle-off pointer"></i>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php $sn++; ?>
                    <?php endif; ?>
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
        <?= $links ?? "" ?>
    </ul>
</div>
