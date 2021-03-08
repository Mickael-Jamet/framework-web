<?php
namespace controllers;
 /**
  * Controller MainController
  */
class MainController extends ControllerBase{

	public function index(){
		$this->loadView("MainController/index.html");
	}
}
