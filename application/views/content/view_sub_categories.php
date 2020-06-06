<?php //echo "<pre/>"; print_r($sub_categories); exit(); ?>
<div id="note">
<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';?>   
</div>
<?php
$str = '[';
foreach ($categories as $value) {
    $str .= "{value:" . $value->category_id . ",text:'" . $value->category_name . " '},";
}
$str = substr($str, 0, -1);
$str .= "]";
?>
<div class="block"> 
    <div class="navbar block-inner block-header">
        <div class="row"><p class="text-muted">Category List </p></div>
    </div>
    <div class="block-content">
    <div class="row">
    <div class="col-sm-12">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatable" id="example">
            <thead>
                <tr>
                    <th>Sub-category Name</th>
                    <th class="hidden-xxs">Parent Category</th>
                    <th class="hidden-xxs">Have Exams</th>
                    <th class="hidden-xxs">Have Courses</th>
                    <th class="hidden-xxs">Status</th>
                    <th class="col-sm-3" style="width: 27%">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1;
            foreach ($sub_categories as $category) { ?>
                <tr class="<?=($i & 1) ? 'even' : 'odd'; ?>">
                    <td>
                        <a href="#" data-name="sub_cat_name" data-type="text" data-url="<?php echo base_url('index.php/admin_control/update_subcategory'); ?>" data-pk="<?=$category->id; ?>" class="data-modify-<?=$category->id; ?> no-style"><?=$category->sub_cat_name; ?></a>
                    </td>
                    <td class="hidden-xxs">
                        <a href="#" data-name="cat_id" data-type="select" data-url="<?php echo base_url('index.php/admin_control/update_subcategory'); ?>" data-source="<?= $str; ?>" data-value="<?= $category->cat_id; ?>" data-pk="<?= $category->id; ?>" class="data-modify-<?= $category->id; ?> no-style"><?php echo $category->category_name; ?></a>
                    </td>
                    <td class="hidden-xxs"><?php echo $mock_count[$category->id]; ?></td>
                    <td class="hidden-xxs"><?php echo $course_count[$category->id]; ?></td>
                    <td class="hidden-xxs"><?php echo $category->sub_cat_active == 1 ? 'Avtive':'Inactive'; ?></td>
                    <td class="col-sm-3" style="width: 27%">
                    <div class="btn-group">
                        <a class="btn btn-default btn-sm modify" name="modify-<?=$category->id; ?>" href = "#"><i class="glyphicon glyphicon-edit"></i><span class="invisible-on-md">  Modify</span></a>

                        <?php if ($category->sub_cat_active == 1) {?>
                            <a class="btn btn-default btn-sm" href = "<?php echo base_url('index.php/admin_control/mute_subcategory/' . $category->id); ?>"><i class="glyphicon glyphicon-off"></i><span class="invisible-on-md">  Deactivate </span></a>        
                        <?php }else { ?>
                            <a class="btn btn-default btn-sm" href = "<?php echo base_url('index.php/admin_control/activate_subcategory/' . $category->id); ?>"><i class="glyphicon glyphicon-check"></i><span class="invisible-on-md">  Activate </span></a>
                        <?php } ?>

                        <a onclick="return delete_confirmation()" href = "<?php echo base_url('index.php/admin_control/delete_subcategory/' . $category->id); ?>" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-trash"></i><span class="invisible-on-md">  Delete</span></a>
                    </div>
                    </td>
                </tr>
                <?php $i++;
            } ?>
            </tbody>
        </table> 
    </div>
    </div>
    </div>
</div><!--/span-->