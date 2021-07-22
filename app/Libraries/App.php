<?php
/*
  * App class that bootstraps the application.
 */
namespace App\Libraries;

class App {
  /*
    * @var $controller string - holds name of the
    * controller that is being used.
    * Defaults to HomeController
   */
  protected $controller = "HomeController";
  /*
    * @var $method string - holds name of the
    * method that is being used.
    * Defaults to index
   */
  protected $method = "index";
  /*
    * @var $params array - holds the
    * parameters to be passed down
    * to the controller method.
    * Defaults to empty array.
   */
  protected $params = [];

  public function __construct() {
    $url = $this->parseUrl();

    if(isset($url[0])) {
      if(file_exists('../app/Controllers/' . ucwords($url[0]) . 'Controller.php')) {
        $this->controller = ucwords($url[0]) . 'Controller';
        unset($url[0]);
      } else {
        $this->controller = "HomeController";
        $this->method = "notFound";
      }
    }

    require_once '../app/Controllers/' . $this->controller . '.php';

    $this->controller = new $this->controller;

    if(isset($url[1])) {
      if(method_exists($this->controller, $url[1])) {
        $this->method = $url[1];
        unset($url[1]);
      } else {
        require_once '../app/Controllers/HomeController.php';
        $this->controller = new \HomeController;
        $this->method = "notFound";
      }
    }

    $this->params = $url ? array_values($url) : [];

    call_user_func_array([$this->controller, $this->method], $this->params);
  }
  /*
    * Parses the query parameter
    * to extract controller, method
    * and parameters to serve the page.
    * @return array
   */
  public function parseUrl() {
    if(isset($_GET['url'])) {
      if(strpos($_GET['url'], "?")) {
        $url = substr($_GET['url'], 0, strpos($_GET['url'], "?"));
        $url = trim($url, '/');
      } else {
        $url = trim($_GET['url'], '/');
      }

      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);
      return $url;
    }
    return [];
  }
}
