<?php
namespace services\ui;
use Ubiquity\controllers\Controller;
use Ubiquity\utils\http\URequest;

 /**
  * Class UIServices
  */
class UIServices extends \Ajax\php\ubiquity\UIService{
    public function __construct(Controller $controller) {
        parent::__construct($controller);
        if(!URequest::isAjax()) {
            $this->jquery->getHref('a[data-target]', '', ['hasLoader' => 'internal', 'historize' => false,'listenerOn'=>'body']);
        }
    }
}
