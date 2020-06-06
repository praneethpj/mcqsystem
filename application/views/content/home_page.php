    <section id="main-slider" class="carousel">
        <div class="col-xs-10 col-xs-offset-1 " style="margin-top: -90px;">
            <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>        
            <?=(isset($message)) ? $message : ''; ?>
        </div>
        <div class="carousel-inner">
            <?php $i = 0;
            $sliders = $this->db->where('content_type', 'slider_text')->get('content')->result();
            foreach ($sliders as $slider) { $i++; ?>
            <div class="item <?=($i==1)?'active':'';?>">
                <div class="container">
                    <div class="carousel-content">
                        <h1><?=$slider->content_heading;?></h1>
                        <p class="lead"><?=$slider->content_data;?></p>
                    </div>
                </div>
            </div><!--/.item-->
            <?php } ?>
        </div><!--/.carousel-inner-->
        <?php if (!$this->session->userdata('log')): ?>
            <div class="center">
                <?php if ($student_can_register): ?>
                    <a href="#register" class="btn btn-primary btn-home-slider btn-lg register_open">Register</a>
                <?php endif; ?>
                <a href="#login" class="btn btn-primary btn-home-slider btn-lg login_open">Login</a>
            </div>
        <?php endif; ?>
        <a class="prev" href="#main-slider" data-slide="prev"><i class="fa fa-angle-left"></i></a>
        <a class="next" href="#main-slider" data-slide="next"><i class="fa fa-angle-right"></i></a>
    </section><!--/#main-slider-->

    <section id="about-us">
        <div class="container">
            <div class="box first">
                <div class="row">
                    <div class="col-md-10 col-sm-12 col-md-offset-1 col-sm-offset-0">
                        <div class="center">
                            <?php $temp = $this->db->get_where('content', array('content_type' => 'about_us'))->row(); ?>
                            <!-- <i class="fa fa-apple fa fa-md fa fa-color1"></i> -->
                            <h1><?=$temp->content_heading; ?></h1>
                            <p><?=$temp->content_data; ?></p>
                        </div>
                    </div><!--/.col-md-4-->
                </div><!--/.row-->
            </div><!--/.box-->
        </div><!--/.container-->
    </section><!--/#services-->


    <section id="latest-exams">
        <div class="container">
            <div class="box first">
                <div class="row">
                    <div class="col-md-10 col-sm-12 col-md-offset-1 col-sm-offset-0">
                        <div class="center">
                            <?php $courses = $this->db->order_by('course_id', 'DESC')->limit('4')->get('courses')->result(); ?>
                            <!-- <i class="fa fa-apple fa fa-md fa fa-color1"></i> -->
                            <h1>Latest Courses</h1>

                            <div class="exam-list">
                                <?php 
                                if (isset($courses) AND !empty($courses)) {  $i = 1;
                                    foreach ($courses as $course) {
                                        if ($course->active == 1) {
                                        ?>
                                            <div class="col-md-3 col-xs-12 col-sm-6 exam-item">
                                                <div class="thumbnail">
                                                    <span style="position: absolute; top: 20px; left: 20px; font-weight: lighter;">
                                                            <?=$this->db->where('course_id', $course->course_id)->from('course_videos')->count_all_results(); ?> lessons
                                                    </span>
                                                    <span style="position: absolute; right: 20px; top: 20px; font-weight: lighter; font-size: 1.3em;">
                                                        <?php if ($course->course_price) {
                                                            echo '<span class="label label-warning pull-right">'.$currency_symbol.$course->course_price.'</span>';
                                                        }else{
                                                            echo '<span class="label label-primary pull-right">Free</span>';
                                                        } ?>                                                
                                                    </span>

                                                    <a href="<?php echo base_url('index.php/course/course_summary/'.$course->course_id); ?>">
                                                        <?php if (file_exists("course-images/$course->course_id.png")) { ?>
                                                            <img class="exam-thumbnail" src="<?=base_url("course-images/$course->course_id.png"); ?>" data-src="holder.js/300x300" alt="...">
                                                        <?php }else{ ?>
                                                            <img class="exam-thumbnail" src="<?=base_url('exam-images/placeholder.png'); ?>" data-src="holder.js/300x300" alt="...">
                                                        <?php } ?>

                                                        <span class="exam-title" style="text-align: left; margin: 10px;"><?=$course->course_title;?></span>
                                                    </a>
                                                </div>   
                                            </div>
                                            <?php $i++;
                                        }
                                    }
                                } else {
                                    echo '<p>No course available.</p>';
                                }
                                ?>
                            </div> <!-- /exam-list -->    

                        </div>
                    </div><!--/.col-md-4-->
                </div><!--/.row-->
            </div><!--/.box-->
        </div><!--/.container-->
    </section><!--/#services-->    
