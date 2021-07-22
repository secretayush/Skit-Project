<?php

use App\Libraries\Controller;
use App\Helpers\Session;
use App\Helpers\Validator;
use App\Helpers\Mailer;

/**
 * HRController controls all the functions and logics of HR feature of the project.
 */
class HrController extends Controller {
  private $mailer;
  public function __construct(){
    $this->userModel = $this->model('User');
    $this->examModel = $this->model('Exam');
    $this->jobModel = $this->model('Job');
    $this->logModel = $this->model('Log');
    $this->questionModel = $this->model('Question');
    $this->mailer = new Mailer();
    if(!Session::isset('user')) {
      Session::set('message', "Please login to continue!");
      Session::set('type', 'warning');
      header('location: /auth/login?continue='.$_GET['url']);
      die();
    }
    if(Session::user()->title != 'hr') {
      Session::set('message', "You don't have access to this area!");
      Session::set('type', 'error');
      header('location: /');
      die();
    }
  }

  /**
   * This function redirects user to the index page of HR.
   * @param  integer $page [HR index page number]
   * @return redirects user to HR index page.
   */
  public function index($page=1) {
    $data =['student' => $this->userModel->selectByRoleID(3), 'exams' => $this->examModel->getExams(Session::user()->company_id, true, $page, 10)];
    $this->view("pages/hr/index", $data);
  }

  /**
   * Redirects user to exam page. HR can create exams here
   * @return On successful submission HR is redirected to index page
   */
  public function exam() {
    $data = [];
    $errors = [];
    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

      $error = false;
      $data = [
        'name' => trim($_POST['name_exam']),
        'description' => trim($_POST['desc_exam']),
        'duration' => trim($_POST['duration']),
        'buffer_time' => trim($_POST['buffer_time']),
        'has_negative_marking' => trim($_POST['has_negative_marking']),
      ];

      $errors = [
        'title_error' => '',
        'desc_error' => '',
        'duration_error' => '',
        'buffer_time_error' => '',
      ];

      $errors['title_error'] = Validator::emptyValidate($_POST['name_exam']);
      $errors['desc_error'] = Validator::emptyValidate($_POST['desc_exam']);
      $errors['duration_error'] = Validator::emptyValidate($_POST['duration']);
      $errors['buffer_time_error'] = Validator::emptyValidate($_POST['buffer_time']);

      foreach($errors as $e) {
        if(!empty($e)) {
          $error = true;
          break;
        }
      }

      if (!$error) {
        $data['company_id'] = Session::user()->company_id;
        $data['added_by'] = Session::user()->id;
        $examId = $this->examModel->create($data);
        foreach($_POST['question'] as $key => $question) {
          $questionData = [
            'description' => $question,
            'option_one'  => $_POST['op1'][$key],
            'option_two'  => $_POST['op2'][$key],
            'option_three' => $_POST['op3'][$key],
            'option_four' => $_POST['op4'][$key],
            'correct_option' => $_POST['correct_option'][$key],
            'exam_id' => $examId,
          ];
          $this->questionModel->create($questionData);
        }
        $this->logModel->create(Session::user()->name . ' has created a new exam.');
        $this->mailer->examCreated(Session::user(), $this->userModel->find(Session::user()->company_id), $this->examModel->find($examId));
        Session::set('message', 'Question paper created successfully!');
        Session::set('type', 'success');
        header('location: /hr');
        die();
      }
    }
    $this->view("pages/hr/exam");
  }
  /**
  * This function is used to show student database.
  * The details of all the students who have applied for the jobs of the company.
  * @return redirects HR to studentdatabase page
  */
  public function studentdatabase(){
    $company = $this->userModel->find(Session::user()->company_id);
    $data = $this->jobModel->showappliedjobs($company->name);
    $this->view("pages/hr/studentdatabase",$data);
  }

  /**
   * This function is used to accept a student application
   * @param  [string] $user_id ID of student applying
   * @param  [string] $job_id  Job ID for the applied job
   * @return [shows a message on successful accepting and sends a mail to student
   * If the student is already accepted it shows a message to the HR.
   * Redirects to studentdatabase page.
   */
  public function accept($user_id,$job_id){
    $user = $this->jobModel->findstudent($user_id,$job_id);
    if(!$user) {
        Session::set('message', "User not found!");
        Session::set('type', 'warning');
    } else {
      if($user->status == 1) {
        Session::set('message', "Student is already accepted!");
        Session::set('type', 'warning');
      } else {
        if($this->jobModel->updateAccountStatus($user_id, $job_id, 1)) {
          Session::set('message', $user->name . '\'s account has been activated!');
          Session::set('type', 'success');
          $this->logModel->create(Session::user()->name . ' has accepted ' . $user->name . ' for a job opening, job id ' . $job_id);
          $this->mailer->studentAccepted($user);
        } else {
          Session::set('message', "Something went wrong!");
          Session::set('type', 'error');
        }
      }
    }
    header('location: /hr/studentdatabase');
  }
  /**
   * This function is used to reject a student application
   * @param  [string] $user_id ID of student applying
   * @param  [string] $job_id  Job ID for the applied job
   * @return [shows a message on rejecting and sends a mail to student
   * If the student is already rejected it shows a message to the HR.
   * Redirects to studentdatabase page.
   */
  public function reject($user_id,$job_id){
    $user = $this->jobModel->findstudent($user_id,$job_id);
    if(!$user) {
        Session::set('message', "User not found!");
        Session::set('type', 'warning');
    } else {
      if($user->status == -1) {
        Session::set('message', "Student is already rejected!");
        Session::set('type', 'warning');
      } else {
        if($this->jobModel->updateAccountStatus($user_id, $job_id, -1)) {
          Session::set('message', $user->name . '\'s account has been rejected!');
          Session::set('type', 'success');
          $this->logModel->create(Session::user()->name . ' has rejected ' . $user->name . ' for a job opening, job id ' . $job_id);
          $this->mailer->studentRejected($user);
        } else {
          Session::set('message', "Something went wrong!");
          Session::set('type', 'error');
        }
      }
    }
    header('location: /hr/studentdatabase');

  }

  /**
   * This function is used to shortlist a student application
   * @param  [string] $user_id ID of student applying
   * @param  [string] $job_id  Job ID for the applied job
   * @return [shows a message on shortlisting and sends a mail to student
   * If the student is already shortlisted it shows a message to the HR.
   * Redirects to studentdatabase page.
   */
  public function shortlist($user_id,$job_id){
    $user = $this->jobModel->findstudent($user_id,$job_id);
    if(!$user) {
        Session::set('message', "User not found!");
        Session::set('type', 'warning');
    } else {
      if($user->status == 2) {
        Session::set('message', "Student is already shortlisted!");
        Session::set('type', 'warning');
      } else {
        if($this->jobModel->updateAccountStatus($user_id, $job_id, 2)) {
          Session::set('message', $user->name . '\'s account has been shortlisted!');
          Session::set('type', 'success');
          $this->logModel->create(Session::user()->name . ' has shortlisted ' . $user->name . ' for a job opening, job id ' . $job_id);
          $this->mailer->studentShortlisted($user);
        } else {
          Session::set('message', "Something went wrong!");
          Session::set('type', 'error');
        }
      }
    }
    header('location: /hr/studentdatabase');
  }

  /**
   * This function gets a list of all the shortlisted students
   * @return redirects to showshortlisted page that shows details of all the shortlisted students
   */
  public function showshortlisted(){
    $company = $this->userModel->find(Session::user()->company_id);
    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
       foreach($_POST['send'] as $id) {
        $d = explode('|',$id);
        $user= $this->userModel->find($d[0]);
        $userHash = md5(4096+$d[0]);
        $userSalt = substr(md5(uniqid(rand(),true)),5,15);
        $token = $userHash . $userSalt;
        $job_id = $d[1];
        $this->jobModel->allotExam($d[0], $_POST['exam_id'], $_POST['exam_time'], $token);
        $this->logModel->create(Session::user()->name . '(' . Session::user()->employee_id . ') of ' . $company->name . ' has allotted an exam to ' . $user->name . '(' . $user->id . ')');
        $this->mailer->allotExam($user, $_POST['exam_time'], $token);
        $this->jobModel->updateAccountStatus($d[0], $job_id, 3);
      }
    }
    $data = [
      'shortlisted' => $this->jobModel->showshortlisted($company->name),
      'exams' => $this->jobModel->showexams($company->id)
    ];
    $this->view("pages/hr/showshortlisted", $data);
  }

  public function showresults(){
    $company = $this->userModel->find(Session::user()->company_id);

    $exams = $this->examModel->showresults($company->id);

    foreach($exams as $exam) {
      $result = 0;
      $hasNegative = $exam->has_negative_marking == 1 ? true : false;
      $questions = $this->questionModel->getExamQuestions($exam->id);
      $studentAnswers = $this->examModel->getStudentAnswers($exam->id, $exam->user_id);
      foreach($questions as $key => $q) {
        if($q->correct_option == $studentAnswers[$key]->answer)
          $result++;
        else if($hasNegative)
          $result--;
      }
      $exam->result = $result;
    }

    $data = [
      'exams' => $exams
    ];
    $this->view("pages/hr/showresults", $data);
  }
}
