<?php //echo "<pre/>"; print_r($courses);print_r($sections);print_r($video); exit(); ?>
<style type="text/css">
    .list-group-item{
        cursor: move;
    }
    .ui-sortable-helper{
        padding: 10px 15px;
        height: 40px !important;
    }
    .ui-sortable-placeholder{
        height: 40px !important;
    }
    ul.inner-sortlist{
        list-style: none;
        padding-left: 0;
    }
    .inner-sortlist li{
        display: inline-block;
    }
</style>

<div id="note">
    <?php 
    if ($message) 
        echo $message; 

    if ($this->session->flashdata('message')) {
        echo $this->session->flashdata('message');
    }
    ?>
    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
</div>
<div class="block">  
    <div class="navbar block-inner block-header">
        <div class="row">
            <p class="text-muted">Course Title: <?=$courses->course_title; ?> 
            <?php 
            if ($courses->user_id == $this->session->userdata['user_id']) {
            ?>
                <a class="btn custom_navbar-btn btn-info pull-right col-sm-2" href="<?=base_url('index.php/course/add_section/'.$courses->course_id); ?>" data-toggle="modal"><i class="glyphicon glyphicon-plus-sign"></i>&nbsp; Add Section</a>
                <a class="btn custom_navbar-btn btn-primary pull-right col-sm-2" href="<?=base_url('index.php/course/add_content/'.$courses->course_id); ?>"><i class="glyphicon glyphicon-plus-sign"></i>&nbsp; Add Content</a>
            <?php 
            } ?>
            </p>
        </div>
    </div>
    <div class="block-content">
    <div class="row">
    <div class="col-sm-12">
        <ul id="sortlist" class="list-group">
        <?php foreach ($sections as $section) { ?>
        <li id="ID_<?=$section->section_id;?>" class="ui-state-default list-group-item">
            <ul class="inner-sortlist">
                <li style="width: 60%;"><a id="section_title-<?=$section->section_id;?>" ><?=$section->section_name.': '.$section->section_title; ?>
                </a></li>
                <li> 
                    <?=$this->db->where('course_id', $courses->course_id)->where('section_id', $section->section_id)->from('course_videos')->count_all_results(); ?> videos
                </li>                                
                <li class="pull-right">
                    <div class="btn-group pull-right">
                        <a href="<?= base_url('index.php/course/section_detail/' . $section->section_id); ?>"  class="btn btn-xs btn-success "><i class="glyphicon glyphicon-resize-small"></i><span class="invisible-on-sm"> View</span></a>
                        <a class="btn btn-xs btn-primary update"  data-update="<?=$section->section_id;?>" href="#update_section" data-toggle="modal"><i class="glyphicon glyphicon-edit"></i><span class="invisible-on-md">  Modify</span></a>
                        <a onclick="return delete_confirmation()" href = "<?=base_url('index.php/course/delete_section/' . $section->section_id); ?>" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i><span class="invisible-on-md">  Delete</span></a>
                    </div>
                </li>                                
            </ul>
        </li>
        <?php
        }
        ?>
        </ul>
        <a id="button" class="btn btn-sm btn-warning">Update orders</a> <i class="fa fa-warning"></i> You can reorder the view by dragging the list.
    </div>
    </div>
    </div>
</div><!--/span-->

<!-- Update Section Modal -->
<div class="modal fade" id="update_section" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="TRUE">  
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="TRUE">&times;</button>
        <h4 class="modal-title">Update Section</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open(base_url() . 'index.php/course/update_section','role="form" class="form-horizontal"'); ?>
          <input type="hidden" name="section_id" id="update_section_id" value="">
          <input type="hidden" name="course_id" value="<?=$courses->course_id;?>">
          <div class="form-group">
            <label for="section_name" class="col-xs-3 control-label">Section Name :</label>
            <div class="col-xs-8">
                <?php echo form_input('section_name', '', 'id="update_section_name" placeholder="Section name" class="form-control" required="required"') ?>
            </div>
          </div>
          <div class="form-group">
            <label for="section_title" class="col-xs-3 control-label">Section Title :</label>
            <div class="col-xs-8">
                <?php echo form_input('section_title', '', 'id="update_section_title" placeholder="Section title" class="form-control" required="required"') ?>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <?php echo form_submit('submit', 'Save', 'class="btn btn-primary"') ?>
        <?php echo form_close() ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- script for take the old value. -->
<script type="text/javascript">


//--------- SAVE THE ORDER
$('#button').click(function(event){
    //alert('me');
       var order = $("#sortlist").sortable("serialize");
       $('#message').html('Saving changes..');
       $.post("<?=base_url();?>index.php/course/save_order",order,function(theResponse){
         $('#message').html(theResponse);
         });
       event.preventDefault();
});

//------------------------------ COOKIE SESSION SAVES

$('.update').click(function() {
    var id = $(this).attr('data-update'); // Get the id
    var str = $.trim($('#section_title-'+id).html()); //Get the old value and trimed it
    var value = str.split(": ");

    $('#update_section_name').val(value[0]); // Set the old value intu the field
    $('#update_section_title').val(value[1]); // Set the old value intu the field
    $('#update_section_id').val(id); // Set section_id that will be updated
});
</script>


