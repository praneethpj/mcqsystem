<style type="text/css">
.pagination > .warning > a, 
.pagination > .warning > span, 
.pagination > .warning > a:hover, 
.pagination > .warning > span:hover, 
.pagination > .warning > a:focus, 
.pagination > .warning > span:focus {
    z-index: 2;
    color: #999;
    background-color: #f2dede;
    border-color: #dddddd;
}
.pagination > li > a, .pagination > li > span {
    min-width: 60px !important;
}
</style>

<?php
    $num_of_ques = count($questions);
    $count = 0;
        // echo "<pre/>"; print_r($questions); //exit();
?>
<section id="start_exam">
    <div class="container">
        <div class="box">
            <div class="row">
                <div class="col-md-10 col-sm-12 col-md-offset-1 col-sm-offset-0">
                    <div id="note">
                        <noscript><div class="alert alert-danger">Your browser does not support JavaScript or JavaScript is disabled! Please use JavaScript enabled browser to take this exam.</div></noscript>
                        <?php if ($message) echo $message; ?>
                    </div>
                    <h3># <?= $mock->title_name ?></h3>
                    <hr>
                    <form id="anserForm" action="<?= base_url('index.php/exam_control'); ?>" method="post">
                        <div class="question_content">
                            <input type="hidden" name="exam_id" value="<?= $mock->title_id; ?>">
                            <input type="hidden" name="token" value="<?= time(); ?>">

                            <div id="Carousel" class="carousel slide col-md-12" data-interval="false" data-wrap="false" style="margin-bottom: 30px;">

                                <div class="carousel-inner">
                                    <?php 
                                    foreach ($questions as $ques):
                                        $type = ($ques->option_type == 'Radio') ? 'radio' : 'checkbox'; ?>
                                        <div class="item <?= ($count === 0) ? 'active' : '' ?>">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p>Question <?=$count+1 . ' of ' . $num_of_ques; ?> 
                                                        <span class="pull-right">Time Remaining: 
                                                            <span class="time-duration"></span>
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3 style="min-height: 2.2em;"><?= $ques->question ?></h3>

                                                    <?php if (!empty($ques->media_type) AND ($ques->media_type != '')  AND ($ques->media_link != '')) {
                                                        switch ($ques->media_type) {
                                                            case 'youtube':
                                                                parse_str(parse_url($ques->media_link, PHP_URL_QUERY));
                                                                echo '<div class="embed-responsive embed-responsive-16by9">';
                                                                echo '<iframe class="embed-responsive-item" src="//www.youtube.com/embed/'.$v.'" frameborder="0"></iframe>';
                                                                echo "</div>";
                                                                break;
                                                            case 'audio':
                                                                $link = '<audio controls>';
                                                                  $link .= '<source src="'.base_url("question-media/".$ques->media_link).'" type="audio/mpeg">';
                                                                  $link .= '<source src="'.base_url("question-media/".$ques->media_link).'" type="audio/ogg">';
                                                                  $link .= '<source src="'.base_url("question-media/".$ques->media_link).'" type="audio/wav">';
                                                                $link .= '</audio>';
                                                                echo $link;
                                                                break;
                                                            case 'video':
                                                                $link = '<p><video width="600" height="400" controls>';
                                                                  $link .= '<source src="'.base_url("question-media/".$ques->media_link).'" type="video/mp4">';
                                                                  $link .= '<source src="'.base_url("question-media/".$ques->media_link).'" type="video/ogg">';
                                                                  $link .= '<source src="'.base_url("question-media/".$ques->media_link).'" type="video/webm">';
                                                                $link .= '</audio></p>';
                                                                echo $link;
                                                                break;
                                                            case 'image':
                                                                echo '<img src="'.base_url("question-media/".$ques->media_link).'" alt="image" height="auto" width="100%">';
                                                                break;                                    
                                                            default:
                                                                break;
                                                        }
                                                        echo "<br/><br/>";
                                                    }
                                                    ?>
                                                    <div id="question-<?=$count;?>">
                                                    <?php
                                                    foreach ($answers[$ques->ques_id][0] as $ans) { ?>
                                                        <div class="<?= $type ?>" style="margin-left: 23px; margin-top:10px;">
                                                            <label><input type="<?= $type ?>" name="ans[<?= $ques->ques_id; ?>]<?= ($type == 'checkbox') ? '[]' : '' ?>" value="<?=$ans->ans_id; ?>"> 
                                                                <?=form_prep($ans->answer); ?>
                                                            </label>
                                                        </div>
                                                    <?php 
                                                    } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $count++;
                                    endforeach;
                                    ?>
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class=" me-control-btn">
                                    <a class="btn btn-lg btn-info col-xs-5 col-xs-offset-0 me-prev" href="#Carousel" disabled> &laquo; Prev<span class="hidden-xxs">ious Question</span></a>
                                    <a class="btn btn-lg btn-info col-xs-5 col-xs-offset-2 me-next" href="#Carousel" > Next <span class="hidden-xxs">Question</span> &raquo; </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <p id="submit_button" style="margin: 30px 15px;"></p>
                        </div>

                        <!-- LINKED NAV -->
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <ol class="carousel-linked-nav pagination">
                                    <?php for($p_i = 0; $p_i < $num_of_ques; $p_i++){ ?>
                                        <li class="<?=$p_i == 0 ? "active" : ""; ?>">
                                            <a href="#<?=$p_i;?>"><?=$p_i+1;?></a>
                                        </li>
                                    <?php } ?>
                                </ol>
                            </div>                   
                        </div>                   

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script language="JavaScript"><!--
javascript:window.history.forward(1);
//--></script>
<script type="text/javascript">
$(document).ready(function() {

    // Set Time
    var count = <?= ($duration) ?>;
    var h, m, s, newTime;

    var counter = setInterval(timer, 1000);
    function timer() {
        count = count - 1;
        if (count < 0) {
            clearInterval(counter);
            return;
        }
        h = Math.floor(count / 3600);
        m = Math.floor(count / 60) - (h * 60);
        s = count % 60;
        if (m.toString().length == 1)
            m = '0' + m;
        if (s.toString().length == 1)
            s = '0' + s;
        if (h) {
            if (h.toString().length == 1)
                h = '0' + h;
            newTime = '<strong>' + h + ':' + m + ':' + s + '</strong> <small class="text-muted">Hours</small>';
        } else {
            newTime = '<strong>' + m + ':' + s + '</strong> <small class="text-muted">Minutes</small>';
        }
       
        //Update timer cookie
        var now = new Date();
        var time = now.getTime();
        time += count * 1000;
        now.setTime(time);
        document.cookie="ExamTimeDuration="+count+"; expires="+now.toUTCString()+"; path=/";
        
        //Update time to HTML
        $('.time-duration').html(newTime);
    }

    var idx = 0;
    
    // Coltrol Buttons    
    var submit_btn = '<button type="submit" onclick="{return submit_validate();}" class="btn btn-lg btn-success col-xs-12" > <i class="fa fa-check-square-o"></i> Submit <span class="hidden-xxs">your answers </span></a>';

    var num_of_ques = "<?php echo $num_of_ques; ?>";

    $('.me-next').click(function() 
    {
        $("#Carousel").carousel("next");

        chk_answers(idx);

        idx++;

        rmv_warning(idx);

        enable_prev_btn();

        set_active(idx);

        if (idx >= num_of_ques-1)
            disable_next_btn();

    });
    
    $('.me-prev').click(function() 
    {
        $("#Carousel").carousel("prev");

        chk_answers(idx);

        idx--;

        rmv_warning(idx);

        enable_next_btn();

        set_active(idx);

        if (idx <= 0)
            disable_prev_btn();

    });


    /* SLIDE ON CLICK */ 
    $('.carousel-linked-nav > li > a').click(function() {

        // grab href, remove pound sign, convert to number
        idx = Number( $(this).attr('href').substring(1) );

        for (var i = 0; i < num_of_ques; i++) {
            chk_answers(i);
        }
        // console.log(idx);

        // slide to number index
        $('#Carousel').carousel(idx);

        // remove current active class
        $('.carousel-linked-nav .active').removeClass('active');

        // remove current warning class
        rmv_warning(idx);

        // add active class to just clicked on item
        $(this).parent().addClass('active');

        if (idx == 0){
            disable_prev_btn();
            enable_next_btn();
        } else if (idx >= num_of_ques-1){
            disable_next_btn();
            enable_prev_btn();
        }else{
            enable_next_btn();
            enable_prev_btn();
        }

        // don't follow the link
        return false;
    });

    function set_active(idx)
    {
        // remove active class
        $('.carousel-linked-nav .active').removeClass('active');

        // select currently active item and add active class
        $('.carousel-linked-nav li:eq(' + idx + ')').addClass('active');        
    }

    function chk_answers(index)
    {
        if(index < 0 ) return false;

        var checkedElement = $('#question-'+index).children().children();
        var chk = false;

        checkedElement.children('input').each(function (i, node) {

            if (node.checked){
                chk = true;
                return;
            }
        });

        if(chk){
            rmv_warning(index);
        }else{
            add_warning(index);
        }

        return false;
    }

    // add current warning class
    function add_warning(index)
    {
        $('.carousel-linked-nav li:eq(' + index + ')').addClass('warning');         
    }

    // remove current warning class
    function rmv_warning(index)
    {
        $('.carousel-linked-nav li:eq(' + index + ')').removeClass('warning');         
    }

    function disable_next_btn()
    {
        $('.me-next').attr('disabled', 'disabled');      //disable Nest button for last question.
        show_submit_btn();
    }

    function enable_next_btn()
    {
        $('.me-next').removeAttr('disabled');
    }

    function disable_prev_btn()
    {
        $('.me-prev').attr('disabled', 'disabled');      //disable Nest button for last question.
    }

    function enable_prev_btn()
    {
        $('.me-prev').removeAttr('disabled');
    }

    function show_submit_btn()
    {
        //Check if the submit button already placed add if not.
        if (!$("#submit_button > button").length)
            $("#submit_button").append(submit_btn);
    }

    //Sumbit after time out
    var timeout = <?= ($duration * 1000) ?>;
    setTimeout(function() {
        alert('TIME OUT!');
        $('#anserForm').submit();
    }, timeout);


    function submit_validate(form) {

        // validation code here ...

        console.log(form);
        var valid = false;

        if(!valid) {
            alert('Please correct the errors in the form!');
            return false;
        }
        else {
            return confirm('Do you really want to submit the form?');
        }
    }

});

</script>