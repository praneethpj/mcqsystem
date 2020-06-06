<?php 
if ($this->session->userdata('Time_zone')) {
    date_default_timezone_set ($this->session->userdata('Time_zone'));
}

$user_info = $this->db->get_where('users', array('user_id' => $this->session->userdata('user_id')))->row();?>
<?php $purchased = $this->db->where('user_id', $this->session->userdata('user_id'))->where('pur_ref_id', $course->course_id)->get('puchase_history')->row();?>
<section id="exam_summery">
    <div class="container">
        <div class="box">
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1">
                    <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                    <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>        
                    <?=(isset($message)) ? $message : ''; ?>
                </div>
                <div class="col-md-3">
                    <div>
                    <?php if (file_exists("course-images/$course->course_id.png")) { ?>
                        <img class="course-summary-thumbnail" width="250" src="<?=base_url("course-images/$course->course_id.png"); ?>" alt="...">
                    <?php }else{ ?>
                        <img class="course-summary-thumbnail" width="250" src="<?=base_url('course-images/placeholder.png'); ?>" alt="...">
                    <?php } ?>
                    </div>
                    <div>
                        <h3>Category:</h3>
                        <h4><?=$course->category_name.' / '.$course->sub_cat_name; ?></h4>
                        <h3>Instructor: </h3>
                        <img style="height: 50px; width: 50px;" src="<?=base_url('user-avatar/avatar-placeholder.jpg')?>">
                        <span style="margin-left: 10px;"><?=$course->user_name?></span>
                    </div>
                </div>
                <div class="col-md-7">
                    <h1># <?=$course->course_title?></h1>

                    <p><?=$course->course_intro; ?></p>
                    <!--
                    <p>
                        <img src="<?=base_url('course-images/CPkGq6G.png')?>"/>
                        <img src="<?=base_url('course-images/CPkGq6G.png')?>"/>
                        <img src="<?=base_url('course-images/CPkGq6G.png')?>"/>
                        <img src="<?=base_url('course-images/CPkGq6G.png')?>"/>
                        <img src="<?=base_url('course-images/CPkGq6G.png')?>"/>
                        <?=$course->course_count_reviews;?> Reviews
                    </p><hr/>
                    -->

                    <p><?=$course->course_description; ?></p>
                    <div>
                        <h4>Course requirement</h4>
                        <div><?=$course->course_requirement; ?></div>
                        <h4>What am I going to get from this course?</h4>
                        <div><?=$course->what_i_get; ?></div>
                        <h4>What is the target audience?</h4>
                        <div><?=$course->target_audience; ?></div>
                    </div>
                    <br/>
                    <div>
                        <h4 style="text-align:center;">LESSONS</h4>
                        <div class="course-video-container">
                        <ul>
                        <?php $i = 1; 
                            $sections = $this->db->get_where('course_sections', array('course_id'=>$course->course_id))->result();
                            foreach ($sections as $value) { $j = 1;  ?>
                            <li class="chap-title"><b> <?=$value->section_name?> : </b> <h5><?=' '.$value->section_title;?> </h5></li>
                            <?php 
                                $videos = $this->db->where('section_id', $value->section_id)->order_by('orderList', 'asc')
                                    ->get('course_videos')
                                    ->result();
                                if ($videos) {
                                foreach ($videos as $video) { 
                                    $date = @date_create($video->created_at);
                            ?>

                                <li class="lec">
                                    <div class="lec-left">
                                        <span class="course-no"><?=$i.'.'.$j;?> </span>
                                    </div>
                                    <div class="lec-right">
                                        <div class="lec-url">
                                            <div class="lec-main fxac">
                                                <div class="lec-title">
                                                    <?php 
                                                    if (
                                                        ($video->preview_type == 'free') || 
                                                        (!$course->course_price) || 
                                                        $purchased || 
                                                        (
                                                            ($this->session->userdata('log')) && 
                                                            ($user_info->subscription_id) && 
                                                            ($user_info->subscription_end > now())
                                                        )
                                                        ) 
                                                    { 
                                                        if($video->content_type == 'external_link')
                                                        { ?>

                                                            <i class="glyphicon glyphicon-link"></i> <a href="<?=$video->youtube_link; ?>" target="_blank"><?=$video->video_title; ?></a>

                                                            <?php if (($video->preview_type == 'free') || ($video->preview_type == 'Free') || !$course->course_price) { ?>
                                                                <span class="label label-default pull-right">Free</span>
                                                            <?php } ?>

                                                            <span class="help-block small"><?='Type: ';?> <?php $splits = explode('.', $video->video_link);?> <?=end($splits)?:str_replace('_', ' ', $video->content_type); ?> <?=' | Added: '.date_format($date, 'M d, Y' ); ?> </span>

                                                        <?php }else if($video->content_type == 'youtube'){ ?>

                                                            <i class="glyphicon glyphicon-expand"></i> <a href="<?=$video->youtube_link; ?>" target="_blank"><?=$video->video_title; ?></a>

                                                            <?php if (($video->preview_type == 'free') || ($video->preview_type == 'Free') || !$course->course_price) { ?>
                                                                <span class="label label-default pull-right">Free</span>
                                                            <?php } ?>

                                                            <span class="help-block small"><?='Type: ';?> <?php $splits = explode('.', $video->video_link);?> <?=end($splits)?:str_replace('_', ' ', $video->content_type); ?> <?=' | Added: '.date_format($date, 'M d, Y' ); ?> </span>


                                                        <?php }else if($video->content_type == 'file')
                                                            { 
                                                            ?>

                                                            <i class="glyphicon glyphicon-download"></i> 
                                                                <a href="<?=base_url('course_videos/'.$video->course_id.'/'.$video->video_link); ?>" download><?=$video->video_title; ?></a>

                                                            <?php if (($video->preview_type == 'free') || ($video->preview_type == 'Free') || !$course->course_price) { ?>
                                                                <span class="label label-default pull-right">Free</span>
                                                            <?php } ?>

                                                            <span class="help-block small">Size: <?=number_format($video->file_size/1000000, 2) .'MB | Type: ';?> <?php $splits = explode('.', $video->video_link);?> <?=end($splits)?:str_replace('_', ' ', $video->content_type); ?> <?=' | Added: '.date_format($date, 'M d, Y' ); ?> </span>


                                                        <?php }else{ ?>
                                                
                                                        <a href="" class="videoplaylink" data-toggle="modal" data-target="#videoModal" data-video-url="<?=base_url('course_videos/'.$video->course_id.'/'.$video->video_link); ?>">
                                                            <i class="glyphicon glyphicon-expand"></i>

                                                            <?=$video->video_title; ?>

                                                            <?php if (($video->preview_type == 'free') || ($video->preview_type == 'Free') || !$course->course_price) { ?>
                                                                <span class="label label-default pull-right">Free</span>
                                                            <?php } ?>

                                                            <span class="help-block small"><?='Type: ';?> <?php $splits = explode('.', $video->video_link);?> <?=end($splits)?:str_replace('_', ' ', $video->content_type); ?> <?=' | Added: '.date_format($date, 'M d, Y' ); ?> </span>

                                                        </a>
                                                        <?php } ?>

                                                    <?php 
                                                    }else{ ?>
                                                            <i class="glyphicon glyphicon-expand"></i>
                                                            <?=$video->video_title; ?>

                                                            <span class="help-block small"><?='Type: ';?> <?php $splits = explode('.', $video->video_link);?> <?=end($splits)?:str_replace('_', ' ', $video->content_type); ?> <?=' | Added: '.date_format($date, 'M d, Y' ); ?> </span>
                                                    <?php } ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                             <?php $j++; }
                             $i++; 
                             }else{ ?>
                                <li class="lec">
                                    <div class="lec-left">
                                        <span class="course-no"></span>
                                    </div>
                                    <div class="lec-right">
                                        <div class="lec-url">
                                            <div class="lec-main fxac">
                                                <div class="lec-title">
                                                    No video added yet!
                                                </div>
                                                <div class="lec-includes">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php }
                            }
                            $exams = $this->db->where('course_id',$course->course_id)->get('exam_title')->result();
                            if ($exams) { $k = 1; ?>
                                <li class="chap-title"><b> Associated Exams </b></li>
                                <?php 
                                foreach ($exams as $exam) { ?>
                                    <li class="lec">
                                        <div class="lec-left">
                                            <span class="course-no"><?=$k;?> </span>
                                        </div>
                                        <div class="lec-right">
                                            <div class="lec-url">
                                                <div class="lec-main fxac">
                                                    <div class="lec-title">
                                                        <?php if ($exam->public || $purchased || (($this->session->userdata('log')) && ($user_info->subscription_id) && ($user_info->subscription_end > now()))) { ?>
                                                            <a href="<?=base_url('index.php/exam_control/view_exam_summery/'.$exam->title_id)?>">
                                                                <?=$exam->title_name; ?>
                                                            </a>
                                                        <?php }else{ 
                                                            echo $exam->title_name;
                                                            } ?>
                                                        <?php if ($exam->exam_price == 0) { ?>
                                                            <span class="label label-default pull-right">Free</span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php $k++; 
                                }
                            } ?>
                        </ul>
                            
                        </div>
                    </div>

                </div>
                <div class="col-md-2">
                    <div class="pb-t">
                        <div class="pb-p">
                            <span class="pb-pr ">
                                <?php if ($course->course_price) {
                                    echo $currency_symbol.$course->course_price;                     
                                }else{
                                    echo "Free";
                                } ?>
                            </span>
                        </div> 
                        <div class="pb-ta">

                            <?php 
                            if ($this->session->userdata('log')) { 
                                if ($course->course_price && (!$user_info->subscription_id && ($user_info->subscription_end < now()))){
                            ?>

                                <a href="<?=base_url('index.php/course/enroll/'.$course->course_id) ?>" class="btn btn-success">Pay with PayPal</a> 

                            <?php
                                }
                            }else{
                            ?>
                                <a href="<?=base_url('index.php/course/enroll/'.$course->course_id);?>" class="btn btn-primary"> Enroll Now </a>
                            <?php 
                            } ?>


                        </div>
                    </div>

                    <div class="big-gap"></div>
                    
                    <div class="fb-share-button" 
                        data-href="<?=base_url('index.php/course/course_summary/'.$course->course_id)?>" 
                        data-layout="button_count" data-size="large" >
                    </div>

                    <div style="background-color: #eee;">
                        <h4 class="related_courses">Related courses: </h4>
                    </div>
                        <?php 
                            $related_courses = $this->db->get_where('courses', array('category_id'=>$course->id))->result();
                            foreach ($related_courses as $value) { 
                                if ($value->course_id != $course->course_id) { ?>
                                    <div class="thumbnail">
                                    <a href="<?=base_url('index.php/course/course_summary/'.$value->course_id); ?>">

                                        <?php if (file_exists("course-images/$value->course_id.png")) { ?>
                                            <img class="course-summary-thumbnail" width="250" src="<?=base_url("course-images/$value->course_id.png"); ?>" alt="...">
                                        <?php }else{ ?>
                                            <img class="course-summary-thumbnail" width="250" src="<?=base_url('course-images/placeholder.png'); ?>" alt="...">
                                        <?php } ?>

                                        <div class="caption">
                                            <h4><?=$value->course_title;?></h4>
                                        </div>
                                    </a>
                                    </div>                
                        <?php   }
                            } ?>
                </div>

            </div>
        </div> 
    </div>
</section><!--/#pricing-->
<script src="<?=base_url('assets/js/video.js') ?>"></script>

<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 5px;">
                <button type="button" id="modalClose" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4  class="modal-title" id="myModalLabel">
                    <span id="modalVideotitle"></span>
                </h4>
            </div>
            <div class="modal-body " style="padding: 0px;">
                <div class="embed-responsive embed-responsive-16by9">
                    <video class="videoPlayer embed-responsive-item" height="" controls autoplay src="" type="video/x-flv"/></video>
                </div>
            </div>
            <br/>
            <?php if(false){?>
            <div class="" style=" text-align: center;">
            <div class="btn-group" role="group">
                <button type="button" id="prevSec" class="btn btn-default"> <i class="glyphicon glyphicon-fast-backward"  data-toggle="tooltip" data-placement="top" title="Previous Section "></i> </button>
                <button type="button" id="prevVideo" class="btn btn-default"> <i class="glyphicon glyphicon-step-backward"  data-toggle="tooltip" data-placement="top" title="Previous Video "></i> </button>
                <button type="button" id="nextVideo" class="btn btn-default"> <i class="glyphicon glyphicon-step-forward"  data-toggle="tooltip" data-placement="top" title="Next Video "></i> </button>
                <button type="button" id="nextSec" class="btn btn-default"> <i class="glyphicon glyphicon-fast-forward"  data-toggle="tooltip" data-placement="top" title="Next Section "></i> </button>
            </div>
            </div>
            <?php } ?>
            <br/>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $(this).bind("contextmenu", function(e) {
            e.preventDefault();
        });
    });    
</script>