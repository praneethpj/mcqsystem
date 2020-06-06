<div id="note">
    <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
    <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>        
</div>
<div class="block">  
  <div class="navbar block-inner block-header">
      <div class="row">
          <p class="text-muted">Modify user</p>
      </div>
  </div>
  <div class="block-content">
      <div class="row">
        <div class="col-sm-12">
          <?=form_open(base_url('index.php/user_control/modify_user/'.$data_id), 'role="form" class="form-horizontal"'); ?>
            <div class="form-group">
              <label for="user_name" class="col-sm-2 control-label col-xs-2">User Name: *</label>
              <div class="col-xs-6">
                  <?php echo form_input('user_name', $user->user_name, 'placeholder="User Name" class="form-control" required="required"') ?>
              </div>
            </div>
            <div class="form-group">
              <label for="user_email" class="col-sm-2 control-label col-xs-2">Email: *</label>
              <div class="col-xs-6">
                  <?php echo form_input('user_email', $user->user_email, 'pattern="^[a-zA-Z0-9.!#$%&'."'".'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$" title="you@domain.com" placeholder="Email address" class="form-control" required="required"') ?>
              </div>
            </div>

            <div class="form-group">
              <label for="user_phone" class="col-sm-2 control-label col-xs-2">Phone:</label>
              <div class="col-xs-6">
                  <?php echo form_input('user_phone', $user->user_phone, 'pattern="^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$" title="Enter Valid Phone Number" min="8" max="15" placeholder="Phone Number" class="form-control"') ?>
              </div>
            </div>
            <div class="form-group">
              <label for="user_role" class="col-sm-2 control-label col-xs-2">Role: *</label>
              <div class="col-xs-6">
                <select name="user_role" class="form-control">
                    <?php foreach ($user_role as $value) {
                        if ($value->user_role_id > $this->session->userdata('user_role_id')) { ?>
                        <option value="<?=$value->user_role_id;?>" <?=($user->user_role_id == $value->user_role_id)?'selected':'';?> ><?=$value->user_role_name;?></option>
                    <?php } } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-offset-3 col-sm-8 col-xs-offset-2 col-xs-9">
                  <p class="text-muted">* Required fields.</p>
              </label>
            </div>
            <div class="col-xs-offset-1 col-sm-offset-2 col-xs-4">
                <button type="submit" class="btn btn-primary col-xs-6">Save</button>&nbsp;
                <button type="reset" class="btn btn-default">Reset</button>
            </div>
          <?php echo form_close() ?>
        </div>
      </div>
  </div>
</div>
