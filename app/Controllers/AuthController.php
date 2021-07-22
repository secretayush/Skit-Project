<?php
/*
  * Controller for authentication.
 */
use App\Libraries\Controller;
use App\Helpers\Validator;
use App\Helpers\Session;
use App\Helpers\Mailer;

use App\Helpers\Auth\Google;


class AuthController extends Controller {
  /*
    * @var object $mailer - Mailer class object
   */
  private $mailer;
  public function __construct() {
    $this->userModel = $this->model('User');
    $this->roleModel = $this->model('Role');
    $this->logModel = $this->model('Log');
    $this->mailer = new Mailer();
  }
  /*
    * Allows users to sign up to the platform.
    * @param string $role - Role the user is
    * signing up for.
   */
  public function signup($role) {
    if(Session::isset('user')) {
      header('location: /');
    }

    $data = [];
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $error = false;
        $data = [
          'name' => trim($_POST['name']),
          'email' => trim($_POST['email']),
          'contact_number' => trim($_POST['contact_number']),
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),
        ];

        $errors = [
          'name_error' => '',
          'email_error' => '',
          'contact_number_error' => '',
          'confirm_password_error' => '',
        ];

        $errors['name_error'] = Validator::nameValidate($_POST['name']);
        $errors['email_error'] = Validator::emailValidate($_POST['email']);
        $errors['contact_number_error'] = Validator::numberValidate($_POST['contact_number']);
        $errors['password_error'] = Validator::passwordValidate($_POST['password']);
        $errors['confirm_password_error'] = Validator::confpasswordValidate($_POST['password'],$_POST['confirm_password']);

        if($this->userModel->findUserByEmail($data['email'])) {
          $errors['email_error'] = "User with same email already exists!";
        }

        if($role == 'company') {
          $data = array_merge($data, [
            'company_identifier' => trim($_POST['company_identifier']),
          ]);
          $errors = array_merge($errors, [
            'company_identifier_error' => '',
          ]);
          $errors['company_identifier_error'] = Validator::idValidate($_POST['company_identifier']);
        } else if ($role == 'student') {
          $data = array_merge($data, [
            'college_name' => trim($_POST['college_name']),
            'college_id' => trim($_POST['college_id']),
            'current_cgpa' => trim($_POST['current_cgpa']),
            'active_backlogs' => trim($_POST['active_backlogs']),
            'tenth_marks' => trim($_POST['tenth_marks']),
            'twelveth_marks' => trim($_POST['twelveth_marks']),
            'resume' => $_FILES['resume'],
          ]);

          $errors = array_merge($errors, [
            'college_name_error' => '',
            'college_id_error' => '',
            'current_cgpa_error' => '',
            'active_backlogs_error' => '',
            'tenth_marks_error' => '',
            'twelveth_marks_error' => '',
            'resume_error' => ''
          ]);

          $errors['college_name_error'] = Validator::emptyValidate($_POST['college_name']);
          $errors['college_id_error'] = Validator::emptyValidate($_POST['college_id']);
          $errors['current_cgpa_error'] = Validator::emptyValidate($_POST['current_cgpa']);
          $errors['active_backlogs_error'] = Validator::emptyValidate($_POST['active_backlogs']);
          $errors['tenth_marks_error'] = Validator::emptyValidate($_POST['tenth_marks']);
          $errors['twelveth_marks_error'] = Validator::emptyValidate($_POST['twelveth_marks']);
          $errors['resume_error'] = Validator::fileValidate($_FILES['resume']);
        }

        foreach($errors as $e) {
          if(!empty($e)) {
            $error = true;
            break;
          }
        }

        if (!$error) {
          $roleOb = $this->roleModel->findRoleByName($role);
          if($roleOb) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            if ($this->userModel->register($data, $roleOb)) {
              Session::set('message', 'Registration successful!');
              Session::set('type', 'success');
              $this->logModel->create($data['name'] . '(' . $data['email'] . ') has signed up as a ' . $role);
              if($role=='company') {
                $this->mailer->welcomeCompany($this->userModel->findUserByEmail($data['email']));
              } else {
                $this->mailer->welcomeStudent($this->userModel->findUserByEmail($data['email']));
              }
              header('location: /auth/login');
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
    $data['role'] = $role;
    $data['google_url'] = (new Google($role))->getSigninUrl();
    $provider = new League\OAuth2\Client\Provider\LinkedIn([
        'clientId'          => $_ENV['LINKEDIN_CLIENT_ID'],
        'clientSecret'      => $_ENV['LINKEDIN_CLIENT_SECRET'],
        'redirectUri'       => 'http://gethired.com/auth/linkedin/'.$role,
    ]);
    $data['linkedin_url'] = $provider->getAuthorizationUrl();
    Session::set('oauth2state', $provider->getState());
    $this->view("pages/auth/signup", array_merge($data, $errors));
  }
  /*
    * Allows users to login to the platform.
   */
  public function login() {
    if(Session::isset('user')) {
      header('location: /');
    }

    $getParams = substr($_GET['url'], strpos($_GET['url'], "?")+1);
    parse_str($getParams, $getArray);
    $intended = $getArray['continue'];

    $data['continue'] = $intended;

    if($_SERVER['REQUEST_METHOD'] == "POST") {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = array_merge($data, [
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'email_error' => '',
        'password_error' => '',
      ]);

      $data['email_error'] = Validator::emptyValidate($_POST['email']);
      $data['password_error'] = Validator::emptyValidate($_POST['password']);

      if(empty($data['email_error']) && empty($data['password_error'])) {
        $user = $this->userModel->login($data['email'], $data['password']);
        if($user) {
          if($user->account_status == 0) {
            Session::set('message', 'Account approval pending!');
            Session::set('type', 'warning');
          } else if ($user->account_status == 2) {
            Session::set('message', 'Your account has been suspended by the admin!');
            Session::set('type', 'warning');
          } else if ($user->account_status == 3) {
            Session::set('message', 'Your account request has been rejected!');
            Session::set('type', 'warning');
          } else {
            Session::set('user', serialize($user));
            Session::set('message', 'Welcome ' . $user->name . '!');
            Session::set('type', 'success');
            if($intended) {
              header('location: ' . $intended);
            }
            else {
              $roleTitle = $user->title == 'moderator' ? 'admin' : $user->title;
              header('location: /' . $roleTitle);
            }
            die();
          }
        } else {
          $data['email_error'] = "Invalid credentials!";
        }
      }
    }
    $data['google_url'] = (new Google('student'))->getSigninUrl();
    $provider = new League\OAuth2\Client\Provider\LinkedIn([
        'clientId'          => $_ENV['LINKEDIN_CLIENT_ID'],
        'clientSecret'      => $_ENV['LINKEDIN_CLIENT_SECRET'],
        'redirectUri'       => 'http://gethired.com/auth/linkedin/student',
    ]);
    $data['linkedin_url'] = $provider->getAuthorizationUrl();
    Session::set('oauth2state', $provider->getState());
    $this->view("pages/auth/login", $data);
  }
  /*
    * Allows users to sign in/sign up using Google Oauth.
    * @param string $role - Role of the user [student, company]
   */
  public function google($role) {
    if(Session::isset('user')) {
      header('location: /');
    }
    $data = [];
    if(strpos($_GET['url'], 'code')) {
      $google = new Google($role, substr($_GET['url'], strpos($_GET['url'], '=')+1));
      $googleUser = $google->getUserInfo();
      if(($user = $this->userModel->findUserByEmail($googleUser->email))) {
        if($user) {
          if($user->account_status == 0) {
            Session::set('message', 'Account approval pending!');
            Session::set('type', 'warning');
          } else if ($user->account_status == 2) {
            Session::set('message', 'Your account has been suspended by the admin!');
            Session::set('type', 'warning');
          } else if ($user->account_status == 3) {
            Session::set('message', 'Your account request has been rejected!');
            Session::set('type', 'warning');
          } else {
            Session::set('user', serialize($user));
            Session::set('message', 'Welcome ' . $user->name . '!');
            Session::set('type', 'success');
            if($intended) {
              header('location: ' . $intended);
            }
            else
              header('location: /');
            die();
          }
        } else {
          Session::set('message', 'Something went wrong!');
          Session::set('type', 'error');
          header('location: /');
          die();
        }
      } else {
        $data['prefilled'] = true;
        $data['email'] = $googleUser->email;
        $data['name'] = $googleUser->name;
        $data['role'] = $role;
        $this->view("pages/auth/signup", $data);
      }
    } else {
        Session::set('message', 'Something went wrong!');
        Session::set('type', 'error');
        header('location: /');
        die();
    }
  }
  /*
    * Allows users to sign in/sign up using LinkedIn Oauth.
    * @param string $role - Role of the user [student, company]
   */
  public function linkedin($role) {
    if(Session::isset('user')) {
      header('location: /');
    }
    $data = [];
    if(strpos($_GET['url'], 'code')) {
      if(!strpos($_GET['url'], 'state') || substr($_GET['url'], strpos($_GET['url'], 'state=')+1) !== $_SESSION['oauth2state']) {
          Session::set('message', 'Something went wrong!');
          Session::set('type', 'error');
          Session::unset('oauth2state');
          header('location: /');
          die();
      }
      $provider = new League\OAuth2\Client\Provider\LinkedIn([
          'clientId'          => $_ENV['LINKEDIN_CLIENT_ID'],
          'clientSecret'      => $_ENV['LINKEDIN_CLIENT_SECRET'],
          'redirectUri'       => 'http://gethired.com/auth/linkedin/'.$role,
      ]);
      $token = $provider->getAccessToken('authorization_code', [
          'code' => substr($_GET['url'], strpos($_GET['url'], 'code=')+1)
      ]);
      try {
          $linkedInUser = $provider->getResourceOwner($token);
          if(($user = $this->userModel->findUserByEmail($linkedInUser->getEmail()))) {
            if($user) {
              if($user->account_status == 0) {
                Session::set('message', 'Account approval pending!');
                Session::set('type', 'warning');
              } else if ($user->account_status == 2) {
                Session::set('message', 'Your account has been suspended by the admin!');
                Session::set('type', 'warning');
              } else if ($user->account_status == 3) {
                Session::set('message', 'Your account request has been rejected!');
                Session::set('type', 'warning');
              } else {
                Session::set('user', serialize($user));
                Session::set('message', 'Welcome ' . $user->name . '!');
                Session::set('type', 'success');
                if($intended) {
                  header('location: ' . $intended);
                }
                else
                  header('location: /');
                die();
              }
            } else {
              Session::set('message', 'Something went wrong!');
              Session::set('type', 'error');
              header('location: /');
              die();
            }
          } else {
            $data['prefilled'] = true;
            $data['email'] = $linkedInUser->getEmail();
            $data['name'] = $linkedInUser->getFirstName();
            $data['role'] = $role;
            $this->view("pages/auth/signup", $data);
          }
      } catch (Exception $e) {
          exit('Oh dear...');
      }
    } else {
        Session::set('message', 'Something went wrong!');
        Session::set('type', 'error');
        header('location: /');
        die();
    }
  }
  /*
    * Clears the user session variable,
    * allowing the user to sign out.
   */
  public function logout() {
    Session::unset('user');
    Session::set('message', 'Logged out successfully!');
    Session::set('type', 'success');
    header('location: /');
  }
}
