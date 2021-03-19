<?php
namespace controllers;
use models\Section;
use Ubiquity\attributes\items\di\Autowired;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\auth\AuthController;
use Ubiquity\controllers\auth\WithAuthTrait;
use services\dao\UserRepository;
use services\ui\UIServices;
use Ubiquity\orm\DAO;

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
        $this->jquery->renderView("MainController/index.html");
	}

    public function initialize() {
        parent::initialize();
    }

    public function getRepo(): UserRepository { return $this->repo; }

    public function setRepo(UserRepository $repo): void {
        $this->repo = $repo;
    }

    protected function getAuthController(): AuthController {
        return new MyAuth($this);
    }

	#[Route(path: "store",name: "store")]
	public function store(){
        $sections=DAO::getAll(Section::class,'', ['products']);
        $this->jquery->renderView('MainController/store.html',['section'=>$sections]);
	}

}
