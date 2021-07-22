<?php
/*
  * Controller to reset password.
 */
use App\Libraries\Controller;
use App\Helpers\Session;
use App\Helpers\Validator;
use App\Helpers\Mailer;

class PasswordController extends Controller {
  /*
    * @var object $mailer - Mailer class object.
   */
  private $mailer;
  public function __construct() {
    $this->userModel = $this->model('User');
    $this->logModel = $this->model('Log');
    $this->mailer = new Mailer();
    if(Session::isset('user')) {
      header('location: /');
      die();
    }
  }
  /*
    * Generates a password reset link
    * and mails it to the user to verify
    * their email address.
   */
  public function forgot() {
    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'email' => trim($_POST['email']),
        'email_error' => '',
      ];

      $data['email_error'] = Validator::emailValidate($_POST['email']);

      if(empty($data['email_error'])) {
        $user = $this->userModel->findUserByEmail($data['email']);
        if($user) {
           $expires_on = mktime(date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y"));
           $expires_on_date = date("Y-m-d H:i:s", $expires_on);
           $emailHash = md5(4096+$user->email);
           $emailSalt = substr(md5(uniqid(rand(),true)),5,15);
           $token = $emailHash . $emailSalt;
           if($this->userModel->forgotPassword($user->email, $token, $expires_on_date)) {
            $this->logModel->create($user->name . '(' . $user->email . ') has requested a password reset.');
            $this->mailer->forgotPassword($user, $token);
            Session::set('message', 'Password reset link has been sent to <b>'.$user->email.'</b>, please check your email, this link will be valid for 24 hours!');
            Session::set('type', 'success');
           }
        } else {
          $data['email_error'] = "Account with this email id not found!";
        }
      }
    }
    $this->view("pages/password/forgot", $data);
  }
  /*
    * Verifies the email and token,
    * and resets the password.
    * @param string $email - Email passed in URL param
    * @param string $token - Reset token passed in URL param
   */
  public function reset($email, $token) {
    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'password' => trim($_POST['password']),
        'confirm_password' => trim($_POST['confirm_password']),
        'password_error' => '',
        'confirm_passsword_error' => '',
      ];

      $data['password_error'] = Validator::passwordValidate($_POST['password']);
      $data['confirm_password_error'] = Validator::confpasswordValidate($_POST['password'],$_POST['confirm_password']);

      if(empty($data['password_error']) && empty($data['confirm_passsword_error'])) {
        $resetRequest = $this->userModel->findResetRequest($email, $token);
        if($resetRequest) {
          $now = date("Y-m-d H:i:s");
          if($resetRequest->expires_on >= $now) {
            $user = $this->userModel->findUserByEmail($email);
            if($user) {
              if($this->userModel->resetPassword($user->email, password_hash($data['password'], PASSWORD_DEFAULT))) {
                Session::set('message', "Password reset successful!");
                Session::set('type', 'success');
                $this->logModel->create($user->name . '(' . $user->email . ')\'s password was reset.');
                $this->mailer->passwordReset($user);
                $this->userModel->deleteResetRequest($user->email, $token);
                header('location: /auth/login');
                die();
              }
            } else {
              Session::set('message', "Something went wrong!");
              Session::set('type', 'error');
            }
          } else {
            Session::set('message', "The reset link has expired!");
            Session::set('type', 'error');
          }
        } else {
          Session::set('message', "The reset link seems to be invalid or malformed!");
          Session::set('type', 'error');
        }
      }
    }
    $data['reset_email'] = $email;
    $data['reset_token'] = $token;
    $this->view("pages/password/reset", $data);
  }
}
