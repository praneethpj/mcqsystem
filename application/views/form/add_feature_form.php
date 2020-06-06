<div id="note">
    <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
    <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>        
    <?=(isset($message)) ? $message : ''; ?>
</div>
<div class="block">  
    <div class="navbar block-inner block-header">
        <div class="row">
            <p class="text-muted">New feature</p>
        </div>
    </div>
    <div class="block-content">
        <div class="row">
            <div class="col-sm-12">
                <?php echo form_open(base_url() . 'index.php/membership/save_features', 'role="form" class="form-horizontal"'); ?>
                  <?php
                  $option = array();
                  $option[0] = 'Select membership';
                  foreach ($memberships as $value) {
                          $option[$value->price_table_id] = $value->price_table_title;
                  }
                  ?>
                <div class="form-group">
                  <label for="membership_id" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Membership Type:*</label>
                  <div class="col-lg-5 col-sm-8 col-xs-7 col-mb">
                      <?php echo form_dropdown('membership_id', $option,'','class="form-control"') ?>
                  </div>
                </div>
                <div class="form-group">
                    <label for="feature_1" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Feature 1:*</label>
                    <div class="col-lg-5 col-sm-8 col-xs-7 col-mb">
                      <?php 
                        $data = array(
                            'name'        => 'feature[]',
                            'placeholder' => 'new feature 1',
                            'id'          => 'feature_1',
                            'value'       => '',
                            'rows'        => '2',
                            'class'       => 'form-control textarea-wysihtml5',
                            'required' => 'required',
                        ); ?>
                        <?php echo form_textarea($data) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="feature_2" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Feature 2:</label>
                    <div class="col-lg-5 col-sm-8 col-xs-7 col-mb">
                      <?php 
                        $data = array(
                            'name'        => 'feature[]',
                            'placeholder' => 'new feature 2',
                            'id'          => 'feature_2',
                            'value'       => '',
                            'rows'        => '2',
                            'class'       => 'form-control textarea-wysihtml5',
                        ); ?>
                        <?php echo form_textarea($data) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="feature_3" class="col-sm-offset-0 col-lg-2 col-xs-offset-1 col-xs-3 control-label mobile">Feature 3:</label>
                    <div class="col-lg-5 col-sm-8 col-xs-7 col-mb">
                      <?php 
                        $data = array(
                            'name'        => 'feature[]',
                            'placeholder' => 'new feature 3',
                            'id'          => 'feature_3',
                            'value'       => '',
                            'rows'        => '2',
                            'class'       => 'form-control textarea-wysihtml5',
                        ); ?>
                        <?php echo form_textarea($data) ?>
                    </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-offset-3 col-sm-8 col-xs-offset-2 col-xs-9">
                      <p class="text-muted">* Required fields.</p>
                  </label>
                </div>

                <div class="col-xs-offset-1 col-sm-offset-2 col-xs-4">
                    <button type="submit" class="btn btn-primary col-xs-6">Save</button>&nbsp;
                </div>
                <?php echo form_close() ?>

            </div>
        </div>
    </div>
</div>

<?php include 'application/views/plugin_scripts/bootstrap-wysihtml5.php';?>
    
