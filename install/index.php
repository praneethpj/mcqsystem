<?php
error_reporting(E_ALL); //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.

$db_config_path = '../application/config/database.php';
error_reporting(0);

// Only load the classes in case the user submitted the form
if ($_POST) {

    // Load the classes and create the new objects
    require_once('includes/core_class.php');
    require_once('includes/database_class.php');

    $core = new Core();
    $database = new Database();

    // Validate the post data
    if ($core->validate_post($_POST) == true) {

        // First create the database, then create tables, then write config file
        if ($database->check_database_connection($_POST) == false) {
            $message = $core->show_message('error', "The database connection failed. Please check the given info. If not sure about it, please talk to your system admin/hosting provider.");
        }else if ($database->check_database($_POST) == false) {
            $message = $core->show_message('error', "The database could not be found, please create a new database and try again.");
        }else if ($database->create_tables($_POST) == false) {
            $message = $core->show_message('error', "The database could not be installed, please verify your settings.");
        } else if ($core->write_config($_POST) == false) {
            $message = $core->show_message('error', "The database configuration file could not be written, Please make the application/config/database.php file writable. <small>Example: <code>chmod 777 application/config/database.php</code></small>");
        }

        // If no errors, redirect to registration page
        if (!isset($message)) {
            $code = sha1('GALUA');
            $redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
            $redir .= "://" . $_SERVER['HTTP_HOST'];
            $redir .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
            $redir = str_replace('install/', '', $redir);
            header('Location: ' . $redir . 'index.php/admin/system_control?code='.$code);
        }
    } else {
        $message = $core->show_message('error', 'Not all fields have been filled in correctly. The host, username, password, and database name are required.');
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="../assets/bootstrap/css/bootstrap.css" rel="stylesheet" media="all"></link>
        <link href="assets/style.css" rel="stylesheet" media="all"></link>
        <title>Install | Minor School</title>
    </head>
    <body>
        <div class="row">
        <h1 class="text-center">Install | Minor School</h1>
        </div><br/>
        <div class="container">
            <div class="row">
                <div class="col-md-offset-2 col-md-8 col-xs-offset-1 col-xs-10">

                    <?php $requires_fail = array();

                    if(phpversion() < '5.6.0' ) 
                    {
                        
                      $requires_fail[] = "Minimum PHP version <code>5.6.0</code> is required. Please upgrade the PHP version on you server. <br/><small>Current PHP version: ". phpversion() ."</small>";

                    } 

                    if (!is_writable($db_config_path)) 
                    {

                         $requires_fail[] = "Please make the application/config/database.php file writable. <br/><small>Example: <code>chmod 777 application/config/database.php</code></small>";

                    } 

                    if (!empty($requires_fail)) 
                    {
                        echo '<div class="alert alert-info"> <h3> The application requirements are failed. Please fix this issues before install the application. </h3></div>';

                        foreach ($requires_fail as $require_fail) {

                            echo '<div class="alert alert-danger">'. $require_fail .'</div>';

                        }

                    } else{ ?>
                        <?php   if (isset($message))  echo '<div class="alert alert-danger">' . $message . '</div>';  ?>
                        <form id="install_form" method="post" class="form-horizontal" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <fieldset class="set-border">
                                <legend class="set-border">Database settings</legend>
                                <div class="form-group">
                                    <label for="hostname" class="col-md-offset-1 col-md-3">Hostname: </label>
                                    <div class="col-md-7">
                                        <input type="text" id="hostname" value="<?=@$_POST['hostname']?>" class="form-control" name="hostname"  placeholder="Hostname" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="col-md-offset-1 col-md-3">Username: </label>
                                    <div class="col-md-7">
                                        <input type="text" id="username" value="<?=@$_POST['username']?>" class="form-control" name="username" placeholder="Username" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-md-offset-1 col-md-3">Password: </label>
                                    <div class="col-md-7">
                                        <input type="password" id="password" class="form-control" name="password"  placeholder="Password" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="database" class="col-md-offset-1 col-md-3">Database Name: </label>
                                    <div class="col-md-7">
                                        <input type="text" id="database" value="<?=@$_POST['database']?>" class="form-control" name="database"  placeholder="Database Name" />
                                    </div>
                                </div><br/>
                                <button type="submit" id="submit" class="btn btn-primary btn-lg btn-block"><i class="glyphicon glyphicon-check"></i> Submit </button>
                                <br/>
                                <button type="reset" class="btn btn-default col-xs-5 col-sm-3">Reset</button>
                            </fieldset>                    
                        </form>
                    <?php } ?>
                    
                </div>
            </div>
        </div>

    </body>
</html>