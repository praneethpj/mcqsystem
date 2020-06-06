<?php $user_info = $this->db->get_where('users', array('user_id' => $this->session->userdata('user_id')))->row();?>
<section id="exam_summery">
    <div class="container">
        <div class="box">
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1">
                    <noscript><div class="alert alert-danger">Your browser does not support JavaScript or JavaScript is disabled! Please use JavaScript enabled browser to take this exam.</div></noscript>
                    <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                    <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>        
                    <?=(isset($message)) ? $message : ''; ?>
                </div>
                <div class="col-md-10 col-sm-12 col-md-offset-1 col-sm-offset-0">
                    <ol class="breadcrumb hidden-print">
                        <?php if ($this->session->userdata('log')) { ?>
                            <li><a href="<?= base_url('index.php/dashboard/' . $this->session->userdata('user_id')); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li> 
                        <?php } ?>
                        <li><a href="<?= base_url('index.php/exam_control/view_all_mocks') ?>"><i class="fa fa-puzzle-piece"></i> Exams</a></li> 
                        <li><a href="<?=base_url('index.php/exam_control/view_exam_summery/' . $mock->title_id) ?>"><i class="fa fa-tasks"></i> Exam Summery</a></li> 
                        <li class="active">Exam Instructions</li>
                    </ol>
                    <div class="big-gap"></div>
                    <h3><span class="text-muted">Exam Title: </span> <?= $mock->title_name ?></h3>

                    <h3 class="text-muted">Requirements:</h3>
                    <p class="">
                        <ul>
                            <li>You need javascript enabled browser to take this exam.</li>
                            <li>Use latest browser to have the best experience.</li>
                        </ul>
                    </p>
                    <h3 class="text-muted">Instructions:</h3>
                    <p class="">
                        <div class="well well-sm">
                            <ul>
                                <li>Each question has between 2 and 8 options. You have to choose one or more correct answers.</li>
                                <li>There are no penalties for incorrect answers.So attempt all questions.</li>
                                <li>You can review and change your answers before final submit.</li>
                                <li>There is no partial marking! If any question have more than one correct answers, you have mark all correct answers </li>
                                <li>Unanswered questions will be count as wrong answers.</li>
                                <li>You must answer all questions before the time duration shown on the top.</li>
                                <li>You can't resume the exam.</li>
                            </ul>
                        </div>
                    </p>

                    <div class="big-gap"></div>


                    <?php 
                    if ($this->session->userdata('log')) { 
                        if ($mock->exam_price) {
                            if (($this->session->userdata('log')) && ($user_info->subscription_id != 0) && ($user_info->subscription_end > now())) {
                                $payment_info = 'start_exam';
                            }else{
                                $payment_info = 'payment_process'; 
                            }
                        }else{
                            $payment_info = 'start_exam';
                        }
                    ?>
                        <a href="<?=base_url('index.php/exam_control/'.$payment_info.'/'.$mock->title_id) ?>" class="btn btn-success col-xs-5"> <?php echo ($payment_info == 'payment_process')?'Pay with PayPal':'Start Exam' ?></a> 

                    <?php
                    }else{
                    ?>
                        <a href="<?=base_url('index.php/exam_control/view_exam_instructions/'.$mock->title_id);?>" class="btn btn-primary"> Login to Start </a>
                    <?php 
                    } ?>

                    <div class="big-gap"><br/></div>
                    <p class="result-note"><strong>Note: </strong>The value of this exam certificate is only valid under the terms and conditions of <?= $brand_name ?>.</p>

                </div>
            </div>
        </div> 
    </div>
</section><!--/#pricing-->

<script>
$(document).ready(function() {
   $("#start-exam").removeAttr("disabled");
})    
</script>
<script language="JavaScript"><!--
javascript:window.history.forward(1);
//--></script>