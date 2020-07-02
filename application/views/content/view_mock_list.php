<div class="hero-area section">

	<!-- Backgound Image -->
	<div class="bg-image bg-parallax overlay" style="background-image:url(<?= base_url('assets/new/img/page-background.jpg'); ?>)"></div>
	<!-- /Backgound Image -->

	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1 text-center">
				<ul class="hero-area-tree">
					<li><a href="<?= base_url(); ?><?= ($this->session->userdata('log')) ? 'index.php/dashboard/' . $this->session->userdata('user_id') : '' ?>">Home</a></li>
					<li>Exams</li>
				</ul>
				<h1 class="white-text">Exams Page</h1>
			</div>
		</div>
	</div>
</div>
<section id="exams">
    <div class="container">
        <div class="box">
	        <div class="row">
                <div class="col-xs-10 col-xs-offset-1">
                    <?=validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                    <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>        
                    <?=(isset($message)) ? $message : ''; ?>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 nopadding">
					<ul class="nav  category-menu" style="float:left;">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class=" fa fa-sitemap"></i> &nbsp;All Categories <span class="caret"></span></a>
		                	<ul class="dropdown-menu" role="menu">
		                    <?php 
		                    foreach ($categories as $value) {
		                        $sub = $this->db->get_where('sub_categories', array('cat_id' => $value->category_id))->result();
		                        if(!empty($sub)){ ?>
			                        <li class="dropdown-submenu">
			                   			<a href="#" tabindex="-1" class="dropdown-toggle" data-toggle="dropdown"><?=$value->category_name; ?></a>
			                            <ul class="dropdown-menu">
			                                <h3><i class="fa fa-code-fork"></i> <?=$value->category_name; ?></h3>
		                                    <?php foreach ($sub as $sub_cat) { ?>
		                                    <li>
		                                        <a href="<?=base_url('index.php/category/'.$sub_cat->id); ?>"><?=$sub_cat->sub_cat_name; ?></a>
		                                    </li>
		                                    <?php } ?>
			                            </ul>
			                        </li>
		                        <?php }else{ ?>
                                    <li><a href="#"><?=$value->category_name; ?></a></li>
		                    <?php 
		                		}
		                    } ?>
		            		</ul>
		                </li>
		            </ul>    

		
					<div class="form-group">
  <label for="sel1">Select list:</label>
  <select class="form-control" id="sel1">
    <option>G.C.E A/L</option>
    <option>G.C.E O/L</option>
    <option>Scholarships (Grade 5)</option>
   
  </select>
</div>

<div class="form-group">
  <label for="sel1">Select Stream:</label>
  <select class="form-control" id="sel1">
    <option>Physical Science (Maths)</option>
    <option>Bio Science (Bio)</option>
    <option>Commerce</option>
    <option>Technology</option>
	<option>Arts</option>
  </select>
</div>

<div class="form-group">
  <label for="sel1">Select Subject:</label>
  <select class="form-control" id="sel1">
  <option value="22">Information Technology - O/L</option>
                                                                                                                                                                <option value="8">Logic - A/L</option>
                                                                                                                                                                <option value="1">Physics - A/L</option>
                                                                                                                                                                <option value="9">Political Science - A/L</option>
                                                                                                                                                                <option value="15">Scholarships (Paper I)</option>
                                                                                                                                                                <option value="19">Scholarships (Paper II)</option>
                                                                                                                                                                <option value="20">Science - O/L</option>
                                                                                                                                                                <option value="17">Science for Technology - A/L</option>
                                                                                                                                                                <option value="14">Sinhala - A/L</option>
                                                                                                                                                                <option value="28">Sinhala - O/L</option>
  </select>
</div>

<div class="form-group">
  <label for="sel1">Select Language:</label>
  <select class="form-control" id="sel1">
  <option  value="2">English</option>
                                                                                                                                                                <option  value="3">தமிழ்</option>
    <option>Sinhala</option>
 
  </select>
</div>


					       	
                </div><!--/.col-md-2-->

				
				
                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 nopadding">
                    <h4><?=isset($category_name)?$category_name:'All Exams'; ?></h4>
                    <?php if ($commercial) { ?>
                    <div class="btn-group pull-right">
                        <a href="<?=base_url('index.php/exam_control/view_all_mocks') ?>" class="btn btn-sm btn-default">All</a>
                        <a href="<?=base_url('index.php/exam_control/mocks_type/paid') ?>" class="btn btn-sm btn-default">Paid</a>
                        <a href="<?=base_url('index.php/exam_control/mocks_type/free') ?>" class="btn btn-sm btn-default">Free</a>
                    </div>
                    <?php } ?>                
	                <div class="exam-list">
	                <?php 
	                    if (isset($mocks) AND !empty($mocks)) {  $i = 1;
	                        foreach ($mocks as $mock) {
	                            if (($mock->exam_active == 1) && ($mock->public == 1)) {
	                                $hr = (int) substr($mock->time_duration, 0, 2); // returns hr 
	                                $minutes =substr($mock->time_duration, -5, 5); // returns minutes 
	                ?>
	                                <div class="col-lg-3 col-md-4 col-xs-12 col-sm-6 exam-item">

                                        <span style="position: absolute; right: 20px; top: 20px; font-weight: lighter; font-size: 1.4em;">
                                            <?php if ($mock->exam_price) {
                                                echo '<span class="label label-warning pull-right">'.$currency_symbol.$mock->exam_price.'</span>';
                                            }else{
                                                echo '<span class="label label-primary pull-right">Free</span>';
                                            } ?>                                                
                                        </span>

	                                    <div class="thumbnail">
	                                        <a href="<?=base_url('index.php/exam_control/view_exam_summery/'.$mock->title_id); ?>">
                                                <?php if (file_exists("exam-images/$mock->title_id.png")) { ?>
	                                                <img class="exam-thumbnail" src="<?=base_url("exam-images/$mock->title_id.png"); ?>" data-src="holder.js/300x300" alt="...">
	                                            <?php }else{ ?>
	                                                <img class="exam-thumbnail" src="<?=base_url('exam-images/placeholder.png'); ?>" data-src="holder.js/300x300" alt="...">
	                                            <?php } ?>
	                                            <div class="caption">
	                                                <span class="exam-category text-danger"><?=$mock->category_name.'/'.$mock->sub_cat_name;?></span>
	                                                <span class="exam-title"><?=$mock->title_name;?></span>
	                                                <p> 
	                                                    <time class="total-question" ><?=($mock->random_ques_no != 0) ? $mock->random_ques_no : $this->db->where('exam_id', $mock->title_id)->get('questions')->num_rows();?> questions</time>
	                                                    <div class="exam-duration" ><?=($hr)?$mock->time_duration.' hours':$minutes.' minutes';?></div>
	                                                    <button class="btn btn-sm btn-primary">Start</button>
    <div class="fb-share-button" 
        data-href="<?=base_url('index.php/exam_control/view_exam_summery/'.$mock->title_id)?>" 
        data-layout="button" >
    </div>

	                                                </p>
	                                            </div>
	                                        </a>
	                                    </div>   
	                                </div>
	                <?php           $i++;
	                            }
	                        }
	                    } else {
	                        echo '<h4>No exam found!</h4>';
	                    }
	                    ?>
	                </div> <!-- /.exam-list -->    
            	</div><!--/.col-md-10-->
            </div><!--/.row-->
        </div><!--/.box-->
    </div><!--/.container-->
</section><!--/#emaxs-->
