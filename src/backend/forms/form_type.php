<?php
namespace pdima88\icms2bonuscode\backend\forms;

use cmsForm;
use fieldString;
use fieldText;
use fieldList;
use pdima88\icms2bonuscode\backend;

/**
 * Class form_type
 * @package pdima88\icms2bonuscode\backend\forms
 * @property backend $controller
 */
class form_type extends cmsForm {

    public function init(){
        
        $components = $this->controller->model->getComponentList(false, '(Выберите из списка)');

        return [
            [
                'type' => 'fieldset',
                'title' => 'Тип бонус-кодов',
                'childs' => [

                    new fieldString('title', [
                        'title' => 'Название',
                        'rules' => [
                            ['required'],
                        ]
                    ]),

                    new fieldText('hint', [
                        'title' => 'Описание',
                        'rules' => [
                            ['required'],
                        ]
                    ]),

                    new fieldList('component', [
                        'title' => 'Компонент',
                        'rules' => [
                            ['required'],
                        ],
                        'items' => $components
                    ])

                ]

            ],
        ];
    }
}
