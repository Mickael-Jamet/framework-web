<?php
namespace controllers;
use models\Basket;
use models\Basketdetail;
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
use Ubiquity\utils\http\USession;

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
        $promos = DAO::getAll(Product::class, 'promotion<?', ['sections'], [0]);
        $rVP = USession::get('recentViewedProducts');
        $this->jquery->renderView('MainController/store.html', compact('sections', 'content', 'promos', 'rVP'));
	}


	#[Route(path: "section/{idSection}",name: "section")]
	public function section($idSection){
        $section=DAO::getById(Section::class,$idSection,['products']);
        if(!URequest::isAjax()) {
            $content=$this->loadDefaultView(compact('section'),true);
            $this->store($content);
            return;
        }
        $this->loadDefaultView(compact('section'));
	}


	#[Route(path: "product/{idSection}/{idProduit}",name: "main.product")]
	public function product($idSection,$idProduit){
		$section = DAO::getById(Section::class, $idSection, false);
		$product = DAO::getById(Product::class, $idProduit, false);
        if(!URequest::isAjax()){
            $content = $this->loadView('MainController/product.html', ['product'=>$product, 'section'=>$section],true);
            $this->store($content);
            return;
        }
		$this->loadView('MainController/product.html', ["product"=>$product, "section"=>$section]);
	}


	#[Route(path: "basket/add/{idProduct}",name: "main.add")]
	public function add($idProduct){
        $product = DAO::getById(Product::class, $idProduct, false);
        $dB = USession::get('defaultBasket');
        $dB->addProduct($product, 1);
	}


	#[Route(path: "basket/addTo/{idBasket}/{idProduct}",name: "main.addTo")]
	public function addTo($idBasket,$idProduct){
		$basket = DAO::getById(Basketdetail::class, $idBasket, ['products']);
		$qtt = $basket->getQuantity();
		$basket->addProduct($idProduct, $qtt);
	}


	#[Route(path: "basket/{idBasket}",name: "basket")]
	public function basket($idBasket){
        $basket = DAO::getById(Basketdetail::class, $idBasket, ['products']);
		$this->loadView('MainController/basket.html', ["basket"=>$basket]);
	}


	#[Route(path: "basket/validate",name: "validate")]
	public function validate(){
		$this->loadView('MainController/validate.html');
	}


	#[Route(path: "basket/timeslot/{idTimeslot}",name: "timeslot")]
	public function timeslot($idTimeslot){
		$this->loadView('MainController/timeslot.html');
	}


	#[Route(path: "basket/command",name: "command")]
	public function command(){
		$this->loadView('MainController/command.html');
	}


	#[Route(path: "basket/clear",name: "clear")]
	public function clear(){
		$this->loadView('MainController/clear.html');
	}

}
