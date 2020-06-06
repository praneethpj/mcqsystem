<?php 
    if ($this->session->userdata('time_zone')) date_default_timezone_set($this->session->userdata('time_zone')); 
    else if( ! ini_get('date.timezone') ) date_default_timezone_set('GMT');
?>
<footer>
    <div class="container">
        <p class="text-muted">Copyright &copy; <?=$brand_name.', '. date('Y')?></p>
    </div>
</footer>
<!-- bootstrap Scripts-->
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>

<!-- Data table Scripts-->
<?php
   $this->load->view('plugin_scripts/datatable_scripts');
?>
<!-- X-Editable Scripts-->
<?php
   $this->load->view('plugin_scripts/x-editable_scripts');
?>
