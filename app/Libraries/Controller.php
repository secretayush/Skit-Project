<?php
/*
  * Base controller class that
  * all other controllers will inherit.
 */
namespace App\Libraries;

use App\Helpers\Session;

class Controller {
  /*
    * Method to load in the model.
    * @param string $model - Name of the model.
    * @return object
   */
  public function model($model) {
      require_once '../app/Models/' . $model . '.php';
      return new $model();
  }
  /*
    * Method to load in the view.
    * @param string $view - Name of the view.
    * @param array $data - Data to pass down
    * to the view.
    * @return void
   */
  public function view($view, $data=[]) {
    if(Session::isset('user')) {
      Session::set('user', serialize($this->model('User')->findUserByEmail(Session::user()->email)));
    }
    if (file_exists('../app/Views/' . $view . '.php')) {
      require_once '../app/Views/' . $view . '.php';
    } else {
      require_once '../app/Views/pages/404.php';
    }
  }
}
