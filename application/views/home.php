<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Munna Khan">
        <title><?=($brand_name)?$brand_name.' - '.$brand_tagline:'MinorSchool' ?></title>

        <?php 
        if(isset($share)){
        
            if(strpos($_SERVER['REQUEST_URI'], "course") !== false && isset($course)){ ?>

                <meta property="og:url"           content="<?=base_url('index.php/course/course_summary/'.$course->course_id)?>" />
                <meta property="og:type"          content="website" />
                <meta property="og:title"         content="<?=$course->course_title?>" />
                <meta property="og:description"   content="<?=$course->course_description?>" />
                <meta property="og:image"         content="<?=base_url("course-images/$course->course_id.png"); ?>" />

            <?php 
            }else if(strpos($_SERVER['REQUEST_URI'], "exam_control") !== false && isset($mock))
            { ?>
                <meta property="og:url"           content="<?=base_url('index.php/exam_control/view_exam_summery/'.$mock->title_id)?>" />
                <meta property="og:type"          content="website" />
                <meta property="og:title"         content="<?=$mock->title_name?>" />
                <meta property="og:description"   content="<?=$mock->syllabus?>" />
                <meta property="og:image"         content="<?=base_url("exam-images/$mock->title_id.png"); ?>" />
            <?php
            }

        } ?>

        <!--Header-->
        <?=$header; ?>
        <!--Page Specific Header-->
        <?php  if (isset($extra_head)) echo $extra_head; ?>
    </head>
    <body>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=564958046919608";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

        <!--Top Navigation-->
        <?=(isset($top_navi)) ? $top_navi : ''; ?>
        <!--Sidebar-->
        <?=(isset($sidebar)) ? $sidebar : ''; ?>

        <!--Content-->
        <?=(isset($content)) ? $content : ''; ?>

        <!--Footer-->
        <?=$footer; ?>
        <!--Page Specific Footer-->
        <?php if (isset($extra_footer)) echo $extra_footer; ?>
    </body>
</html>