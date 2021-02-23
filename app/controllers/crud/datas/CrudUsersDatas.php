<?php
namespace controllers\crud\datas;

use Ubiquity\controllers\crud\CRUDDatas;
 /**
  * Class CrudUsersDatas
  */
class CrudUsersDatas extends CRUDDatas{
    public function getFieldNames($model)
    {
        return ['firstname', 'lastname', 'email'];
    }
}
