<?php
/**
  * Mailer helper class for email
  * related functions.
 */
namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer {
  /**
    * @var object $mailer - PHP Mailer object.
   */
  private $mailer;

  public function __construct() {
    $this->mailer = new PHPMailer(true);
    $this->mailer->isSMTP();
    $this->mailer->Host = $_ENV['MAIL_HOSTNAME'];
    $this->mailer->Username = $_ENV['MAIL_USERNAME'];
    $this->mailer->Password = $_ENV['MAIL_PASSWORD'];
    $this->mailer->SMTPAuth = true;
    $this->mailer->Port = $_ENV['MAIL_PORT'];
    $this->mailer->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_NAME']);
  }
  /**
    * Sends welcome email to companies.
    * @param object $user - Reciepient object.
   */
  public function welcomeCompany($user) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>Welcome to Get Hired! <br/>
      Thank you for signing up! <br/>
      Our admin team is verifying your details and will approve your account soon. <br/>
      You will receive an email once your account is approved! <br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "Welcome To Get Hired";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    $this->mailer->AltBody = "Welcome to Get Hired, your account is under verification. You will receive an email once your account gets approved!";
    return $this->sendMail();
  }
  /**
    * Sends welcome email to students.
    * @param object $user - Reciepient object.
   */
  public function welcomeStudent($user) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>Welcome to Get Hired! <br/>
      Thank you for signing up! <br/>
      Your account has been created successfully! You can browse for job openings and apply for same. <br/>
      <a href='#' target='_blank'>Browse Jobs</a><br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "Welcome To Get Hired";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    $this->mailer->AltBody = "Welcome to Get Hired, your account is created. Visit our website to browse for job openings!";
    return $this->sendMail();
  }

  public function applyStudent($user, $job) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p> You have successfully applied for $job->title at $job->name. <br/>
      Wish you all the best, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "Application for $job->title successful";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    $this->mailer->AltBody = "You application was successful.";
    return $this->sendMail();
  }

  /**
    * Sends forgot password verification,
    * mail to user.
    * @param object $user - Reciepient object.
    * @param string $token - Generated reset token.
   */
  public function forgotPassword($user, $token) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>We have received an request to reset your password! <br/>
      Please click the link below to open the link to reset your password. <br/>
      <a href='http://{$_ENV['BASE_URL']}/password/reset/{$user->email}/{$token}' target='_blank'>Reset Password</a><br/>
      <small>This link will be active for 24 hours, after which you have to create a new password reset request.</small><br/>
      <small><em>If you haven't requested to reset your password please ignore this email, however, we recommend you to change your password as you might be under attack.</em></small><br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "[Get Hired] Password Reset Request";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    return $this->sendMail();
  }

  public function allotExam($user, $exam_time, $token) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>Your exam is scheduled for {$exam_time}<br/>
      Please click the link below to start your exam. <br/>
      <a href='http://{$_ENV['BASE_URL']}/student/start/{$token}' target='_blank'>Start Exam</a><br/>
      <small>This link will be active for 24 hours, after which you will not be allowed to take the exam.</small><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "[Get Hired] Exam Link";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    return $this->sendMail();
  }
  /**
    * Notifies the user that their password reset,
    * was successful.
    * @param object $user - Reciepient object.
   */
  public function passwordReset($user) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>Your password reset was successful! <br/>
      <b>If you haven't reset your password, please contact an administrator immediately!</b><br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "[Get Hired] Password Reset Successful";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    return $this->sendMail();
  }
  /**
    * Notifies the user that their account,
    * has been activated.
    * @param object $user - Reciepient object.
   */
  public function accountActivated($user) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>Your account has been successfully approved by the administrator! <br/>
      You can login and post job openings and access other areas. <br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "[Get Hired] Account Approved";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    return $this->sendMail();
  }
  /**
    * Notifies the user that their account,
    * has been rejected.
    * @param object $user - Reciepient object.
   */
  public function accountRejected($user) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>We regret to inform you that our admin has rejected your request! <br/>
      Reason for rejection: <b>{$user->remarks}</b><br/>
      You may contact the admin for further information.<br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "[Get Hired] Account Request Rejected";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    return $this->sendMail();
  }
  /**
    * Notifies the user that their account,
    * has been suspended.
    * @param object $user - Reciepient object.
   */
  public function accountBanned($user) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>Your account has been banned by our system administrator due to numerous reasons! <br/>
      Reason for ban: <b>{$user->remarks}</b><br/>
      You may contact the admin for further information.<br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "[Get Hired] Account Banned";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    return $this->sendMail();
  }

  public function welcomeHR($user, $password) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>Welcome to Get Hired! <br/>
      You have been added as an HR Manager 
      Your account has been generated by your company administrator! <br/>
      You can login to Get Hired using the url: <a href='http://{$_ENV['BASE_URL']}/auth/login'>http://{$_ENV['BASE_URL']}/auth/login</a>, with the following credentials.<br/>
      E-Mail: {$user->email}<br/>
      Password: <b>{$password}</b><br/>
      <small>The above password was automatically generated by the system, we strongly recommend you to change the password as soon as you login to your dashboard.</small><br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "Welcome To Get Hired";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    $this->mailer->AltBody = "Welcome to Get Hired, your account is created by your company.";
    return $this->sendMail();
  }

  public function examCreated($createdBy, $sendTo, $exam) {
    $content = "
      <h2>Hello {$sendTo->name},</h2>
      <p>A new exam has been created by {$createdBy->name} ({$createdBy->employee_id}) and is pending for approval!<br/>
      You can visit the admin panel and review the exam and approve it or click the link below to go directly to the exam.<br/>
      Name of the exam: {$exam->name}<br/>
      <a href='http://{$_ENV['BASE_URL']}/exam/browse/{$exam->id}'>View Exam</a>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($sendTo->email);
    $this->mailer->Subject = "New Exam Created";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    return $this->sendMail();
  }

  public function examApproved($user, $exam) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>The exam you created has been approved by your company admin.<br/>
      Exam Description: {$exam->description}<br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "Exam Approved";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    return $this->sendMail();
  }

  public function jobOpeningApproved($user, $job) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>The job opening you created has been approved by system admin.<br/>
      Job Title: {$job->title}<br/>
      Thanks and Regards, <br/><br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "Job Opening Approved";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    return $this->sendMail();
  }

  public function jobOpeningRejected($user, $job, $remarks) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>The job opening you created has been rejected by system admin.<br/>
      Job Title: {$job->title}<br/>
      Rejection remarks: {$remarks}<br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "Job Opening Rejected";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    return $this->sendMail();
  }
  public function studentAccepted($user) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>Your profile is accepted for the post of {$user->title} at {$user->company_name}! <br/>
      Please wait until further communicated by company HR. <br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "[Get Hired] Student Accepted";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    return $this->sendMail();
  }
  public function studentRejected($user) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>We regret to inform you that company HR has rejected your request! <br/>
      You may contact the company for further information.<br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "[Get Hired] Student Rejected";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    return $this->sendMail();
  }
  public function studentShortlisted($user) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>We are pleased to inform you that you have been selected for further rounds at {$user->company_name}! <br/>
      Please wait until further communicated by company HR.<br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "[Get Hired] Student Shortlisted";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    return $this->sendMail();
  }
  public function welcomeModerator($user, $password) {
    $content = "
      <h2>Hello {$user->name},</h2>
      <p>Welcome to Get Hired! <br/>
      You have been added as an Moderator for Get Hired.
      Your account has been generated by your system administrator! <br/>
      You can login to Get Hired using the url: <a href='http://{$_ENV['BASE_URL']}/auth/login'>http://{$_ENV['BASE_URL']}/auth/login</a>, with the following credentials.<br/>
      E-Mail: {$user->email}<br/>
      Password: <b>{$password}</b><br/>
      <small>The above password was automatically generated by the system, we strongly recommend you to change the password as soon as you login to your dashboard.</small><br/><br/>
      Thanks and Regards, <br/>
      Team Get Hired</p>
    ";
    $this->mailer->addAddress($user->email);
    $this->mailer->Subject = "Welcome To Get Hired";
    $this->mailer->isHTML(true);
    $this->mailer->Body = $content;
    $this->mailer->AltBody = "Welcome to Get Hired, your account is created by your company.";
    return $this->sendMail();
  }
  /**
    * Function that prepares the mailer and sends.
   */
  private function sendMail() {
    try {
      return $this->mailer->send();
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }
}
