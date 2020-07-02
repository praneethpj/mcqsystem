<!-- Dynamic Form Script-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

$(function(){
   
    function savedetails(){
    
}


    $('#questions').on('submit', function(e){
      e.preventDefault();
      var form = $('#questions')[0];

      var getUrl = window.location;
var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

  $.ajax( {
    url: baseUrl+'/index.php/admin_control/add_question/1',
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false
    } );
    });
});    
</script>
<script type="text/javascript">
    //Add basic 4 fields initially
    var i = 5, s;
    function add_form(val) {
      //  alert(val);
        i = 5;
        var opt = '<div class=row><div class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8">';
            opt += '<p class="text-primary"><i class="glyphicon glyphicon-flash"></i> Select correct answer/s from left redio/checkbox options.</p>';
            opt += '</div></div>';

        for (q = 1; q <= 4; q++) {
            opt += '<div class="form-group">';
            opt += '<label for="options[' + q + ']" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Option ' + q + ': </label>';
            opt += '<div class="col-lg-5 col-sm-8 col-xs-7 col-mb">';
            opt += '<div class="input-group">';
            opt += '<span class="input-group-addon">';
            if (val == 'Radio') {
                opt += '<input type="' + val + '" name="ans" onclick="put_right_ans(' + q + ')" required="required">  <span class="invisible-on-sm"> Correct Ans.</span>';
            } else if (val == 'Checkbox') {
                opt += '<input type="' + val + '" name="right_ans[' + q + ']">  <span class="invisible-on-sm"> Correct Ans.</span>';
            }
            opt += '</span>';
            if (q <= 2) {
                opt += '<input name="options[' + q + ']" class="form-control" type="text" required="required">';
            }
            if (q > 2) {
                opt += '<input name="options[' + q + ']" class="form-control" type="text">';
            }
            opt += '</div></div></div>';
        }
        opt += '<div id="add_more_field-5"></div>';
        opt += '<div class="form-group">';
        opt += '<label class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">&nbsp;</label><div class="col-lg-5 col-sm-8 col-xs-7 col-mb">';
        opt += '<button type="button" class="btn btn-info" id="add_btn" onclick="add_field()"><icon class="icon-plus"></icon> Add More Options</button>';
        opt += '</div></div>';
        document.getElementById('options').innerHTML = opt;
    }

    //Add more fields
    function add_field() {
        var type;
        if (document.getElementById('radio1').checked) {
            type = 'radio';
        } else if (document.getElementById('checkbox1').checked) {
            type = 'checkbox';
        }
        if (i <= 8) {
            var str = '<div class="form-group"><label for="options[' + i + ']" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobil">Option ' + i + ': </label>';
            str += '<div class="col-lg-5 col-sm-8 col-xs-7 col-mb">';
            str += '<div class="input-group">';
            str += '<span class="input-group-addon">';
            if (type === 'radio') {
                str += '<input type="' + type + '" name="ans" onclick="put_right_ans(' + i + ')" required="required">  <span class="invisible-on-sm"> Correct Ans.</span>';
            } else if (type === 'checkbox') {
                str += '<input type="' + type + '" name="right_ans[' + i + ']">  <span class="invisible-on-sm"> Correct Ans.</span>';
            }    
            str += '</span>';
            str += '<input name="options[' + i + ']" class="form-control" type="text">';
            str += '</div></div></div><div id="add_more_field-' + (i + 1) + '"></div>';

            document.getElementById('add_more_field-' + i).innerHTML = str;
            i++;
        } else {
            alert('You added maximum number of options!');
        }
    }

    //Pick the righ answers and set the value to hidden field
    function put_right_ans(val) {
        var ryt = '<input type="hidden" name="right_ans[' + val + ']" value="on">';
        document.getElementById('hidden_fields').innerHTML = ryt;
    }
    $(document).ready(function(){
     
        $("questions").bind('submit', function (e) {
     alert("ss");
});



});

</script>

<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';?>   
<form action="deletprofil.php" id="form_id" method="post">
  <div data-role="controlgroup" data-filter="true" data-input="#filterControlgroup-input">
  <button type="submit" value="1" class="ui-btn ui-shadow ui-corner-all ui-icon-delete ui-btn-icon-right" data-icon="delete" aria-disabled="false">Anlegen</button>
  <button type="submit" value="2" class="ui-btn ui-shadow ui-corner-all ui-icon-delete ui-btn-icon-right" data-icon="delete" aria-disabled="false">Bnlegen</button>
  </div>
</form> 
<!-- block -->
<div class="block">
    <div class="navbar block-inner block-header">
        <div class="row">
            <p class="text-muted">
                <span class=""><?='Exam: '.$exam->title_name; ?></span>
                <span class="pull-right" style="margin-right: 15px;"><?='Question No: '.$question_no; ?></span>
            </p>
        </div>
    </div><br/>
    <div class="block-content">
    <!-- <form id="questions">

    <submit   class="btn btn-info col-xs-offset-1" name="submit" id="submit" value="done"> Save and Done</submit>
    </form> -->
    
   <form role="form" class="form-horizontal" method="post" role="form" name="questions"  id="questions">
    <div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xs-offset-1 col-xs-10">
                <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            </div>
        </div>

        <div id="hidden_fields"></div>
        <div class="row">
                <?php
            $option = array();
            $option[''] = 'Select Subject';
            foreach ($subjects as $category) {
                if ($category->active) {
                    $option[$category->subject_id] = $category->subject_title;
                }
            }
            
            ?>
            <div class="form-group">
                 <label for="question"  class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Category: </label>
                 <div class="col-lg-5 col-sm-8 col-xs-7 col-mb">
                 
                 <select class="form-control" name="questioncategory">
                        <option id="0">Select Category</option>
                         <option id="1">Advance Level</option>
                         <option id="2">Ordinary Level</option>
                         <option id="3">Grade 5 Exam</option>
                     </select>
                 </div>
            </div>
            <div class="form-group">
                 <label for="question" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Subject: </label>
                 <div class="col-lg-5 col-sm-8 col-xs-7 col-mb">
                        <?php echo form_dropdown('subject_id', $option,'', 'id="subject_id" class="form-control"') ?>
                 </div>
            </div>
              <div class="form-group">
                 <label for="question" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Term: </label>
                 <div class="col-lg-5 col-sm-8 col-xs-7 col-mb">
                     <select class="form-control" name="term">
                     <option id="0">Select Category</option>
                         <option id="1">1st Term</option>
                         <option id="2">2nd Term</option>
                         <option id="3">3rd Term</option>
                     </select>
                 </div>
            </div>
             <div class="form-group">
                 <label for="question" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Medium: </label>
                 <div class="col-lg-5 col-sm-8 col-xs-7 col-mb">
                     <select class="form-control" name="medium">
                     <option id="0">Select Medium</option>
                         <option id="1">Sinhala</option>
                         <option id="2">Tamil</option>
                         <option id="3">English</option>
                     </select>
                 </div>
            </div>
                <div class="form-group">
                 <label for="question" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Question Type: </label>
                 <div class="col-lg-5 col-sm-8 col-xs-7 col-mb">
                     <select class="form-control" name="complexiety">
                     <option id="0">Select Complexiety</option>
                         <option id="1">Easy</option>
                         <option id="2">Medium</option>
                         <option id="3">Hard</option>
                         <option id="4">Average</option>
                     </select>
                 </div>
            </div>
        </div>
        <div class="row">
            <!-- <div class="form-group">
                <label for="question" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Question: </label>
                <div class="col-lg-5 col-sm-8 col-xs-7 col-mb">
                  <?php 
                    $data = array(
                        'name'        => 'question',
                        'placeholder' => 'Question Title',
                        'value'       => '',
                        'rows'        => '2',
                        'class'       => 'form-control textarea-wysihtml5',
                        'required' => 'required',
                    ); ?>
                    <?=form_textarea($data) ?>
                </div>
            </div> -->
            <!-- <div class="form-group" id="media-choose">
                <label for="upload-media-file" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Upload Media File: </label>
                <div class="col-lg-5 col-sm-8 col-xs-7 col-mb">
                    <a href="#" class="btn btn-default" id='upload-media-file'>Choose</a>
                </div>
            </div> -->
            <div id="media-option"></div>
            <div id="media-field"></div>

            <!-- <div class="form-group">
                <label for="ans_type" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Answer Type: </label>
                <div class="col-lg-5 col-sm-8 col-xs-7 col-mb">
                    <label class="radio-inline">
                        <input type="radio" id="radio1" name="ans_type" required="required" value="Radio" onclick="add_form(this.value)"> <span>Radio </span>&nbsp;&nbsp;&nbsp;&nbsp;
                    </label>
                    <label class="radio-inline">
                        <input type="radio" id="checkbox1" name="ans_type" required="required" value="Checkbox" onclick="add_form(this.value)"> <span>Check Box</span>
                    </label>
                </div>
            </div><br/> -->
            <div id="options">
            </div>
            <div class="form-group">
                <div class="col-sm-8 col-xs-7 col-sm-offset-2 col-xs-offset-0">
                    <div id="progressBar" style="display:none;"><br/><img src="<?=base_url('assets/images/progress.gif')?>" /></div>
                </div>
            </div>
            <br/><hr/>
            <div class="row">
                <div class="col-xs-offset-1 col-xs-11 col-sm-offset-2 col-md-8">
                    <button type="submit" onclick="$('#progressBar').show();" class="btn btn-primary col-xs-5 col-sm-3"> <i class="glyphicon glyphicon-ok"></i> Reset</button>
                    <button type="submit" class="btn btn-info col-xs-offset-1"  id="submit"><a href="<?= base_url('index.php/admin_control/add_media')?>"><i class="glyphicon glyphicon-saved"></i> Next</button>
                </div>
            </div><br/>
            </form>
        </div>
    </div>
    </div>
    </div>
</div>
<?php include 'application/views/plugin_scripts/bootstrap-wysihtml5.php';?>

<!-- Dynamic media file upload Script-->
<script type="text/javascript">
$('#upload-media-file').click(function(){
    var type = '<div class="form-group">'
                +'<label for="media_type" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Media Type: </label>'
                +'<div class="col-lg-5 col-sm-8 col-xs-7 col-mb" style="margin-top: 5px;">'
                        +'<input type="radio" value="youtube" name="media_type" required="required" onclick="add_media_field(this.value)"> <span>YouTube </span>&nbsp;&nbsp;&nbsp;&nbsp;'
                        +'<input type="radio" value="video" name="media_type" required="required" onclick="add_media_field(this.value)"> <span>Video </span>&nbsp;&nbsp;&nbsp;&nbsp;'
                        +'<input type="radio" value="image" name="media_type" required="required" onclick="add_media_field(this.value)"> <span>Image </span>&nbsp;&nbsp;&nbsp;&nbsp;'
                        +'<input type="radio" value="audio" name="media_type" required="required" onclick="add_media_field(this.value)"> <span>Audio </span>'
                +'</div>'
            +'</div>';
    $('#media-choose').hide();
    $('#media-option').append(type);
})

//Add media fields
function add_media_field(val) {
    var field = '<div class="form-group">'
                +'<label for="media_field" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Add Media: </label>'
                +'<div class="col-lg-5 col-sm-8 col-xs-7 col-mb"><input type="hidden" name="media_type" value="'+val+'">';
    if (val == 'video') {
            var types = 'mp4 | webm | ogg';
    }else if (val == 'audio') {
            var types = 'ogg | mp3 | wav';        
    }else if (val == 'image') {
            var types = 'gif | jpg | png';
    };

    switch(val){
        case 'youtube':
            field += '<input type="text" class="form-control" name="media">';
            break;
        case 'video':
        case 'image':
        case 'audio':
            field += '<input type="file" id="media" name="media" style="margin-top:8px;">';
            field += '<p class="help-block"><i class="glyphicon glyphicon-warning-sign"></i> Allowed types = '+ types +'.</p>';
            break;
    }
    field +='</div></div>';

    $('#media-option').hide();
    $('#media-field').append(field);
}
</script>
