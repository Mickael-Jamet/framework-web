<?php
namespace controllers;
use models\Product;
use models\Section;
use Ubiquity\attributes\items\di\Autowired;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\auth\AuthController;
use Ubiquity\controllers\auth\WithAuthTrait;
use services\dao\UserRepository;
use services\ui\UIServices;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;

/**
  * Controller MainController
  */
class MainController extends ControllerBase{
    use WithAuthTrait;
    #[Autowired]
    private UserRepository $repo;
    private UIServices $ui;

    #[Route('_default',name:'index')]
	public function index(){
        $user=$this->_getAuthController()->_getActiveUser();
        $this->repo->byId($user->getId(),true,false,'user');
        $promos=DAO::getAll(Product::class,'promotion<?',false,[0]);
        $this->jquery->renderView("MainController/index.html",["promos"=>$promos]);
	}

    public function initialize() {
        $this->ui=new UIServices($this);
        parent::initialize();
        $this->jquery->getHref('a[data-target]','',['listenerOn'=>'body','hasLoader'=>'internal-x']);
    }

    public function getRepo(): UserRepository { return $this->repo; }

    public function setRepo(UserRepository $repo): void {
        $this->repo = $repo;
    }

    protected function getAuthController(): AuthController {
        return new MyAuth($this);
    }

	#[Route(path: "store",name: "store")]
	public function store($content=''){
        $sections = DAO::getAll(Section::class, '', ['products']);
        $this->jquery->renderView('MainController/store.html', compact('sections', 'content'));
	}


	#[Route(path: "section/{id}",name: "section")]
	public function section($id){
        $section=DAO::getById(Section::class,$id,['products']);
        if(!URequest::isAjax()) {
            $content=$this->loadDefaultView(compact('section'),true);
            $this->store($content);
            return;
        }
        $this->loadDefaultView(compact('section'));
	}


	#[Route(path: "detailsProduit/{idS}/{idP}",name: "main.detailsProduit")]
	public function detailsProduit($idS,$idP){

		$this->loadView('MainController/detailsProduit.html');

	}


	#[Route(path: "product/{id}",name: "main.product")]
	public function product($id){

		$this->loadView('MainController/product.html');

	}

}
