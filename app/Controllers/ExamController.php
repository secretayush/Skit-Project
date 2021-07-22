<?php

use App\Libraries\Controller;
use App\Helpers\Session;
use App\Helpers\Validator;
/**
 * ExamController class controls all the funtions required in Exam feature of the project.
 */
class ExamController extends Controller {
  public function __construct(){
    $this->userModel = $this->model('User');
    $this->examModel = $this->model('Exam');
    $this->questionModel = $this->model('Question');
    if(!Session::isset('user')) {
      Session::set('message', "Please login to continue!");
      Session::set('type', 'warning');
      header('location: /auth/login?continue='.$_GET['url']);
      die();
    }
    if(!in_array(Session::user()->title, ['company', 'hr'])) {
      Session::set('message', "You don't have access to this area!");
      Session::set('type', 'error');
      header('location: /');
      die();
    }
  }

  /**
   * This function is used to browse all the exams and their questions
   * @param  [string] $id [id of the exam]
   * @return [redirect]     [redirects user to the exam page]
   */
  public function browse($id) {
    $exam = $this->examModel->find($id);
    if(!$exam) {
      $this->view("pages/404");
      die();
    } else {
      if($exam->company_id == Session::user()->id || $exam->company_id == Session::user()->company_id) {
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
    $this->view("pages/exams/browse", $data);
  }

  /**
   * This function is used to redirect the user to edit exam page.
   * If admin rejects an exam, HR can edit it.
   * @param  [string] $id [exam id]
   * @return [redirect]     [After successful edit, hr is redirected to its homepage]
   */
  public function edit($id) {
    $exam = $this->examModel->find($id);
    if(!$exam) {
      $this->view("pages/404");
      die();
    } else {
      if($exam->is_approved == 2 && $exam->added_by == Session::user()->id) {
        $data['exam'] = $exam;
        $questions = $this->questionModel->getExamQuestions($exam->id);
        $data['questions'] = $questions;

        $errors = [];
        if($_SERVER['REQUEST_METHOD'] == "POST") {
          $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

          $error = false;
          $data = array_merge($data, [
            'name' => trim($_POST['name_exam']),
            'description' => trim($_POST['desc_exam']),
            'duration' => trim($_POST['duration']),
            'buffer_time' => trim($_POST['buffer_time']),
            'has_negative_marking' => trim($_POST['has_negative_marking']),
          ]);

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
            $this->examModel->update($id, $data);
            foreach($_POST['question'] as $key => $question) {
              $questionData = [
                'description' => $question,
                'option_one'  => $_POST['op1'][$key],
                'option_two'  => $_POST['op2'][$key],
                'option_three' => $_POST['op3'][$key],
                'option_four' => $_POST['op4'][$key],
                'correct_option' => $_POST['correct_option'][$key],
                'exam_id' => $id,
              ];
              $this->questionModel->create($questionData);
            }
            Session::set('message', 'Question paper updated successfully!');
            Session::set('type', 'success');
            header('location: /hr');
            die();
          }
        }

      } else {
        Session::set('message', "You don't have access to this area!");
        Session::set('type', 'error');
        header('location: /');
        die();
      }
    }
    $this->view("pages/exams/edit", $data);
  }
}
