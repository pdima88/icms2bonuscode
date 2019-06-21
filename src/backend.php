<?php
namespace pdima88\icms2bonuscode;

use cmsBackend;
use pdima88\icms2bonuscode\model;

/**
 * Class backend
 * @package pdima88\icms2bonuscode
 * @property model $model;
 */
class backend extends cmsBackend {
   
    public $useDefaultOptionsAction = true;

    public function actionIndex(){
        $this->redirectToAction('codes');
    }

    protected function validateParamsCount($class, $method_name, $params)
    {
        return true;
    }

    public function getBackendMenu(){
        return array(

            array(
                'title' => 'Бонус-коды',
                'url' => href_to($this->root_url, 'codes')
            ),
            array(
                'title' => 'Типы бонус-кодов',
                'url' => href_to($this->root_url, 'types')
            ),
            array(
                'title' => LANG_BONUSCODE_BACKEND_TAB_OPTIONS,
                'url' => href_to($this->root_url, 'options')
            ),
        );
    }

}
