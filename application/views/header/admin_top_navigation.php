<ul class="nav navbar-nav navbar-right">
    <li class="dropdown user-dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?=$this->session->userdata('user_name')?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="<?=base_url('index.php/admin_control');?>"><i class="fa fa-user"></i> Profile</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url('index.php/login_control/logout'); ?>"><i class="fa fa-power-off"></i> Log Out</a></li>
        </ul>
    </li>
</ul>
