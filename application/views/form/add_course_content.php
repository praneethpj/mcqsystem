<?php if ($message) echo $message; ?>
<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';?>   
<!-- block -->
<div class="block">
    <div class="navbar block-inner block-header">
        <div class="row">
            <p class="text-muted">
                <span class=""><?php echo 'Course Title: '.$course_title; ?></span>
                <span class="pull-right"> </span>
            </p>
        </div>
    </div><br/>
    <div class="block-content">
    <?=form_open_multipart(base_url('index.php/course/add_content'), 'id="upload-form" role="form" class="form-horizontal"'); ?>
    <div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xs-offset-1 col-xs-10">
                <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            </div>
        </div>

        <div id="hidden_fields"></div>
        <div class="row">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <input type="hidden" name="course_title" value="<?php echo $course_title; ?>">
            <input type="hidden" name="section" value="<?php echo $section_id; ?>">
            <?php
            $option = array();
            $option[''] = 'Select Section';
            foreach ($sections as $section) {
                $option[$section->section_id] = $section->section_name.': '.$section->section_title;
            }
            ?>

            <?php if (!isset($section_id) || $section_id == '') { ?>
            <div class="form-group">
                <label for="section" class="col-sm-offset-0 col-md-2 col-xs-offset-1 col-xs-3 control-label mobile">Select section:</label>
                <div class="col-sm-8 col-xs-7 col-mb">
                    <?php echo form_dropdown('section', $option,'', 'id="section" class="form-control" required') ?>
                </div>
            </div>
            <?php } ?>
            <div class="form-group">
                <label for="" class="col-sm-offset-0 col-md-2 col-xs-offset-1 col-xs-3 control-label mobile">Title: </label>
                <div class="col-sm-8 col-xs-7 col-mb">
                  <?php 
                    $data = array(
                        'name'        => 'video_title',
                        'placeholder' => 'Title',
                        'value'       => '',
                        'rows'        => '2',
                        'class'       => 'form-control textarea-wysihtml5',
                        'required' => 'required',
                    ); ?>
                    <?php echo form_textarea($data) ?>
                </div>
            </div>
            <div class="form-group" id="media-choose">
                <label for="upload-media-file" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Content Type: </label>
                <div class="col-lg-5 col-sm-8 col-xs-7 col-mb">
                    <a href="#" class="btn btn-default" id='upload-media-file'>Choose</a>
                </div>
            </div>
            <div id="media-option"></div>
            <div id="media-field"></div>
            <div class="form-group">
                <label for="" class="col-sm-offset-0 col-md-2 col-xs-offset-1 col-xs-3 control-label mobile">Free: </label>
                <div class="col-sm-8 col-xs-7 col-mb">
                  <?php 
                    $data = array(
                        'name'        => 'free',
                        'id'          => 'free',
                        'value'       => 'free',
                        // 'checked'     => TRUE,
                        'style'       => 'margin:10px',
                        ); ?>
                    <?php echo form_checkbox($data); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-8 col-xs-7 col-sm-offset-2 col-xs-offset-0">
                    <div id="progressBar" style="display:none;"><img src="<?=base_url('assets/images/progress.gif')?>" /></div>
                </div>
            </div>

            <br/><hr/>
            <div class="row">
                <div class="col-xs-offset-1 col-xs-11 col-sm-offset-2 col-md-8">
                    <button type="submit" onclick="$('#progressBar').show();" class="btn btn-primary col-xs-5 col-sm-3"> <i class="glyphicon glyphicon-ok"></i> Save and Add More</button>
                    <button type="submit" onclick="$('#progressBar').show();" class="btn btn-info col-xs-offset-1" name="done" value="done"><i class="glyphicon glyphicon-saved"></i> Save and Done</button>
                </div>
            </div>
            <br/>
            <?=form_close();?>
        </div>
    </div>
    </div>
    </div>
</div>
<!-- Dynamic media file upload Script-->
<script type="text/javascript">
$('#upload-media-file').click(function(){
    var type = '<div class="form-group">'
                +'<label for="media_type" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Media Type: </label>'
                +'<div class="col-lg-5 col-sm-8 col-xs-7 col-mb" style="margin-top: 5px;">'
                        +'<input type="radio" value="file" name="media_type" required="required" onclick="add_media_field(this.value)"> <span>File </span>&nbsp;&nbsp;&nbsp;&nbsp;'
                        +'<input type="radio" value="external_link" name="media_type" required="required" onclick="add_media_field(this.value)"> <span>External link </span>&nbsp;&nbsp;&nbsp;&nbsp;'
                        +'<input type="radio" value="video" name="media_type" required="required" onclick="add_media_field(this.value)"> <span>Video </span>&nbsp;&nbsp;&nbsp;&nbsp;'
                        +'<input type="radio" value="youtube" name="media_type" required="required" onclick="add_media_field(this.value)"> <span>YouTube </span>'
                +'</div>'
            +'</div>';
    $('#media-choose').hide();
    $('#media-option').append(type);
})

//Add media fields
function add_media_field(val) {
    var field = '<div class="form-group">'
                +'<label for="media_field" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Add Content: </label>'
                +'<div class="col-lg-5 col-sm-8 col-xs-7 col-mb"><input type="hidden" name="media_type" value="'+val+'">';
    if (val == 'video') {
            var types = 'mp4 | webm | ogg | flv | avi';
    };

    switch(val){
        case 'external_link':
        case 'youtube':
            field += '<input type="text" class="form-control" name="media">';
            break;
        case 'file':
            field += '<input type="file" id="media" name="media" style="margin-top:8px;">';
            break;
        case 'video':
            field += '<input type="file" id="media" name="media" style="margin-top:8px;">';
            field += '<p class="help-block"><i class="glyphicon glyphicon-warning-sign"></i> Allowed types = '+ types +'.</p>';
            break;
    }
    field +='</div></div>';

    $('#media-option').hide();
    $('#media-field').append(field);
}
</script>
<?php include 'application/views/plugin_scripts/bootstrap-wysihtml5.php';?>
