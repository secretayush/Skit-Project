<?php
/*
  * Controller for system admin role.
 */
use App\Libraries\Controller;
use App\Helpers\Session;
use App\Helpers\Mailer;
use App\Helpers\Validator;

class AdminController extends Controller {
  /*
    * @var object $mailer - Mailer class object
   */
  private $mailer;
  public function __construct() {
    $this->userModel = $this->model('User');
    $this->jobModel = $this->model('Job');
    $this->roleModel = $this->model('Role');
    $this->logModel = $this->model('Log');
    $this->mailer = new Mailer();
    if(!Session::isset('user')) {
      Session::set('message', "Please login to continue!");
      Session::set('type', 'warning');
      header('location: /auth/login?continue='.$_GET['url']);
      die();
    }
    if(!in_array(Session::user()->title, ['admin', 'moderator'])) {
      Session::set('message', "You don't have access to this area!");
      Session::set('type', 'error');
      header('location: /');
      die();
    }
  }

  public function jobs($page=1) {
    $data = ['job_openings' => $this->jobModel->get(true, $page, 10)];
    $this->view("pages/admin/jobs", $data);
  }

  /*
    * Renders the admin dashboard homepage.
    * @param int $page - Page number for pagination.
   */
  public function index($page=1) {
    $data = [
      'logs' => $this->logModel->get(true, $page, 10)
    ];
    $this->view("pages/admin/index", $data);
  }
  /*
    * Activates the company user's account.
    * @param int $id - ID of the company user.
   */
  public function activate($id) {
    $user = $this->userModel->find($id);
    if(!$user) {
        Session::set('message', "User not found!");
        Session::set('type', 'warning');
    } else {
      if($user->account_status == 1) {
        Session::set('message', "User account is already active!");
        Session::set('type', 'warning');
      } else {
        if($this->userModel->updateAccountStatus($id, 1)) {
          Session::set('message', $user->name . '\'s account has been activated!');
          Session::set('type', 'success');
          $this->logModel->create(Session::user()->name . '(' . Session::user()->display_name . ') has acvtivated ' . $user->name . '(' . $user->id . ')\'s account.');
          $this->mailer->accountActivated($user);
        } else {
          Session::set('message', "Something went wrong!");
          Session::set('type', 'error');
        }
      }
    }
    header('location: /admin');
  }
  /*
    * Suspends the company user's account.
    * @param int $id - ID of the company user.
   */
  public function ban($id) {
    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $data = [
        'remarks' => trim($_POST['remarks']),
        'remarks_error' => '',
      ];

      $data['remarks_error'] = Validator::emptyValidate($_POST['remarks']);

      if(empty($data['remarks_error'])) {
        $user = $this->userModel->find($id);
        if(!$user) {
            Session::set('message', "User not found!");
            Session::set('type', 'warning');
        } else {
          if($this->userModel->updateAccountStatus($id, 2, $data['remarks'])) {
            Session::set('message', $user->name . ' has been suspended!');
            Session::set('type', 'success');
            $this->logModel->create(Session::user()->name . '(' . Session::user()->display_name . ') has suspended ' . $user->name . '(' . $user->id . ')\'s account.');
            $this->mailer->accountBanned($this->userModel->find($id));
          } else {
            Session::set('message', "Something went wrong!");
            Session::set('type', 'error');
          }
        }
      } else {
        Session::set('message', "Please enter remakrs.");
        Session::set('type', 'error');
      }
    }
    header('location: /admin');
  }
  /*
    * Rejects the company user's account request.
    * @param int $id - ID of the company user.
   */
  public function reject($id) {
    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $data = [
        'remarks' => trim($_POST['remarks']),
        'remarks_error' => '',
      ];

      $data['remarks_error'] = Validator::emptyValidate($_POST['remarks']);

      if(empty($data['remarks_error'])) {
        $user = $this->userModel->find($id);
        if(!$user) {
            Session::set('message', "User not found!");
            Session::set('type', 'warning');
        } else {
          if($this->userModel->updateAccountStatus($id, 3, $data['remarks'])) {
            Session::set('message', $user->name . ' has been rejected!');
            Session::set('type', 'success');
            $this->logModel->create(Session::user()->name . '(' . Session::user()->display_name . ') has rejected ' . $user->name . '(' . $user->id . ')\'s account request.');
            $this->mailer->accountRejected($this->userModel->find($id));
          } else {
            Session::set('message', "Something went wrong!");
            Session::set('type', 'error');
          }
        }
      } else {
        Session::set('message', "Please enter remakrs.");
        Session::set('type', 'error');
      }
    }
    header('location: /admin');
  }
  /*
    * Approves the job.
    * @param int $id - ID of the job.
   */
  public function approveJob($id) {
    $job = $this->jobModel->find($id);
    if(!$job) {
        Session::set('message', "Job not found!");
        Session::set('type', 'warning');
    } else {
      if($job->is_approved == 1) {
        Session::set('message', "Job is already active!");
        Session::set('type', 'warning');
      } else {
        if($this->jobModel->changeStatus($id, 1)) {
          Session::set('message', 'Job activated!');
          Session::set('type', 'success');
          $this->logModel->create(Session::user()->name . '(' . Session::user()->display_name . ') has approved job id ' . $id);
          $this->mailer->jobOpeningApproved($this->userModel->find($job->company_id), $job);
        } else {
          Session::set('message', "Something went wrong!");
          Session::set('type', 'error');
        }
      }
    }
    header('location: /admin');
  }
  /*
    * Rejects the job posted.
    * @param int $jobId - ID of the job.
   */
  public function rejectJob($jobId) {
    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $data = [
        'remarks' => trim($_POST['remarks']),
        'remarks_error' => '',
      ];

      $data['remarks_error'] = Validator::emptyValidate($_POST['remarks']);

      if(empty($data['remarks_error'])) {
        $job = $this->jobModel->find($jobId);
        if(!$job) {
            Session::set('message', "Job not found!");
            Session::set('type', 'warning');
        } else {
          if($job->is_approved != 0) {
            Session::set('message', "Something went wrong!");
            Session::set('type', 'warning');
          } else {
            if($this->jobModel->changeStatus($jobId, 2, $data['remarks'])) {
              Session::set('message', 'Job has been successfully rejected!');
              Session::set('type', 'success');
              $this->logModel->create(Session::user()->name . '(' . Session::user()->display_name . ') has rejected job id ' . $id);
              $this->mailer->jobOpeningRejected($this->userModel->find($job->company_id), $job, $data['remarks']);
            } else {
              Session::set('message', "Something went wrong!");
              Session::set('type', 'error');
            }
          }
        }
      }
    }
    header('location: /admin/jobs');
  }

  public function companies($page=1) {
    $data = ['companies' => $this->userModel->selectCompanies(true, $page, 10)];
    $this->view("pages/admin/companies", $data);
  }

  public function students($page=1) {
    $data = ['students' => $this->userModel->selectStudents(true, $page, 10)];
    $this->view("pages/admin/students", $data);
  }
  public function createModerator() {
    $data = [];
    $errors = [];
    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $error = false;
      $data = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'contact_number' => trim($_POST['contact_number']),
      ];

      $errors = [
        'name_error' => '',
        'email_error' => '',
        'contact_number_error' => '',
      ];

      $errors['name_error'] = Validator::nameValidate($_POST['name']);
      $errors['email_error'] = Validator::emailValidate($_POST['email']);
      $errors['contact_number_error'] = Validator::numberValidate($_POST['contact_number']);

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
        $roleOb = $this->roleModel->findRoleByName('moderator');
        if($roleOb) {
          $passwordRand = substr(md5(uniqid(rand(),true)),5,8);
          $password = $passwordRand . '@' .strtoupper("gethired");
          $data['password'] = password_hash($password, PASSWORD_DEFAULT);
          if ($this->userModel->register($data, $roleOb)) {
            Session::set('message', 'Registration successful!');
            Session::set('type', 'success');
            $this->logModel->create(Session::user()->name . '(' . Session::user()->display_name . ') has created a new moderator.');
            $this->mailer->welcomeModerator($this->userModel->findUserByEmail($data['email']), $password);
            header('location: /admin');
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
    $this->view("pages/admin/moderator", array_merge($data, $errors));
  }
}
