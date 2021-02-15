<?php
namespace controllers;
use models\Groupe;
use models\User;
use Ubiquity\attributes\items\router\Post;
 use models\Organization;
 use Ubiquity\attributes\items\router\Route;
 use Ubiquity\orm\DAO;
 use Ubiquity\orm\repositories\ViewRepository;
use Ubiquity\utils\http\URequest;

/**
  * Controller OrgaController
  */
class OrgaController extends ControllerBase{

    private ViewRepository $repo;

    public function initialize()
    {
        parent::initialize();
        $this->repo=new ViewRepository($this, Organization::class);
    }

    #[Route('orga')]
	public function index(){
        $this->repo->all("", false);
		$this->loadView("OrgaController/index.html");
	}

	#[Route(path: "orga/{idOrga}",name: "orga.getOne")]
	public function getOne($idOrga){
		$this->repo->byId( $idOrga, ['users.groupes','groupes.users']);
		$this->loadDefaultView();
	}


	#[Post(path: "orga/add",name: "orga.add")]
	public function add(){
        $orga=new Organization();
        URequest::setValuesToObject($orga);
        if(DAO::insert($orga)) {
            console.log("Insertion réussie");
        }
        $this->loadView("OrgaController/add.html");
	}


	#[Post(path: "orga/update/{idOrga}",name: "orga.update")]
	public function update($idOrga){
        $orga=DAO::getById(Organization::class,$idOrga);
        URequest::setValuesToObject($orga);
        if(DAO::update($orga)){
            console.log("Mise à jour réussie");
        }
        $this->loadView("OrgaController/update.html");
	}


	#[Post(path: "orga/delete/{idOrga}",name: "orga.delete")]
	public function delete($idOrga){
        $orga=DAO::getById(Organization::class,$idOrga);
        if(DAO::remove($orga)){
            console.log("Suppression réussie");
        }
        $this->loadView("OrgaController/delete.html");
	}

	#[Route()]
    public function getOrga($name){
        $orga=DAO::getOne(Organization::class, "name= ?", parameters:[$name]);
    }

    public function testInsert(){
        $groupe=new Groupe();
        URequest::setValuesToObject($groupe);
        $idOrga=URequest::post('idOrga');
        $orga=DAO::getById(Organization::class,$idOrga);
        $groupe->setOrganization($orga);
        DAO::insert($orga);
    }

    public function testUpdate(){
        $groupe=DAO::getById(Groupe::class,URequest::post('idGroup'));
        URequest::setValuesToObject($groupe);
        $idOrga=URequest::post('idOrga');
        $orga=DAO::getById(Organization::class,$idOrga);
        $groupe->setOrganization($orga);
        $idUsers=explode(',', URequest::post('idUsers'));
        $users=DAO::getAllByIds(User::class,$idUsers);
        foreach ($users as $user){
            $groupe->addUser($user);
        }
        DAO::update($orga, true);
    }

}
