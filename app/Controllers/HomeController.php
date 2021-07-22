<?php
/*
  * Controller for public pages.
 */
use App\Libraries\Controller;
use App\Helpers\Session;

class HomeController extends Controller {
  /*
    * Renders the index page.
    * @return void
   */
  public function index() {
    $this->view("pages/index");
  }
  public function notFound() {
    $this->view("pages/404");
  }
}
