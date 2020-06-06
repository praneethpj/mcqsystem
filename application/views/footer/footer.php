<?php 
    if ($this->session->userdata('time_zone')) date_default_timezone_set($this->session->userdata('time_zone')); 
    else if( ! ini_get('date.timezone') ) date_default_timezone_set('GMT');

	if (!isset($no_contact_form)) 
	{
    	include 'application/views/contact_form.php';
	} 
?>

<!-- Modal Start -->
<?php if (isset($modal)) echo $modal; ?>
<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <p class="text-muted" style="margin: 10px 0;">U0NSSVBUIFNIQVJFRCBPTiBDT0RFTElTVC5DQw==</p>
            </div>
        </div>
    </div>
</footer><!--/#footer-->

<div id="fade" class="black_overlay"></div> 
<!-- Common Scripts-->
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
<!-- Custom JS  -->
<script src="<?php echo base_url('assets/js/jsscript.js') ?>"></script>