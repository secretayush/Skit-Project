<?php

use App\Libraries\Controller;
use App\Helpers\Session;
use App\Helpers\Mailer;
use App\Helpers\Validator;

class CompanyController extends Controller {
  private $mailer;
  public function __construct(){
    $this->userModel = $this->model('User');
    $this->roleModel = $this->model('Role');
    $this->jobModel = $this->model('Job');
    $this->examModel = $this->model('Exam');
    $this->logModel = $this->model('Log');
    $this->mailer = new Mailer();
    if(!Session::isset('user')) {
      Session::set('message', "Please login to continue!");
      Session::set('type', 'warning');
      header('location: /auth/login?continue='.$_GET['url']);
      die();
    }
    if(Session::user()->title != 'company') {
      Session::set('message', "You don't have access to this area!");
      Session::set('type', 'error');
      header('location: /');
      die();
    }
  }
  public function index() {
    $data = ['student' => $this->userModel->selectByRoleID(3)];
    $this->view("pages/company/index", $data);
  }
  public function hr() {
    $data = [];
    $errors = [];
    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $error = false;
      $data = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'contact_number' => trim($_POST['contact_number']),
        'employee_id' => trim($_POST['employee_id'])
      ];

      $errors = [
        'name_error' => '',
        'email_error' => '',
        'contact_number_error' => '',
        'employee_id_error' => ''
      ];

      $errors['name_error'] = Validator::nameValidate($_POST['name']);
      $errors['email_error'] = Validator::emailValidate($_POST['email']);
      $errors['contact_number_error'] = Validator::numberValidate($_POST['contact_number']);
      $errors['employee_id_error'] = Validator::emptyValidate($_POST['contact_number']);

      if($this->userModel->findUserByEmail($data['email'])) {
        $errors['email_error'] = "User with same email already exists!";
      }

      foreach($errors as $e) {
        if(!empty($e)) {
          $error = true;
          break;
        }
      }

      if (!$error) {
        $roleOb = $this->roleModel->findRoleByName('hr');
        if($roleOb) {
          $passwordRand = substr(md5(uniqid(rand(),true)),5,8);
          $password = $passwordRand . '@' .strtoupper(Session::user()->name);
          $data['password'] = password_hash($password, PASSWORD_DEFAULT);
          $data['company_id'] = Session::user()->id;
          if ($this->userModel->register($data, $roleOb)) {
            Session::set('message', 'Registration successful!');
            Session::set('type', 'success');
            // $this->logModel(Session::user()->name . ' has created a new HR Account.');
            $this->mailer->welcomeHR($this->userModel->findUserByEmail($data['email']), $password);
            header('location: /company');
            die();
          } else {
            Session::set('message', "Something went wrong!");
            Session::set('type', 'error');
          }
        } else {
          Session::set('message', "Something went wrong!");
          Session::set('type', 'error');
        }
      }
    }
    $this->view("pages/company/hr", array_merge($data, $errors));
  }
  public function job_post() {
    $data = [];
    $errors = [];
    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $error = false;
      $data = [
        'title' => trim($_POST['job_title']),
        'expiry_date' => trim($_POST['expiry_date']),
        'salary' => trim($_POST['job_salary']),
        'openings' => trim($_POST['job_openings']),
        'short_desc'=> trim($_POST['desc']),
        'company_id' => ''
      ];

      $errors = [
        'title_error' => '',
        'expiry_date_error' => '',
        'salary_error' => '',
        'opening_error' => '',
        'desc_error' => ''
      ];

      $errors['title_error'] = Validator::emptyValidate($_POST['job_title']);
      $errors['expiry_date_error'] = Validator::emptyValidate($_POST['expiry_date']);
      $errors['opening_error'] = Validator::digitValidate($_POST['job_openings']);
      $errors['salary_error'] = Validator::digitValidate($_POST['job_salary']);
      $errors['desc_error'] = Validator::emptyValidate($_POST['desc']);

      $data['company_id'] = Session::user()->id;

      foreach($errors as $e) {
        if(!empty($e)) {
          $error = true;
          break;
        }
      }

      if (!$error) {
        if ($this->jobModel->postjobs($data)) {
          Session::set('message', 'Job Posted successfully!');
          Session::set('type', 'success');
          header('location: /company');
          die();
        } else {
          Session::set('message', "Something went wrong!");
          Session::set('type', 'error');
        }
      } else {
        Session::set('message', "Something went wrong!");
        Session::set('type', 'error');
      }
    }
    $this->view("pages/company/job_post", array_merge($data,$errors));
  }
  public function stu_applied() {
    $data =['student' => $this->userModel->selectByRoleID(3)]; //for student role id = 3
    $this->view("pages/company/stu_applied",$data);
  }
  public function approveExam($examId) {
    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $exam = $this->examModel->find($examId);
      if(!$exam) {
          Session::set('message', "Exam not found!");
          Session::set('type', 'warning');
      } else {
        if($exam->is_approved == 1) {
          Session::set('message', "Exam is already approved!");
          Session::set('type', 'warning');
        } else {
          if($this->examModel->changeStatus($examId, 1)) {
            Session::set('message', 'Exam has been successfully approved!');
            Session::set('type', 'success');
            $this->logModel->create(Session::user()->name . ' has approved the exam ' . $examId);
            $this->mailer->examApproved($this->userModel->find($exam->added_by), $exam);
          } else {
            Session::set('message', "Something went wrong!");
            Session::set('type', 'error');
          }
        }
      }
    }
    header('location: /company');
  }

  public function rejectExam($examId) {
    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $data = [
        'remarks' => trim($_POST['remarks']),
        'remarks_error' => '',
      ];

      $data['remarks_error'] = Validator::emptyValidate($_POST['remarks']);

      if(empty($data['remarks_error'])) {
        $exam = $this->examModel->find($examId);
        if(!$exam) {
            Session::set('message', "Exam not found!");
            Session::set('type', 'warning');
        } else {
          if($exam->is_approved != 0) {
            Session::set('message', "Something went wrong!");
            Session::set('type', 'warning');
          } else {
            if($this->examModel->changeStatus($examId, 2, $data['remarks'])) {
              Session::set('message', 'Exam has been successfully rejected!');
              Session::set('type', 'success');
              $this->mailer->examRejected($this->userModel->find($exam->added_by), $exam, $data['remarks']);
            } else {
              Session::set('message', "Something went wrong!");
              Session::set('type', 'error');
            }
          }
        }
      }
    }
    header('location: /company');
  }

  public function exam($page=1) {
    $data =['exams' => $this->examModel->getExams(Session::user()->id, true, $page, 10)];
    $this->view("pages/company/exam", $data);
  }
}
