<?php
namespace controllers;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Route;
 /**
  * Controller TodosController
  */
class TodosController extends ControllerBase{
    public function initialize()
    {
        parent::initialize();
        $this->menu();
    }

    #[Route('_default', name: 'home')]
	public function index(){
		
	}

    #[Post(path: "TodosController/add", name: 'todos.add')]
    public function addElement(){

    }

	#[Get(path: "todos/delete/{index}", name: 'todos.delete')]
	public function delecteElement($index){
		
	}


	#[Post(path: "todos/edit/{index}", name: 'todos.edit')]
	public function editElement($index){
		
	}


	#[Get(path: "todos/loadList/{uniquid}", name: 'todos.loadList')]
	public function loadList($uniquid){
		
	}


	
	public function menu(){
		
		$this->loadView('TodosController/menu.html');

	}


	#[Post(path: "TodosController/loadListFromForm", name: 'todos.loadListFromForm')]
	public function loadListFromForm(){
		
	}


	#[Get(path: "TodosController/newList", name: 'todos.newList')]
	public function newList($force){
		
	}


	#[Get(path: "TodosController/saveList", name: 'todos.saveList')]
	public function saveList(){
		
	}

}
