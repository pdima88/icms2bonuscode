<?php
namespace pdima88\icms2bonuscode\backend\actions;

use pdima88\icms2ext\crudAction;
use pdima88\icms2ext\GridHelper;
use pdima88\icms2ext\Model;
use pdima88\icms2ext\Table;
use pdima88\icms2bonuscode\model as modelBonuscode;
use cmsCore;
use cmsUser;

/**
 * @property modelBonuscode $model
 */

class codes extends crudAction {

    const FORM_CODE = 'code';
    const FORM_GROUP = 'group';

    public function __construct($controller, array $params)
    {
        parent::__construct($controller, $params);
        $this->pageTitle = 'Бонус-коды';
        $this->titles['add'] = 'Создание бонус-кода';
        $this->titles['edit'] = 'Редактирование бонус-кода';
        $this->titles['group_add'] = 'Добавление группы';
        $this->titles['group_edit'] = 'Редактирование группы';
        $this->messages['add'] = 'Бонус-код добавлен';
        $this->messages['error_edit_no_item'] = 'Бонус-код не найден';
    }

    public function actionIndex() {
        $res = parent::actionIndex();

        $groups = $this->model->getGroups();
        $groups = is_array($groups) ? $groups : [];
        $groups = array_pad($groups, (sizeof($groups)+1)*-1, array(
                'id' => 0,
                'title' => LANG_ALL)
        );
        $res['data']['group_id'] = $this->getParam();
        $res['data']['groups'] = $groups;
        return $res;
    }

    function getGrid() {
        $groupId = $this->getParam();
        $select = Model::zendDbSelect()->from(Table::prefix(modelBonuscode::TABLE_BONUSCODES));
        if (isset($groupId) && $groupId) $select->where('group_id = ?', $groupId);

        $grid = [
            'id' => 'bonuscodes',
            'select' => $select,
            'rownum' => true,
            'sort' => [
                'id' => 'desc',
            ],
            'multisort' => true,
            'paging' => 10,
            'url' => $this->cms_core->uri_absolute,
            'ajax' => $this->cms_core->uri_absolute,
            'actions' => GridHelper::getActions([
               'edit' => [
                    'title' => 'Изменить',
                    'href'  => href_to($this->pageUrl, 'edit', '{id}') . '?back={returnUrl}',
                ],
                'delete' => [
                    'title' => 'Удалить',
                    'href' => '',
                    'confirmDelete' => true,
                ]
            ]),
            'delete' => href_to($this->pageUrl, 'delete', '{id}'). '?back={returnUrl}',
            'columns' => []
        ];



        $grid['columns']['code'] = [
            'title' => 'Бонус-код',
            'width' => 150,
            'sort' => true,
            'filter' => 'text'
        ];
        if (!isset($groupId) || !$groupId) {
            $grid['columns']['group_id'] = [
                'title' => 'Группа',
                'align' => 'center',
                'filter' => 'select',
                'sort' => 'true',
                'format' => $this->model->getGroupList()
            ];
        }
        $grid['columns']['type_id'] = [
            'title' => 'Тип',
            'align' => 'center',
            'sort' => true,
            'filter' => 'select',
            'format' => $this->model->getTypeList()
        ];
        $grid['columns']['bonus'] = [
            'title' => 'Бонус',
            'format' => '%.2f',
            'width' => 100,
            'align' => 'right',
            'sort' => true,
            'filter' => 'equal'
        ];
        $grid['columns']['date_valid'] = [
            'title' => 'Срок действия',
            'format' => 'date',
            'filter' => 'dateRange',
            'sort' => true,
            'align' => 'center',
        ];
        $grid['columns']['hint'] = [
            'title' => 'Описание',
            'filter' => 'text',
        ];
        $grid['columns']['date_created'] = [
            'title' => 'Дата создания',
            'format' => 'datetime',
            'filter' => 'dateRange',
            'sort' => true,
            'align' => 'center',
        ];
        $grid['columns']['user_created'] = [
            'title' => 'Кто создал (ID пользователя)',
            'align' => 'center',
            'filter' => 'equal',
            'sort' => true,
        ];
        $grid['columns']['is_active'] = [
            'title' => 'Активен',
            'width' => 70,
            'align' => 'center',
            'sort' => true,
            'format' => 'checkbox',
            'filter' => 'select',
        ];
        $grid['columns']['total_activation_count'] = [
            'title' => 'Кол-во активаций',
            'align' => 'center',
            'sort' => true,
        ];
        $grid['columns']['max_activation_count'] = [
            'title' => 'Макс. кол-во активаций',
            'align' => 'center',
            'sort' => true,
        ];

        return $grid;
    }

    public function actionAdd()
    {
        $this->setForm(self::FORM_CODE);
        return parent::actionAdd();
    }

    public function actionEdit($id = null, $item = null)
    {
        $this->setForm(self::FORM_CODE);
        return parent::actionEdit();
    }

    public function actionDelete() {
        $this->setForm(self::FORM_CODE);
        return parent::actionDelete();
    }

    public function setForm($formName) {
        $this->formName = $formName;
        if ($formName == self::FORM_CODE) {
            $this->tableName = modelBonuscode::TABLE_BONUSCODES;
        } elseif ($formName == self::FORM_GROUP) {
            $this->tableName = modelBonuscode::TABLE_GROUPS;
        } else {
            throw new Exception('Unknown form name: '.$formName);
        }
    }

    public function actionGroupAdd() {
        $this->setForm(self::FORM_GROUP);
        return parent::actionAdd();
    }

    public function actionGroupEdit() {
        $this->setForm(self::FORM_GROUP);
        $id = $this->getParam();
        if (!$id) cmsCore::error404();
        $item = $this->model->getGroup($id);
        if (!$item) {
            cmsUser::addSessionMessage('Группа не найдена', 'error');
            $this->redirectBack();
        }
        return parent::actionEdit($id, $item);
    }

    public function actionGroupDelete() {
        $this->setForm(self::FORM_GROUP);
        $id = $this->getParam();
        if (!$id) cmsCore::error404();
        $item = $this->model->getGroup($id);
        if (!$item) {
            cmsUser::addSessionMessage('Группа не найдена', 'error');
            $this->redirectBack();
        }
        return parent::actionDelete();
    }

    public function actionGroupInfo() {
        $id = $this->getParam(0);
        if (!$id) { exit; }

        $group = $this->model->getGroup($id);

        if (!$group) {
            echo 'Группа не найдена!';
            exit;
        }

        return [
            'tpl' => 'backend/group_info',
            'data' => [
                'group' => $group,
            ]
        ];
    }

    public function save($id, $data)
    {
        if ($this->formName == self::FORM_CODE) {
            if ($data['code'] === '') {
                $data['code'] = $this->model->generateCode();
            }
        }
        if (!isset($data['date_valid'])) $data['date_valid'] = false;
        if (!$id) {
            $data['date_created'] = null;
            $data['user_created'] = cmsUser::getInstance()->id;
        }
        parent::save($id, $data);
    }

}
