<?php
namespace pdima88\icms2bonuscode\backend\forms;

use cmsForm;
use fieldList;
use fieldString;
use fieldText;
use fieldDate;
use fieldNumber;
use fieldCheckbox;
use pdima88\icms2bonuscode\model as modelBonuscode;
use pdima88\icms2bonuscode\backend as backend;

/**
 * Class form_code
 * @package pdima88\icms2bonuscode\backend\forms
 * @property backend $controller;
 */
class form_code extends cmsForm {

    public function init(){
        /** @var modelBonuscode $model */
        $model = $this->controller->model;

        return [
            [
                'type' => 'fieldset',
                'title' => 'Бонус-код',
                'childs' => [
                    new fieldList('type_id', [
                        'title' => 'Тип',
                        'rules' => [
                            ['required'],
                        ],
                        'items' => $model->getTypeList(false, '(Выберите из списка)')
                    ]),

                    new fieldList('group_id', [
                        'title' => 'Группа',
                        'rules' => [
                            ['required'],
                        ],
                        'items' => $model->getGroupList(false, '(Выберите из списка)')
                    ]),

                    new fieldString('code', [
                        'title' => 'Бонус-код',
                        'hint' => 'Оставьте пустым, чтобы сгенерировать случайным образом'
                    ]),

                    new fieldNumber('bonus', [
                        'title' => 'Бонус',
                    ]),

                    new fieldText('hint', [
                        'title' => 'Описание',
                        'hint' => 'Необязательно, отображается только администраторам'
                    ]),

                    new fieldDate('date_valid', [
                        'title' => 'Срок действия',
                        'hint' => 'По указанный день включительно, или оставьте пустым, чтобы не ограничивать срок действия'
                        /*'options' => [
                            'show_time' => 1,
                        ]*/
                    ]),

                    new fieldNumber('max_activation_count', [
                        'title' => 'Макс. кол-во активаций',
                        'hint' => 'Если указано 0 - неограничено'
                    ]),

                    new fieldCheckbox('is_active', [
                        'title' => 'Активен',
                        'default' => true,
                    ]),

                ]

            ],
        ];
    }

    public function parse($request, $is_submitted=false, $item=false){
        $result = parent::parse($request, $is_submitted, $item);
        $result['max_activation_count'] = (int)($result['max_activation_count'] ?? 0);
        $result['is_active'] = (bool)($result['is_active'] ?? false);
        $result['date_valid'] = (!datetime_empty($result['date_valid'])) ? date('Y-m-d', strtotime($result['date_valid'])).' 23:59:59' : null;
        return $result;
    }

}
