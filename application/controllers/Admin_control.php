<?php
if (!defined('BASEPATH'))  exit('No direct script access allowed');


class Admin_control extends MS_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('exam_model');
        $this->load->model('admin_model');
        if (!$this->session->userdata('log')) {
            $this->session->set_userdata('back_url', current_url());
            redirect(base_url('index.php/login_control'));
        }
    }

    public function index($message = '')
    {
        $data = array();
        $data['class'] = 31; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', '', TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        $data['profile_info'] = $this->admin_model->get_my_profile_info();
        $data['content'] = $this->load->view('admin/view_profile_info', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', '', TRUE);
        $this->load->view('dashboard', $data);
    }

    public function view_my_mocks($message = '')
    {
        $userId = $this->session->userdata('user_id');
        $data = array();
        $data['class'] = 21; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', '', TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        $data['categories'] = $this->exam_model->get_categories();
        if ($this->session->userdata('user_role_id') <= 3) {
            $data['mocks'] = $this->exam_model->get_all_mocks();
            $data['content'] = $this->load->view('content/view_all_mocks', $data, TRUE);
        } else {
            $data['mocks'] = $this->admin_model->get_user_mocks($userId);
            $data['content'] = $this->load->view('content/view_user_mocks', $data, TRUE);
        }
        $data['footer'] = $this->load->view('footer/admin_footer', '', TRUE);
        $this->load->view('dashboard', $data);
    }

    public function view_my_mock_detail($id, $message = '')
    {
        if (!is_numeric($id)) {
            show_404();
        }

        $data = array();
        $data['mock_title'] = $this->exam_model->get_mock_by_id($id);
        if (!$this->session->userdata('log') || ($this->session->userdata('user_role_id') > 2 && $data['mock_title']->user_id != $this->session->userdata('user_id')))
        {
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }

        $data['class'] = 21;   // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        if (!(empty($data['mock_title'])) && (($this->session->userdata('user_role_id') <= 3) OR ($data['mock_title']->user_id == $this->session->userdata('user_id')))) {
            $data['mocks'] = $this->exam_model->get_mock_detail($id);
            $data['mock_ans'] = $this->exam_model->get_mock_answers($data['mocks']);
            $data['content'] = $this->load->view('content/mock_detail', $data, TRUE);
            $data['modal'] = $this->load->view('modals/update_question', $data, TRUE);
            $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
            $this->load->view('dashboard', $data);
        } else {
            show_404();
        }
    }

    public function view_categories($message = '')
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        $data = array();
        $data['class'] = 61; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        $data['categories'] = $this->exam_model->get_categories_with_author();
        $data['content'] = $this->load->view('content/view_all_categories', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function view_subcategories($message = '')
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        $data = array();
        $data['class'] = 63; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        $data['categories'] = $this->exam_model->get_categories();
        $data['sub_categories'] = $this->exam_model->get_subcategories_with_category();
        $data['mock_count'] = $this->exam_model->mock_count($data['sub_categories']);
        $data['course_count'] = $this->exam_model->course_count($data['sub_categories']);
        //  echo "<pre/>"; print_r($data['course_count']); exit();
        $data['content'] = $this->load->view('content/view_sub_categories', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function subcategory_form($message = '')
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }

        $data = array();
        $data['class'] = 64; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        $data['categories'] = $this->exam_model->get_categories();
        $data['content'] = $this->load->view('form/subcategory_form', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function category_form($message = '')
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }

        $data = array();
        $data['class'] = 62; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $data['content'] = $this->load->view('form/category_form', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function view_payment_history($message = '')
    {
        if ($this->session->userdata('user_role_id') > 2) {
            redirect(base_url("index.php/login_control/dashboard_control"));
        }
        $data = array();
        $data['class'] = 35; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        $data['payments'] = $this->admin_model->get_payment_history();
        $data['content'] = $this->load->view('content/view_payment_history', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function create_category()
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        $data = array();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('cat_name', 'Category', 'required|max_length[20]');
        if ($this->form_validation->run() != FALSE)
        {
            $category_name = $this->input->post('cat_name');

            $cat_id = $this->admin_model->create_category($category_name);

            if ($cat_id) {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Category added successfully.'
                        . '</div>';
            } else {
                $message = '<div class="alert alert-danger">Category ' . $category_name . ' already exist. Try new one.</div>';
            }
        }

        $this->session->set_flashdata('message',$message);
        redirect(base_url('index.php/admin_control/category_form'));
    }

    public function create_subcategory()
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('sub_cat_name', 'Category', 'required|max_length[20]');
        if ($this->form_validation->run() != FALSE)
        {
            $sub_cat_name = $this->input->post('sub_cat_name');
            $cat_id = $this->admin_model->create_subcategory($sub_cat_name);

            if ($cat_id)
            {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Sub category added successfully.'
                        . '</div>';
            } else {
                $message = '<div class="alert alert-danger">' . $sub_cat_name . ' already exist.</div>';
            }
        }
        $this->session->set_flashdata('message',$message);
        redirect(base_url('index.php/admin_control/subcategory_form'));
    }

    public function create_exam()
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('category', 'Category', 'required|integer');
            $this->form_validation->set_rules('mock_title', 'Mock Title', 'required|min_length[3]');
            $this->form_validation->set_rules('passing_score', 'Passing Score', 'required|integer|less_than[100]');
            $this->form_validation->set_rules('duration', 'Time Duration', 'required|min_length[5]|max_length[8]');
            $this->form_validation->set_rules('random_ques', 'Total Random Question', 'required|integer');

            if ($this->form_validation->run() == FALSE)
                redirect(base_url('index.php/admin_control/mock_form'));

            $exam_id = $this->admin_model->add_mock_title();

            if ($_FILES['feature_image']['name']) {
                $uploads_dir = './exam-images';
                $tmp_name = $_FILES["feature_image"]["tmp_name"];
                move_uploaded_file($tmp_name, "$uploads_dir/$exam_id.png");
            }

            if ($exam_id) {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Exam created successfully! Now creat questions.'
                        . '</div>';

                $this->session->set_flashdata('message',$message);
                // redirect(base_url('index.php/admin_control/question_form'));
                redirect(base_url('index.php/admin_control/add_question/'.$exam_id));

                // $this->question_form($exam_id);
            } else {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
                $this->session->set_flashdata('message',$message);
                redirect(base_url('index.php/admin_control/mock_form'));
            }
        }

        $userId = $this->session->userdata('user_id');
        $data = array();
        $data['class'] = 22; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        // $data['cat_id'] = $cat_id;
        $data['categories'] = $this->exam_model->get_categories();
        $data['content'] = $this->load->view('form/mock_form', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $this->load->view('dashboard', $data);

    }

    public function add_question($exam_id)
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        if ($this->input->post()) {
                 // echo "<pre>";                print_r($this->input->post());                exit();
            $this->load->library('form_validation');
            $this->form_validation->set_rules('question', 'Question', 'required');
            $this->form_validation->set_rules('right_ans[]', 'At least one correct answer', 'required');
            $this->form_validation->set_rules('ans_type', 'Answer Type', 'required');
            $this->form_validation->set_rules('options[1]', 'Option 1', 'required');
            $this->form_validation->set_rules('options[2]', 'Option 2', 'required');

            if ($this->form_validation->run() !== FALSE)
            {
                $file_name = ''; $file_type = '';
                if ($_FILES['media']['name']) {
                    $config['upload_path'] = './question-media/'.$this->input->post('media_type').'/';

                    if ($this->input->post('media_type') == 'image') {
                        $config['allowed_types'] = 'gif|jpg|png';
                    }elseif ($this->input->post('media_type') == 'video') {
                        $config['allowed_types'] = 'mp4|ogg|webm';
                    }elseif ($this->input->post('media_type') == 'audio') {
                        $config['allowed_types'] = 'application/ogg|mp3|wav';
                    }

                    $config['file_name'] = uniqid();
                    $config['overwrite'] = TRUE;

                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('media')) {
                        $error = array('error' => $this->upload->display_errors('<div class="alert alert-danger">', '</div>'));
                        $this->session->set_flashdata('message',$error['error']);
                        redirect(base_url('index.php/admin_control/add_question/'.$exam_id));
                    } else {
                        $upload_data = $this->upload->data();
                        $file_name = $this->input->post('media_type').'/'.$upload_data['file_name'];
                        $file_type = $this->input->post('media_type');
                    }
                }else if($this->input->post('media', TRUE)){
                    $file_name = $this->input->post('media');
                    $file_type = $this->input->post('media_type');
                }

                if ($this->admin_model->add_question($exam_id, $file_name, $file_type))
                {
                    $message = '<div class="alert alert-success alert-dismissable">'
                            . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                            . 'Question added successfully!'
                            . '</div>';
                } else {
                    $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
                }
            }
            $this->session->set_flashdata('message',$message);

            if ($this->input->post('done'))
                redirect(base_url('index.php/mock_detail/'.$exam_id));

            redirect(base_url('index.php/admin_control/add_question/'.$exam_id));
        }

        $data = array();
        $data['class'] = 22; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['categories'] = $this->exam_model->get_categories();
        $data['question_no'] = $this->exam_model->question_count($exam_id);
        $data['exam'] = $this->exam_model->get_mock_title($exam_id);
        $data['content'] = $this->load->view('form/question_form', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function activate_category($id)
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        if ($this->admin_model->activate_category($id)) {
            $message = '<div class="alert alert-success alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'Updated successfully!'
                    . '</div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'An ERROR occurred! Please try again.</div>';
        }
        $this->session->set_flashdata('message',$message);
        redirect(base_url('index.php/admin_control/view_categories'));
    }

    public function activate_subcategory($id)
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        if ($this->admin_model->activate_subcategory($id)) {
            $message = '<div class="alert alert-success alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'Updated successfully!'
                    . '</div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'An ERROR occurred! Please try again.</div>';
        }
        $this->session->set_flashdata('message',$message);
        redirect(base_url('index.php/admin_control/view_subcategories'));
    }

    public function update_category_name()
    {
        echo ($this->admin_model->update_category_name()) ? 'TRUE' : 'FALSE';
    }

    public function update_subcategory()
    {
        echo ($this->admin_model->update_subcategory()) ? 'TRUE' : 'FALSE';
    }

    public function update_mock_title()
    {
        echo ($this->admin_model->update_mock_title()) ? 'TRUE' : 'FALSE';
    }

    public function update_question()
    {
        if (!$this->session->userdata('log') || $this->session->userdata('user_role_id') > 4){
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('question', 'Question', 'required|min_length[8]|max_length[100]');
        $exam_id = $this->input->post('exam_id', TRUE);

        if ($this->form_validation->run() == FALSE)
        {
            $message = '<div class="alert alert-danger alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'An ERROR occurred! ' . validation_errors()
                    . '</div>';
        } elseif ($this->admin_model->update_question()) {
            $message = '<div class="alert alert-success alert-dismissable">'
                    . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                    . 'Updated successfully!'
                    . '</div>';
        } else {
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
        }

        $this->session->set_flashdata('message',$message);
        redirect(base_url('index.php/admin_control/view_my_mock_detail/'.$exam_id));
    }

    public function update_answer($ques_id)
    {
        echo ($this->admin_model->update_answer($ques_id)) ? 'TRUE' : 'FALSE';
    }

    public function change_password()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('old-pass', 'Current Password', 'required|min_length[6]');
        $this->form_validation->set_rules('new-pass', 'New Password', 'required|min_length[6]|matches[re-new-pass]');
        $this->form_validation->set_rules('re-new-pass', 'Re-type New Password', 'required|min_length[6]');
        if ($this->form_validation->run() != FALSE)
        {
            $this->load->model('user_model');
            $data = $this->user_model->get_user_info();

            if ($data->user_id != $this->session->userdata('user_id')) {
                $message = '<div class="alert alert-danger alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Your can\'t change other user\'s password!</div>';

            } elseif (($data->user_pass != md5($this->input->post('old-pass')))) {
                $message = '<div class="alert alert-danger alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Your old password doesn\'t match! Please try again.</div>';

            } elseif (($data->user_pass == md5($this->input->post('new-pass')))) {
                $message = '<div class="alert alert-danger alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'You entered your old password! Please try different one.</div>';
            } else {
                $info = array();
                $info['user_pass'] = md5($this->input->post('new-pass'));
                if ($this->admin_model->update_password($info)) {
                    $message = '<div class="alert alert-success alert-dismissable">'
                            . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                            . 'Password Changed successfully!'
                            . '</div>';
                } else {

                    $message = '<div class="alert alert-danger alert-dismissable">'
                            . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                            . 'An ERROR occurred! Please try again.</div>';
                }
            }
        }

        $this->session->set_flashdata('message',$message);
        redirect(base_url('index.php/admin_control/index'));

    }

    public function update_profile_info()
    {
        echo $this->admin_model->update_profile_info() ? 'TRUE' : 'FALSE';
    }

    public function mute_category($id)
    {
        if (!is_numeric($id)) return FALSE;

        $user_role_id = $this->session->userdata('user_role_id');

        if ($user_role_id > 3)
        {
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not Authorised person to do this!</div>';
        }else{
            if ($this->admin_model->mute_category($id)) {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'This category has muted successfully! No one can create new course/exam in this category.'
                        . '</div>';
            } else {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
            }
        }

        $this->session->set_flashdata('message',$message);
        redirect(base_url('index.php/admin_control/view_categories'));
    }

    public function mute_subcategory($id)
    {
        if (!is_numeric($id)) return FALSE;

        $user_role_id = $this->session->userdata('user_role_id');

        if ($user_role_id > 3)
        {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not Authorised person to do this!</div>';
        }else{

            if ($this->admin_model->mute_subcategory($id)) {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'This sub-category has muted successfully! No one can create new course/exam in this category.'
                        . '</div>';
            } else {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
            }
        }

        $this->session->set_flashdata('message',$message);
        redirect(base_url('index.php/admin_control/view_subcategories'));

    }

    public function delete_category($id)
    {
        if (!is_numeric($id)) {
            return FALSE;
        }

        if ($this->session->userdata('user_role_id') > 3) {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not Authorised person to do this!</div>';
        }else{
            $key = $this->admin_model->delete_category_name($id);
            if ($key == 'deleted') {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'The category has Deleted successfully!'
                        . '</div>';
            } elseif ($key == 'muted') {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'This category have subcategories, so can\'t be deleted but muted successfully! No one can create new exam in this category.'
                        . '</div>';
            } else {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
            }
        }
        $this->session->set_flashdata('message',$message);
        redirect(base_url('index.php/admin_control/view_categories'));
    }

    public function delete_subcategory($id)
    {
        if (!is_numeric($id)) return FALSE;

        if ($this->session->userdata('user_role_id') > 3) {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not Authorised person to do this!</div>';
        }else{
            $key = $this->admin_model->delete_subcategory_name($id);
            if ($key == 'deleted') {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'The category has Deleted successfully!'
                        . '</div>';
            } else {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
            }
        }
        $this->session->set_flashdata('message',$message);
        redirect(base_url('index.php/admin_control/view_subcategories'));
    }

    public function delete_exam($id)
    {
        if (!is_numeric($id)) return FALSE;

        $user_id = $this->session->userdata('user_id');
        $user_role_id = $this->session->userdata('user_role_id');

        if ($user_role_id <= 2) {
            if ($this->admin_model->delete_exam_with_all_questions($id)) {
                unlink('./exam-images/'.$id.'.png');

                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'The Exam has Deleted successfully with all related questions and answers!'
                        . '</div>';
            } else {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
            }
        } else {
            $author = $this->exam_model->get_mock_by_id($id);
            if (empty($author) OR (($user_role_id != 3) && ($author->user_id != $user_id))) {
                exit('<h2>You are not Authorised person to do this!</h2>');
            }
            if ($this->admin_model->mute_exam($id)) {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'The Exam is muted successfully! Admin will review the request.'
                        . '</div>';
            } else {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
            }
        }
        $this->session->set_flashdata('message', $message);
        redirect(base_url('index.php/admin_control/view_my_mocks'));
    }

    public function delete_question($id)
    {
        if (!is_numeric($id)) return FALSE;

        $author = $this->exam_model->get_question_by_id($id);

        if (empty($author) OR ($author->user_id != $this->session->userdata('user_id')) && $this->session->userdata('user_role_id') > 2) {
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not Authorised person to do this!</div>';
        }else{

            $exam_id = $author->exam_id;
            if ($this->admin_model->delete_question_with_answers($id))
            {
                if(file_exists("question-media/$author->media_link"))
                    unlink("question-media/$author->media_link");

                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Successfully deleted!'
                        . '</div>';
            } else {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
            }
        }
        $this->session->set_flashdata('message', $message);
        redirect(base_url('index.php/admin_control/view_my_mock_detail/'.$exam_id));
    }

    public function delete_answer($id)
    {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $author = $this->exam_model->get_answer_by_id($id);
        if (empty($author) OR ($author->user_id != $this->session->userdata('user_id'))) {
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
        }else{
            $ques_id = $author->exam_id;
            if ($this->admin_model->delete_answer($id)) {
                $message = '<div class="alert alert-success alert-dismissable">'
                        . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                        . 'Successfully Deleted!'
                        . '</div>';
            } else {
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
            }
        }
        $this->session->set_flashdata('message', $message);
        redirect(base_url('index.php/admin_control/view_my_mock_detail/'.$ques_id));
    }

    public function edit_mock_detail($id, $message = '')
    {
        if (!is_numeric($id)) show_404();

        $data = array();
        $data['mock'] = $this->admin_model->get_mock_detail($id);
        if (!$this->session->userdata('log') || ($this->session->userdata('user_role_id') > 2 && $data['mock_title']->user_id != $this->session->userdata('user_id')))
        {
            $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>You are not allowed to view this page.</div>';
            $this->session->set_flashdata('message', $message);
            redirect(base_url());
        }

        $data['class'] = 21; // class control value left digit for main manu rigt digit for submenu
        $data['header'] = $this->load->view('header/admin_head', '', TRUE);
        $data['top_navi'] = $this->load->view('header/admin_top_navigation', $data, TRUE);
        $data['sidebar'] = $this->load->view('sidebar/admin_sidebar', $data, TRUE);
        $data['message'] = $message;
        $data['ques_count'] = $this->exam_model->question_count_by_id($id);
        $data['content'] = $this->load->view('form/edit_mock_detail', $data, TRUE);
        $data['footer'] = $this->load->view('footer/admin_footer', $data, TRUE);
        $this->load->view('dashboard', $data);
    }

    public function update_exam($id, $message = '')
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('category', 'Category', 'required|integer');
        $this->form_validation->set_rules('mock_title', 'Mock Title', 'required|min_length[3]');
        $this->form_validation->set_rules('mock_syllabus', 'Syllabus', 'required');
        $this->form_validation->set_rules('duration', 'Time Duration', 'required|min_length[5]|max_length[8]');
        $this->form_validation->set_rules('random_ques', 'Total Random Question', 'required|integer');
        $this->form_validation->set_rules('passing_score', 'Passing Score', 'required|integer|less_than[100]');
        if ($this->form_validation->run() == FALSE)
            redirect(base_url('index.php/admin_control/edit_mock_detail/'.$id));

        if ($_FILES['feature_image']['name'])
        {
            $uploads_dir = './exam-images';
            $tmp_name = $_FILES["feature_image"]["tmp_name"];
            move_uploaded_file($tmp_name, "$uploads_dir/$id.png");
        }

        if($this->admin_model->update_mock($id)){
            $message = '<div class="alert alert-success alert-dismissable">'
                . '<button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>'
                . 'Exam updated successfully.'
                . '</div>';
        }else{
                $message = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="TRUE">&times;</button>An ERROR occurred! Please try again.</div>';
        }

        $this->session->set_flashdata('message',$message);
        redirect(base_url('index.php/mocks'));
    }

    public function get_subcategories_ajax($id)
    {
        $sub_cat = $this->admin_model->get_subcategories_by_cat_id($id);
        $str = '';
        foreach ($sub_cat as $value) {
            $str.='<option value="'.$value->id.'">'.$value->sub_cat_name.'</option>';
        }

        echo $str;
    }


}
