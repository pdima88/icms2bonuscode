<?php
namespace pdima88\icms2bonuscode\backend\forms;

use pdima88\icms2bonuscode\backend;

/**

 * @property backend $controller */
class formBonuscodeTypeEdit extends formBonuscodeType {

    public function init(){

        $form = [
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

                ]

            ],
        ];
        
        //$form = cmsEventsManager::hook('bonuscode_form', )
    }
}
