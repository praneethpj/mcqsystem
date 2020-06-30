<div class="hero-area section">

	<!-- Backgound Image -->
	<div class="bg-image bg-parallax overlay" style="background-image:url(<?= base_url('assets/new/img/page-background.jpg'); ?>)"></div>
	<!-- /Backgound Image -->

	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1 text-center">
				<ul class="hero-area-tree">
					<li><a href="<?= base_url(); ?><?= ($this->session->userdata('log')) ? 'index.php/dashboard/' . $this->session->userdata('user_id') : '' ?>">Home</a></li>
					<li>Price</li>
				</ul>
				<h1 class="white-text">Price List Page</h1>
			</div>
		</div>
	</div>
</div>

<section id="pricing">
    <div class="container">
        <div class="box">
            <div class="col-xs-10 col-xs-offset-1">
                <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>        
                <?=(isset($message)) ? $message : ''; ?>
            </div>
            <div class="center">
                <?php $temp = $this->db->get_where('content', array('content_type' => 'price_table_msg'))->row(); ?>

                <h1 class=""><?=$temp->content_heading; ?></h1>
                <p><?=$temp->content_data; ?></p>
            </div><!--/.center-->   
            <div class="big-gap"></div>
            <div class="row" style="margin-right: 20px; margin-left:20px;">
                <?php $count = count($memberships);
                foreach ($memberships as $offer) { ?>
                    <div class="col-md-<?=($count > 3)?'3':'4'; ?> col-sm-6 col-xs-12 float-shadow nopadding">
                        <?php if($offer->price_table_top){ ?>
                            <div class="recommended"><strong><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> RECOMMENDED</strong></div>                           
                        <?php } ?>
                        <div class="price_table_container">
                            <div class="price_table_heading"><?=$offer->price_table_title; ?></div>
                            <div class="price_table_body">
                                <div class="price_table_row cost <?=($offer->price_table_top)?'dark-bg':'static-bg'; ?>"><?=$currency_symbol.$offer->price_table_cost; ?></div>
                                <?php foreach ($features as $feature) { 
                                    if ($feature->parent_id == $offer->price_table_id) {
                                        echo '<div class="price_table_row">'.$feature->feature_item.'</div>';
                                    }
                                 } ?>
                            </div>
                            <a href="<?=base_url('index.php/membership/payment_process/'.$offer->price_table_id);?>" class="btn <?=($offer->price_table_top)?'btn-dark':' btn-static'; ?> btn-lg btn-block">Subscribe</a>
                        </div>
                    </div>
                <?php } ?>
            </div><!--/.row-->
        </div>
    </div>
</section><!--/#pricing-->