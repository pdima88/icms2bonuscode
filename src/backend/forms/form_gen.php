<?php
namespace pdima88\icms2bonuscode\backend\forms;

use cmsForm;
use fieldNumber;
use fieldList;
use fieldString;
use fieldText;
use fieldDate;
use fieldCheckbox;
use pdima88\icms2bonuscode\backend;

/**
 * Class form_gen
 * @property backend $controller;
 */
class form_gen extends cmsForm {

    public function init(){
        /** @var modelBonuscode $model */
        $model = $this->controller->model;

        return [
            [
                'type' => 'fieldset',
                'title' => 'Генерация бонус-кодов',
                'childs' => [
                    new fieldNumber('count', [
                        'title' => 'Количество бонус-кодов',
                        'rules' => [
                            ['required'],
                        ],
                        'default' => 1
                    ]),
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
                        'title' => 'Шаблон бонус-кода',
                        'hint' => 'Используйте символ * для любого случайного символа (цифра, буква)'
                    ]),

                    new fieldNumber('bonus', [
                        'title' => 'Бонус',
                    ]),

                    /*new fieldText('code', [
                        'title' => 'Шаблон бонус-кода',
                        'hint' => 'Используйте символ * для любого случайного символа (цифра, буква), вы также можете указать доп. символы подстановки и используемый алфавит на второй строке и ниже'
                    ]),

                    new fieldText('bonus', [
                        'title' => 'Бонус',
                        'hint' => 'Можете указать несколько значений через запятую или перевод строки, а также диапазон через дефис, тогда значение будет выбираться случайным образом'
                    ]),*/

                    new fieldText('hint', [
                        'title' => 'Описание',
                        'hint' => 'Необязательно, отображается только администраторам'
                    ]),

                    new fieldDate('date_valid', [
                        'title' => 'Срок действия'
                    ]),

                    new fieldNumber('max_activation_count', [
                        'title' => 'Макс. кол-во активаций',
                        'hint' => 'Если указано 0 - неограничено'
                    ]),

                    new fieldCheckbox('is_active', [
                        'title' => 'Активен'
                    ]),

                ]

            ],
        ];
    }

    public function parse($request, $is_submitted=false, $item=false){
        $result = parent::parse($request, $is_submitted, $item);
        $result['max_activation_count'] = (int)($result['max_activation_count'] ?? 0);
        $result['is_active'] = (bool)($result['is_active'] ?? false);
        return $result;
    }

}
