<div id="note"> <?php  if ($message) echo $message; ?> 
<?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';?>   
    
</div>

<div class="block">  
    <div class="navbar block-inner block-header">
        <div class="row"><p class="text-muted">Exam List </p></div>
    </div>
    <div class="block-content">
        <div class="row">
            <div class="col-sm-12">
                <?php if (isset($mocks) AND !empty($mocks)) { ?>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
                        <thead>
                            <tr>
                                <th>Question Title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($mocks as $mock) {
                            ?>
                                <tr class="<?= ($i & 1) ? 'even' : 'odd'; ?>">
                                    <td>
                                        <p class="lead"><?= $mock->question; ?></p>
                                      
                                        <span class="text-muted">Public: </span><?= ($mock->public == 1) ? 'Yes' : 'No'; ?>&nbsp;
                    
                                        <span class="text-muted">Subject: </span><?=$mock->subject_title; ?>&nbsp;
                                        <br/>
                                        <span class="text-muted">Teacher: </span><?= $mock->user_name . '.'; ?>&nbsp;
                                     
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a class="btn btn-success btn-sm" href = "<?= base_url('index.php/admin_control/view_my_mock_detail/' . $mock->ques_id); ?>"><span class="invisible-on-md">  View Questions</span></a>
                                            <a class="btn btn-info btn-sm" href = "<?= base_url('index.php/admin_control/edit_mock_detail/' . $mock->title_id); ?>"><span class="invisible-on-md">  Approve</span></a>
                                            <a onclick="return delete_confirmation()" href = "<?php echo base_url('index.php/admin_control/delete_exam/' . $mock->title_id); ?>" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i><span class="invisible-on-md">  Delete</span></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo '<h3>No result found!</h3>';
                }
                ?>
            </div>
        </div>
    </div>
</div><!--/span-->

