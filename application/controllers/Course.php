<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Course extends MS_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('course_model');
        $this->load->model('exam_model');
        $this->load->model('admin_model');
    }

    public function index($message = '')
    {
        $data = array();
        $data['share'] = true;
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/top_navigation', $data, TRUE);
        $data['user_role'] = $this->admin_model->get_user_role();
        $data['categories'] = $this->exam_model->get_categories();
        $data['message'] = $message;
        $data['courses'] = $this->course_model->get_all_courses();
        $data['content'] = $this->load->view('content/view_course_list', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', $data, TRUE);
            // echo "<pre/>"; print_r($data['courses']); exit();
        $this->load->view('home', $data);
    }

    public function course_summary($id, $message = '')
    {
        $data = array();
        $data['share'] = true;
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/top_navigation', $data, TRUE);
        $data['message'] = $message;
        $data['course'] = $this->course_model->get_course_by_id($id);
        $data['sections'] = $this->course_model->get_sections($id);
        $data['content'] = $this->load->view('content/view_course_summary', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', $data, TRUE);
        $this->load->view('home', $data);
    }

    public function view_all_courses($message = '')
    {
        if (!$this->session->userdata('log')) {
            $this->session->set_userdata('back_url', current_url());
            redirect(base_url('index.php/login_control'));
        }

        $userId = $this->session->userdata('user_id');
        $data = array();
        $data['class'] = 91; // class control value left digit for main manu rigt digit for submenu
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', '', TRUE);
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        $data['categories'] = $this->exam_model->get_subcategories();
        if ($this->session->userdata('user_role_id') < 4) {
            $data['courses'] = $this->course_model->get_all_courses();
            $data['content'] = $this->load->view('content/view_all_courses', $data, TRUE);
        } else {
            $data['courses'] = $this->course_model->get_user_courses($userId);
            $data['content'] = $this->load->view('content/view_user_courses', $data, TRUE);
        }
        $data['footer'] = $this->load->view('footer/admin_footer', '', TRUE);
        $this->load->view('dashboard', $data);
    }


    public function view_course_by_category($cat_id)
    {
        $data = array();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['courses'] = $this->course_model->get_courses_by_category($cat_id);
        $data['categories'] = $this->exam_model->get_categories();
        $data['category_name'] = $this->db->get_where('sub_categories', array('id' => $cat_id))->row()->sub_cat_name;
        $data['top_navi'] = $this->load->view('header/top_navigation', $data, TRUE);
        $data['user_role'] = $this->admin_model->get_user_role();
        $data['content'] = $this->load->view('content/view_course_list', $data, TRUE);
        $data['footer'] = $this->load->view('footer/footer', $data, TRUE);
        $this->load->view('home', $data);
    }

    public function courses_type($type)
    {
        $data = array();
        $data['header'] = $this->load->view('header/head', '', TRUE);
        $data['categories'] = $this->exam_model->get_categories();
      //    $data['mock_count'] = $this->exam_model->mock_count($data['categories']);
        $data['top_navi'] = $this->load->view('header/top_navigation', $data, TRUE);
        $data['user_role'] = $this->admin_model->get_user_role();
            $data['courses'] = $this->course_model->get_courses_by_price($type);
        if($type === 'free'){
            $data['category_name'] = 'Free';
        }else if($type === 'paid'){
            $data['category_name'] = 'Paid';
        }else{
            redirect(base_url('index.php/course'));
        }
        $data['footer'] = $this->load->view('footer/footer', $data, TRUE);
        $data['content'] = $this->load->view('content/view_course_list', $data, TRUE);
        $this->load->view('home', $data);

    }

    public function create_course($message = '', $cat_id = '')
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }

        $userId = $this->session->userdata('user_id');
        $data = array();
        $data['class'] = 92; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        $data['cat_id'] = $cat_id;
        $data['categories'] = $this->exam_model->get_categories();
        $data['content'] = $this->load->view('form/course_form', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function save_course($message = '')
    {
          // echo "<pre/>"; print_r($this->input->post()); exit();
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('category', 'Sub Category', 'required|integer');
        $this->form_validation->set_rules('course_title', 'Course Title', 'required');
        $this->form_validation->set_rules('course_intro', 'Course Introduction', 'required');
        $this->form_validation->set_rules('course_description', 'Course Description', 'required');
        $this->form_validation->set_rules('course_requirement', 'Course Requirements', 'required');
        $this->form_validation->set_rules('target_audience', 'Course Audience', 'required');
        $this->form_validation->set_rules('what_i_get', 'What I Get', 'required');
        $this->form_validation->set_rules('price', 'Price', 'required|numeric');
        if ($this->form_validation->run() == FALSE) {
            // redirect(base_url('index.php/course/create_course'));
            $this->create_course();
        } else {
            $form_info = array();

            $title_id = $this->course_model->add_course_title();

            if ($_FILES['feature_image']['name']) {
                $uploads_dir = './course-images';
                $tmp_name = $_FILES["feature_image"]["tmp_name"];
                move_uploaded_file($tmp_name, "$uploads_dir/$title_id.png");
            }

            if ($title_id) {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Course created successfully!.'
                        . '</div>';
                $course_title = $this->input->post('course_title');
                $this->ctreat_course_sections($title_id, $course_title, $message);
            } else {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
                $this->ctreat_course($message);
            }
        }
    }

    public function ctreat_course_sections($course_id = 0, $course_title = 'Create Sections', $message = '')
    {
            // echo "<pre/>"; print_r(func_get_args()); exit();
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }

        $data = array();
        $data['class'] = 92; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        $data['course_title'] = $course_title;
        $data['course_id'] = $course_id;
        $data['content'] = $this->load->view('form/section_form', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function add_section($course_id = '', $message = '')
    {//        exit($course_id);
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        $data = array();
        $data['class'] = 91; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        // $data['sections'] = $this->course_model->get_sections($course_id);
        $data['course_id'] = $course_id;
        $data['course_title'] = $this->db->get_where('courses', array('course_id' => $course_id))->row()->course_title;
        $data['content'] = $this->load->view('form/section_form', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function save_sections()
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('section[0]', 'Section Title 1', 'required');
        $this->form_validation->set_rules('course_id', 'Course Id', 'required|integer');
        if ($this->form_validation->run() != FALSE)
        {
            if ($this->course_model->save_course_sections()) {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Section created successfully!.'
                        . '</div>';
                $this->session->set_flashdata('message', $message);
                redirect(base_url('index.php/course/course_detail/'.$this->input->post('course_id')));
            }else{
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
            }
        }

        $this->session->set_flashdata('message', $message);
        redirect(base_url('index.php/course/ctreat_course_sections/'.$this->input->post('course_id')));
    }

    public function add_content($course_id = '', $section_id = '', $message = '')
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }

        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('section', 'Select Section', 'required|integer');
            $this->form_validation->set_rules('video_title', 'Video Title', 'required');

            $course_id = $this->input->post('course_id');
            $section_id = $this->input->post('section');

            if ($this->form_validation->run() == FALSE) {
                $this->add_content($course_id, $section_id);
            } else {
                $form_info = array();
                if (isset($_FILES['media']['name'])) {
                    $path_parts = pathinfo($_FILES["media"]["name"]);
                    $extension = $path_parts['extension'];

                    $directory = $course_id;
                    if (!is_dir('course_videos/'.$directory)) {
                        mkdir('./course_videos/' . $directory, 0777, TRUE);

                        $myFile = "./course_videos/".$directory."/index.html";
                        $fh = fopen($myFile, 'w');
                        $stringData = "<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>";
                        fwrite($fh, $stringData);
                    }

                    // $total_course_file_size = $this->db->select_sum('file_size')->where('course_id', $course_id)->get('course_videos')->row()->file_size;

                    // $total_course_file_size = number_format(($total_course_file_size + $_FILES["media"]["size"])/1000000, 2);

                    // // echo "<pre/>"; print_r($_FILES); exit();

                    // if ($total_course_file_size > 400) {
                    //     $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>ERROR! The file size exceed the total allowed course size.</div>';
                    //     $this->session->set_flashdata('message', $message);
                    //     redirect(base_url('index.php/course/add_content/'.$course_id.'/'.$section_id));
                    // }

                    if($this->input->post('media_type') == 'file')
                    {
                        if(preg_match('/video\/*/',$_FILES['media']['type'])):
                            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>ERROR! To upload video file please choose content type video.</div>';
                            $this->session->set_flashdata('message', $message);
                            redirect(base_url('index.php/course/add_content/'.$course_id.'/'.$section_id));
                        endif;

                        $dest = './course_videos/'.$directory.'/'.$_FILES["media"]['name'];
            // echo "<pre>"; print_r($_FILES); print_r($dest) echo "</pre>"; exit();

                        if(move_uploaded_file($_FILES["media"]['tmp_name'], $dest)){
                            $video_id = $this->course_model->add_course_video($_FILES["media"]["name"], $_FILES["media"]["size"]);
                        }
                    }else{

                        $config['upload_path'] = './course_videos/'.$directory.'/';
                        $config['allowed_types'] = 'mp4|flv|avi|mpeg|ogg|webm';
                        $config['file_name'] = $section_id.'_'.$this->input->post('video_title').'.'.$extension;
                        $config['overwrite'] = TRUE;
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('media')) {
                            $error = array('error' => $this->upload->display_errors('<div class="alert alert-danger">', '</div>'));
                            $this->session->set_flashdata('message', $error['error']);
                            redirect(base_url('index.php/course/add_content/'.$course_id.'/'.$section_id));
                        } else {
                            $upload_data = $this->upload->data();
                            $video_id = $this->course_model->add_course_video($upload_data['file_name'], $_FILES["media"]["size"]);
                        }
                    }
                }else{
                    $video_id = $this->course_model->add_course_video();
                }

                if ($video_id) {
                    $message = '<div class="alert alert-success alert-dismissable">'
                            . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                            . 'Content uploaded successfully!.'
                            . '</div>';
                    $this->session->set_flashdata('message', $message);
                    if ($this->input->post('done')) {
                        redirect(base_url('index.php/course/course_detail/'.$course_id));
                    } else {
                        redirect(base_url('index.php/course/add_content/'.$course_id.'/'.$section_id));
                    }
                } else {
                    $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('index.php/course/add_content/'.$course_id.'/'.$section_id));
                }
            }

        }

        $data = array();
        $data['class'] = 92; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;

        $data['sections'] = $this->course_model->get_sections($course_id);
        $data['section_id'] = $section_id;

        $data['course_id'] = $course_id;
        $data['course_title'] = $this->db->get_where('courses', array('course_id' => $course_id))->row()->course_title;
        $data['content'] = $this->load->view('form/add_course_content', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $this->load->view('dashboard', $data);

    }

    public function upload_course_videos($message = '')
    {
        // echo "<pre/>"; print_r($_FILES); echo "<pre/>"; print_r($_POST); exit();
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('section', 'Select Section', 'required|integer');
        $this->form_validation->set_rules('video_title', 'Video Title', 'required');
        // $this->form_validation->set_rules('free', 'free');
        if ($this->form_validation->run() == FALSE) {
            $this->add_content($this->input->post('course_id'));
        } else {
            $form_info = array();
            if ($_FILES['media']['name']) {
                $path_parts = pathinfo($_FILES["media"]["name"]);
                $extension = $path_parts['extension'];

                $directory = $this->input->post('course_id');
                if (!is_dir('course_videos/'.$directory)) {
                    mkdir('./course_videos/' . $directory, 0777, TRUE);
                }
                $config['upload_path'] = './course_videos/'.$directory.'/';
                $config['allowed_types'] = 'mp4|flv|avi|mpeg|ogg|webm';
                $config['file_name'] = $this->input->post('section').'_'.$this->input->post('video_title').'.'.$extension;
                $config['overwrite'] = TRUE;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('media')) {
                    $error = array('error' => $this->upload->display_errors('<div class="alert alert-danger">', '</div>'));
                    $this->session->set_flashdata('message',$error['error']);
                    redirect(base_url('index.php/course/add_content/'.$this->input->post('course_id')));
                } else {
                    $upload_data = $this->upload->data();
                    $video_id = $this->course_model->add_course_video($upload_data['file_name']);
                }
            }else{
                $video_id = $this->course_model->add_course_video();
            }

            if ($video_id) {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Video added successfully!.'
                        . '</div>';
                $this->session->set_flashdata('message', $message);
                if ($this->input->post('done')) {
                    redirect(base_url('index.php/course/course_detail/'.$this->input->post('course_id')));
                } else {
                    redirect(base_url('index.php/course/add_content/'.$this->input->post('course_id')));
                }
            } else {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
                $this->session->set_flashdata('message', $message);
                redirect(base_url('index.php/course/add_content/'.$this->input->post('course_id')));
            }
        }
    }

    public function course_detail($id, $message = '')
    {
        if (!is_numeric($id)) {
            show_404();
        }
        $data = array();
        $data['class'] = 91;   // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        $data['courses'] = $this->course_model->get_course_detail($id);
        $data['sections'] = $this->course_model->get_sections($id);
        $data['content'] = $this->load->view('content/course_detail', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $data['extra_footer'] = $this->load->view('plugin_scripts/drag-n-drop','', TRUE);
        $this->load->view('dashboard', $data);
    }

    public function section_detail($id, $message = '')
    {
        if (!is_numeric($id)) show_404();

        $data = array();
        $data['class'] = 91;   // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        //        $data['courses'] = $this->course_model->get_course_detail($id);
        $data['section'] = $this->course_model->get_section_detail($id);
        $data['videos'] = $this->course_model->get_section_videos($id, $data['section']->course_id);
        $data['content'] = $this->load->view('content/section_detail', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $data['extra_footer'] = $this->load->view('plugin_scripts/drag-n-drop','', TRUE);
         //echo "<pre/>"; print_r($data['videos']); exit();
        $this->load->view('dashboard', $data);
    }


    public function edit_course_detail($id, $message = '')
    {
        if (!is_numeric($id)) {
            show_404();
        }
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        $data = array();
        $data['class'] = 91;   // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        $data['courses'] = $this->course_model->get_course_detail($id);
        $data['content'] = $this->load->view('form/edit_course_detail', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function update_course($id, $message = '')
    {
        if (!is_numeric($id))  show_404();
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('category', 'Category', 'required|integer');
        $this->form_validation->set_rules('course_title', 'Course Title', 'required');
        $this->form_validation->set_rules('course_intro', 'Course Introduction', 'required');
        $this->form_validation->set_rules('course_description', 'Course Description', 'required');
        $this->form_validation->set_rules('course_requirement', 'Course Requirements', 'required');
        $this->form_validation->set_rules('target_audience', 'Course Audience', 'required');
        $this->form_validation->set_rules('what_i_get', 'What I Get', 'required');
        $this->form_validation->set_rules('price', 'Price', 'required|numeric');

        if ($this->form_validation->run() == FALSE)
            redirect(base_url('index.php/course/edit_course_detail/'.$id));

        if ($_FILES['feature_image']['name']) {

            $uploads_dir = './course-images';
            $tmp_name = $_FILES["feature_image"]["tmp_name"];
            move_uploaded_file($tmp_name, "$uploads_dir/$id.png");
        }

        $this->course_model->update_course_title($id);

        $message = '<div class="alert alert-success alert-dismissable">'
                . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                . 'Course updated successfully!.'
                . '</div>';
            $this->session->set_flashdata('message',$message);
            redirect(base_url('index.php/course/view_all_courses'));
    }

    function delete_course($id)
    {
        if (!is_numeric($id)) show_404();
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }

        $have_sections = $this->db->get_where('course_sections', array('course_id' => $id))->result();

        if (!empty($have_sections)) {
            //echo "<pre/>"; print_r($have_video); exit();
            $message = '<div class="alert alert-danger">This course has sections. Please delete all sections on the course and try again.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('index.php/course/course_detail/'.$have_sections[0]->course_id));
        }else{
            $course_id = $this->db->get_where('courses', array('course_id' => $id))->row()->course_id;
            $this->db->where('course_id', $id);
            $this->db->delete('courses');
            if ($this->db->affected_rows() == 1) {
                unlink('./course-images/'.$id.'.png');

                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Course deleted successfully!.'
                        . '</div>';
            }else {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
            }
            $this->session->set_flashdata('message', $message);
            redirect(base_url('index.php/course/view_all_courses'));
        }
    }

    function delete_video($id)
    {
        if (!is_numeric($id)) show_404();

        $user_id = $this->session->userdata('user_id');
        $user_role_id = $this->session->userdata('user_role_id');
        $video = $this->db->where('video_id', $id)->get('course_videos')->row();

        $author = $this->db->where('course_id', $video->course_id)->get('courses')->row()->created_by;
        if ($author != $user_id && $user_role_id > 2) {
            $message = '<div class="alert alert-danger alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'You are not Authorised person to do this!'
                    . '</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('index.php/course/section_detail/'.$video->section_id));
        }

        $this->db->where('video_id', $id)->delete('course_videos');

        if (unlink('course_videos/'.$video->course_id.'/'.$video->video_link)) {
            $message = '<div class="alert alert-success alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'The video has deleted successfully.'
                    . '</div>';
        } else {
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
        }
        $this->session->set_flashdata('message', $message);
        redirect(base_url('index.php/course/section_detail/'.$video->section_id));
    }

    public function save_order()
    {
         $order = $_POST['ID'];
         $k  = 1;

         $str = implode(",", $order);
        // echo "<pre/>"; print_r($order); exit();
        foreach ($order as $k => $val){
            $data['orderList'] = $k;
            $this->db->where('section_id', $val)->update('course_sections', $data);

        }
        $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Saved successfully!.'
                        . '</div>';
        echo $message;

    }

    public function save_order_vdo()
    {
         $order = $_POST['ID'];
         $k  = 1;

         $str = implode(",", $order);
        // echo "<pre/>"; print_r($order); exit();
        foreach ($order as $k => $val){
            $data['orderList'] = $k;
            $this->db->where('video_id', $val)->update('course_videos', $data);

        }
        $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Saved successfully!.'
                        . '</div>';
        echo $message;
    }



    function update_section()
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
               // print_r($_POST);        exit();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('section_id', 'Section Id', 'required|integer');
        $this->form_validation->set_rules('section_name', 'Section Name', 'required');
        $this->form_validation->set_rules('section_title', 'Section Title', 'required');
        if ($this->form_validation->run() != FALSE) {
            if ($this->course_model->update_section()) {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Section updated successfully!.'
                        . '</div>';
            }else{
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>Section updated failed! Please try again.</div>';
            }
            $this->session->set_flashdata('message', $message);
        }
        redirect(base_url('index.php/course/course_detail/'.$this->input->post('course_id')));
    }

    function update_video()
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        //        print_r($_POST);        exit();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('video_id', 'Video Id', 'required|integer');
        $this->form_validation->set_rules('video_title', 'Video Title', 'required');
        if ($this->form_validation->run() != FALSE) {
            if ($this->course_model->update_video()) {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Video updated successfully!.'
                        . '</div>';
            }else{
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
            }
            $this->session->set_flashdata('message', $message);
        }
        redirect(base_url('index.php/course/section_detail/'.$this->input->post('section_id')));
    }

    function delete_section($id)
    {
        if (!is_numeric($id)) {
            show_404();
        }
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }

        $have_video = $this->db->get_where('course_videos', array('section_id' => $id))->result();
        if (!empty($have_video)) {
            //echo "<pre/>"; print_r($have_video); exit();
            $message = '<div class="alert alert-danger">This section has videos. Please delete all videos on the section and try again.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('index.php/course/course_detail/'.$have_video[0]->course_id));
        }else{
            $course_id = $this->db->get_where('course_sections', array('section_id' => $id))->row()->course_id;
            $this->db->where('section_id', $id);
            $this->db->delete('course_sections');
            if ($this->db->affected_rows() == 1) {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Section deleted successfully!'
                        . '</div>';
            }else {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
            }
            $this->session->set_flashdata('message', $message);
            redirect(base_url('index.php/course/course_detail/'.$course_id));
        }
    }

    public function enroll($id = NULL, $message = '')
    {
        if (($id == '') OR !is_numeric($id))  show_404();

        if (!$this->session->userdata('log')){
            $this->session->set_userdata('back_url', current_url());
            $message = '<div class="alert alert-danger alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'Please login to view this page!</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('index.php/login_control'));
        }

        $course_info = $this->db->get_where('courses', array('course_id' => $id))->row();

        $payment_settings = $this->admin_model->get_paypal_settings();

        $user_info = $this->db->get_where('users', array('user_id' => $this->session->userdata('user_id')))->row();

        if(!$course_info->course_price || ($user_info->subscription_id && ($user_info->subscription_end > now())))
            redirect(base_url('index.php/course/course_summary/'.$id));

        $currency = $this->db->select('currency.currency_code,currency.currency_symbol')
                        ->from('paypal_settings')
                        ->join('currency', 'currency.currency_id = paypal_settings.currency_id')
                        ->get()->row_array();

        if ($payment_settings->sandbox == 1)  $mode = TRUE; else $mode = FALSE;

        $settings = array(
            'username' => $payment_settings->api_username,
            'password' => $payment_settings->api_pass,
            'signature' => $payment_settings->api_signature,
            'test_mode' => $mode
        );

        $params = array(
            'amount' => $course_info->course_price,
            'currency' => $currency['currency_code'],
            'description' => $course_info->course_title,
            'return_url' => base_url('index.php/course/payment_complete/'.$id),
            'cancel_url' => base_url('index.php/course/course_summary/'.$id)
        );
            // echo "<pre/>"; print_r($params); exit();

        $this->load->library('merchant');
        $this->merchant->load('paypal_express');
        $this->merchant->initialize($settings);
        $response = $this->merchant->purchase($params);

        if ($response->status() == Merchant_response::FAILED) {
            $message = $response->message();
            echo('Error processing payment: ' . $message);
        }
    }

    public function payment_complete($id)
    {
        $course_info = $this->db->get_where('courses', array('course_id' => $id))->row();
        $payment_settings = $this->admin_model->get_paypal_settings();
        $currency = $this->db->select('currency.currency_code,currency.currency_symbol')
                        ->from('paypal_settings')
                        ->join('currency', 'currency.currency_id = paypal_settings.currency_id')
                        ->get()->row_array();

        if ($payment_settings->sandbox == 1) {
            $mode = TRUE;
        }else{
            $mode = FALSE;
        }

        $settings = array(
            'username' => $payment_settings->api_username,
            'password' => $payment_settings->api_pass,
            'signature' => $payment_settings->api_signature,
            'test_mode' => $mode
        );
        $params = array(
            'amount' => $course_info->course_price,
            'currency' => $currency['currency_code'],
            'cancel_url' => base_url('index.php/course/course_summary/'.$id)
        );

        $this->load->library('merchant');
        $this->merchant->load('paypal_express');
        $this->merchant->initialize($settings);
        $response = $this->merchant->purchase_return($params);

        if ($response->success()) {
            $message = '<div class="alert alert-sucsess alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'Payment Successful!</div>';
            $this->session->set_flashdata('message', $message);
            $data = array();
            $data['PayerID'] = $this->input->get('PayerID');
            $data['token'] = $this->input->get('token');
            $data['course_title'] = $course_info->course_title;
            $data['pay_amount'] = $course_info->course_price;
            $data['currency_code'] = $currency_code . ' ' . $currency_symbol;
            $data['method'] = 'PayPal';
            $data['gateway_reference'] = $response->reference();
            $paymentRefId = $this->set_payment_detail($data);

            $data['paymentRefId'] = $paymentRefId;
            $data['pur_ref_id'] = $id;
            $this->set_purchase_detail($data);

            $message .= '<div class="alert alert-success alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'Sessions are unlocked now.'
                    . '</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url('index.php/course/course_summary/'.$id));
        } else {
            $message = $response->message();
            echo('Error processing payment: ' . $message);
        }
    }

    public function set_payment_detail($info)
    {
        $data = array();
        $data['payer_id'] = $info['PayerID'];
        $data['token'] = $info['token'];
        $data['pay_amount'] = $info['pay_amount'];
        $data['payment_type'] = 'Course';
        $data['currency_code'] = $info['currency_code'];
        $data['user_id_ref'] = $this->session->userdata('user_id');
        $data['payment_reference'] = $info['course_title'];
        $data['pay_date'] = date('Y-m-d');
        $data['pay_method'] = $info['method'];
        $data['gateway_reference'] = $info['gateway_reference'];
        $this->db->insert('payment_history', $data);
        if ($this->db->affected_rows() == 1) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    public function set_purchase_detail($info)
    {
        $data = array();
        $data['type'] = 'Course';
        $data['user_id'] = $this->session->userdata('user_id');
        $data['pur_ref_id'] = $info['pur_ref_id'];
        $data['pur_date'] = date('Y-m-d');

        $data['payment_id'] = $info['paymentRefId'];

        $this->db->insert('puchase_history', $data);
        if ($this->db->affected_rows() == 1) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

}

