<?php
namespace pdima88\icms2bonuscode\backend\forms;

use cmsForm;
use fieldString;
use fieldText;
use fieldNumber;

class form_group extends cmsForm {

    public function init(){

        return [
            [
                'type' => 'fieldset',
                'title' => 'Группа бонус-кодов',
                'childs' => [

                    new fieldString('title', [
                        'title' => 'Название',
                        'rules' => [
                            ['required'],
                        ]
                    ]),

                    new fieldText('hint', [
                        'title' => 'Описание',
                    ]),

                    new fieldNumber('sortorder', [
                        'title' => 'Порядок сортировки',
                        'hint' => 'Группы будут выводится в порядке возрастания значения в этом поле'
                    ])

                ]

            ],
        ];
    }

    public function parse($request, $is_submitted=false, $item=false){
        $result = parent::parse($request, $is_submitted, $item);
        $result['sortorder'] = (int)($result['sortorder'] ?? 0);
        return $result;
    }

}
