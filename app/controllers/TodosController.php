<?php
namespace controllers;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\Router;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;

/**
  * Controller TodosController
  * @property \Ajax\php\ubiquity\JsUtils $jquery
  */
class TodosController extends ControllerBase{
    const CACHE_KEY = 'datas/lists/';
    const EMPTY_LIST_ID='not saved';
    const LIST_SESSION_KEY='list';
    const ACTIVE_LIST_SESSION_KEY='active-list';

    public function initialize()
    {
        parent::initialize();
        $this->menu();
    }

    #[Route('_default', name: 'home')]
	public function index()
    {
        if(USession::exists(self::LIST_SESSION_KEY)){
            $list = USession::get(self::LIST_SESSION_KEY, []);
            $this->displayList($list);
        }
        $this->showMessage('Bienvenu !', 'TodoLists permet de gérer des listes ...',  'info', 'info circle',
        [['url'=>Router::path('todos.new'),'caption'=>'créer une nouvelle liste','style'=>'basic inverted']]);
	}

    #[Post(path: "TodosController/add", name: 'todos.add')]
    public function addElement(){
        $list=USession::get(self::LIST_SESSION_KEY);
        if(URequest::filled('elements')){
            $elements=explode("\n",URequest::post('elements'));
            foreach ($elements as $elm){
                $list[]=$elm;
            }
        }else{
            $list[]=URequest::post('element');
        }
        USession::set(self::LIST_SESSION_KEY, $list);
        $this->displayList($list);
    }

	#[Get(path: "todos/delete/{index}", name: 'todos.delete')]
	public function delecteElement($index){
        $list=USession::get(self::LIST_SESSION_KEY);
        if(isset($list[$index])){
            array_splice($list, $index, 1);
            USession::set(self::LIST_SESSION_KEY, $list);
        }
        $this->displayList($list);
	}


	#[Post(path: "todos/edit/{index}", name: 'todos.edit')]
	public function editElement($index){
        $list=USession::get(self::LIST_SESSION_KEY);
        if(isset($list[$index])){
            $list[$index] = URequest::post('editElement');
            USession::set(self::LIST_SESSION_KEY, $list);
        }
        $this->displayList($list);
	}


	#[Get(path: "todos/loadList/{uniquid}", name: 'todos.loadList')]
	public function loadList($uniquid){
		
	}

	#[Post(path: "TodosController/loadListFromForm", name: 'todos.loadListFromForm')]
	public function loadListFromForm(){
		
	}


	#[Get(path: "TodosController/newList/{force}", name: 'todos.new')]
	public function newList($force=false){
        if($force != false || !USession::exists(self::LIST_SESSION_KEY)){
            USession::set(self::LIST_SESSION_KEY, []);
            $this->displayList(USession::get(self::LIST_SESSION_KEY));
        }else if(USession::exists(self::LIST_SESSION_KEY)) {
            $this->showMessage("Nouvelle Liste", "Une liste existe déjà. Voulez vous la vider ?", "", "",
                [['url' =>Router::path('todos.new/1'),'caption'=>'Créer une nouvelle liste','style'=>'basic inverted'],
                    ['url' =>Router::path('todos.menu'),'caption'=>'Annuler','style'=>'basic inverted']]);
            $this->displayList(USession::get(self::LIST_SESSION_KEY));
        }
	}


	#[Get(path: "TodosController/saveList", name: 'todos.saveList')]
	public function saveList(){
		
	}


    public function menu(){

        $this->loadView('TodosController/menu.html');

    }
	
	public function displayList($list){
        if(\count($list)>0){
            $this->jquery->show('._saveList','','',false);
        }
		$this->jquery->change('#multiple', '$("._form").toggle();');
		$this->jquery->renderView('TodosController/displayList.html', ['list'=>$list]);
	}


	
	public function showMessage(string $header,string $message,string $type='info',string $icon='info circle',array $buttons=[]){
        $this->loadView('TodosController/showMessage.html', compact('header','message','type','icon','buttons'));
	}

}
