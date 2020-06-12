<!-- Header -->
<header id="header" class="transparent-nav">
    <div class="container">

        <div class="navbar-header">
            <!-- Logo -->
            <div class="navbar-brand">
                <a class="logo" href="<?= base_url(); ?><?= ($this->session->userdata('log')) ? 'index.php/dashboard/' . $this->session->userdata('user_id') : '' ?>">
                    <?php if (file_exists('./logo.png')) { ?>
                        <img src="<?= base_url(); ?>logo.png" width="200px" height="78px">
                    <?php } else {
                        echo ($brand_name) ? $brand_name : 'MinorSchool';
                    } ?>
                </a>
            </div>
            <!-- /Logo -->

            <!-- Mobile toggle -->
            <button class="navbar-toggle">
                <span></span>
            </button>
            <!-- /Mobile toggle -->
        </div>

        <!-- Navigation -->
        <nav id="nav">
            <ul class="main-menu nav navbar-nav navbar-right">
                <li class="<?= ($this->uri->segment(1) == '') ? 'active' : ''; ?>"><a href="<?= base_url('index.php'); ?>"><i class="fa fa-home"></i></a></li>
                <li class="<?= ($this->uri->segment(1) == 'course') ? 'active' : ''; ?>"><a href="<?= base_url('index.php/course'); ?>">Courses</a></li>
                <li class="<?= ($this->uri->segment(1) == 'exam_control') ? 'active' : ''; ?>"><a href="<?= base_url('index.php/exam_control/view_all_mocks'); ?>">Exams</a></li>
                <li class="<?= ($this->uri->segment(2) == 'pricing') ? 'active' : ''; ?>"><a href="<?= base_url('index.php/guest/pricing'); ?>">Pricing</a></li>
                <!-- <li class="<?= ($this->uri->segment(1) == 'blog') ? 'active' : ''; ?>"><a href="<?= base_url('index.php/blog'); ?>">Blog</a></li> -->
                <?php if ($this->session->userdata('log')) { ?>
                    <li class="<?= ($this->uri->segment(1) == 'noticeboard') ? 'active' : ''; ?>"><a href="<?= base_url('index.php/noticeboard/notices'); ?>">Noticeboard</a></li>
                    <li class="<?= ($this->uri->segment(2) == 'view_faqs') ? 'active' : ''; ?>"><a href="<?= base_url('index.php/guest/view_faqs'); ?>">FAQ</a></li>
                    <li><a href="<?= base_url('index.php/login_control/logout'); ?>"><i class="fa fa-power-off"></i></a></li>
                <?php } else { ?>
                    <!-- <li><a href="<?= base_url('index.php/admin'); ?>">Admin</a></li> -->
                    <li class="nav-item "><a>Join as a Teacher</a></li>
                <?php } ?>
            </ul>
        </nav>
        <!-- /Navigation -->

    </div>
</header>
<!-- /Header -->
<?php   //   echo "<pre/>"; print_r($this->uri->segment(1)); exit();    
?>