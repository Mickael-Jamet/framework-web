<?php
namespace services\ui;

 use models\Group;
 use Ubiquity\controllers\Controller;
 use Ubiquity\controllers\Router;

 /**
  * Class UIGroups
  */
class UIGroups extends \Ajax\php\ubiquity\UIService{
    public function __construct(Controller $controller){
        parent::__construct($controller);
        $this->jquery->getHref('a[data-target]', parameters: ['historize'=>false, 'hasLoader'=>'internal', 'listenerOn'=>'body']);
    }

    public function listGroups(array $groups){
        $dt=$this->semantic->dataTable('dt-groups',Group::class,$groups);
        $dt->setFields(['name','email']);
        $dt->fieldAsLabel('email', 'mail');
        $dt->addDeleteButton();
    }

    public function orgaForm(\models\Organization $orga){
        $frm=$this->semantic->dataForm('frmOrga', $orga);
        $frm->setFields(['id', 'name', 'domain','submit']);
        $frm->fieldAsHidden('id');
        $frm->fieldAsLabeledInput('name', ['rules'=>'empty']);
        $frm->fieldAsLabeledInput('domain', ['rules'=>['empty', 'email']]);
        $frm->setValidationParams(["on"=>"blur","inline"=>true]);
        $frm->fieldAsSubmit('submit',  'positive', Router::path('addOrga'), "#response");
    }
}
