<?php

use App\Libraries\Controller;
use App\Helpers\Validator;
use App\Helpers\Session;
use App\Helpers\Mailer;

class StudentController extends Controller {
  private $mailer;
  public function __construct(){
    $this->userModel = $this->model('User');
    $this->roleModel = $this->model('Role');
    $this->jobModel = $this->model('Job');
    $this->examModel = $this->model('Exam');
    $this->logModel = $this->model('Log');
    $this->questionModel = $this->model('Question');
    $this->mailer = new Mailer();
    if(!Session::isset('user')) {
      Session::set('message', "Please login to continue!");
      Session::set('type', 'warning');
      header('location: /auth/login?continue='.$_GET['url']);
      die();
    }
    if(Session::user()->title != 'student') {
      Session::set('message', "You don't have access to this area!");
      Session::set('type', 'error');
      header('location: /');
      die();
    }
  }
  public function index() {
    $jobs = $this->jobModel->showjobs();
    foreach ($jobs as $job) {
      $job->applied = [];
      foreach ($this->jobModel->check($job->id) as $value) {
        array_push($job->applied , $value->user_id);
      }
    }
    $this->view("pages/student/index",$jobs);
  }

  public function myapplications() {
    $user_id = Session::user()->id;
    $data = $this->jobModel->showappliedstudents($user_id);
    $this->view("pages/student/myapplications",$data);
  }

  public function apply($job_id){
    $user_id = Session::user()->id;
    $data = $this->jobModel->showappliedstudents($user_id);
    if($this->userModel->findByJobID($job_id, $data)) {
      Session::set('message', "You have already registered!");
      Session::set('type', 'error');
      header('location: /student/');
      die();
    }
    if ($this->jobModel->applyjobs($job_id,$user_id)) {
      Session::set('message', 'Registration successful!');
      Session::set('type', 'success');
      $data = $this->jobModel->showappliedstudents($user_id);
      $this->logModel->create(Session::user()->name . '(' . Session::user()->id . ') has applied for job id ' . $job_id);
      $this->mailer->applyStudent(Session::user(), $this->jobModel->find($job_id));
      header('location: /student/index');
      die();
    } else {
      Session::set('message', "Something went wrong!");
      Session::set('type', 'error');
    }
  }
  public function start($token) {
    $exam = $this->examModel->getExamFromToken($token);
    if(!$exam) {
      $this->view("pages/404");
      die();
    } else {
      if($exam->user_id == Session::user()->id) {
        if($exam->status != 0) {
          Session::set('message', "You don't have access to this area!");
          Session::set('type', 'error');
          header('location: /');
          die();
        }
        $now = date('Y-m-d H:i:s', strtotime("now"));
        if($now < $exam->exam_time || $now > date('Y-m-d H:i:s', strtotime("+".$exam->buffer_time." minutes", strtotime($exam->exam_time)))) {
          Session::set('message', "You cannot start the exam now!");
          Session::set('type', 'error');
          header('location: /');
          die();
        }
        $startedAt = date('Y-m-d H:i:s', strtotime("now"));
        if($this->examModel->startExam($token, $startedAt)) {
          header('location: /student/test/'.$token);
        } else {
          Session::set('message', "Something wen't wrong, please contact the administrator!");
          Session::set('type', 'error');
          header('location: /');
        }
      } else {
        Session::set('message', "You don't have access to this area!");
        Session::set('type', 'error');
        header('location: /');
        die();
      }
    }
  }
  public function test($token) {
    $exam = $this->examModel->getExamFromToken($token);
    if($_SERVER['REQUEST_METHOD'] == "POST") {
      foreach($_POST['question_id'] as $question) {
        $answer = $_POST['ans'][$question];
        $this->examModel->registerAnswer($exam->id, Session::user()->id, $answer);
      }
      $this->examModel->stopExam($token);
      Session::set('message', "Your response was recorded!");
      Session::set('type', 'info');
      header('location: /');
      die();
    }
    if(!$exam) {
      $this->view("pages/404");
      die();
    } else {
      if($exam->user_id == Session::user()->id) {
        // if($exam->status != 1) {
        //   Session::set('message', "You don't have access to this area!");
        //   Session::set('type', 'error');
        //   header('location: /');
        //   die();
        // }
        $data['exam'] = $exam;
        $questions = $this->questionModel->getExamQuestions($exam->id);
        $data['questions'] = $questions;
      } else {
        Session::set('message', "You don't have access to this area!");
        Session::set('type', 'error');
        header('location: /');
        die();
      }
    }
    $this->view("pages/student/test", $data);
  }
  public function upcomingExams()
  {
    $user_id = Session::user()->id;
    $data = $this->jobModel->showallotedexams($user_id);
    $this->view("pages/student/upcomingexams",$data);
  }
}

