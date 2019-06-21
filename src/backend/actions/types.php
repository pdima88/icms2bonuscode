<?php
namespace pdima88\icms2bonuscode\backend\actions;

use pdima88\icms2ext\crudAction;
use pdima88\icms2ext\GridHelper;
use pdima88\icms2ext\Model;
use pdima88\icms2ext\Table;
use cmsCore;
use cmsUser;
use pdima88\icms2bonuscode\model as modelBonuscode;
use pdima88\icms2bonuscode\backend as backendBonuscode;

/**
 * @property modelBonuscode $model
 * @property backendBonuscode $controller
 */
class types extends crudAction {

    const FORM_EDIT = 'type_edit';

    public function __construct($controller, array $params)
    {
        parent::__construct($controller, $params);
        $this->formName = 'type';
        $this->tableName = modelBonuscode::TABLE_TYPES;
        $this->pageTitle = 'Типы бонус-кодов';
        $this->titles['add'] = 'Новый тип';
        $this->titles['edit'] = 'Редактирование типа';
        $this->messages['add'] = 'Новый тип бонус-кодов добавлен, пожалуйста укажите параметры типа';
        $this->messages['error_edit_no_item'] = 'Тип бонус-кодов не найден';
    }

    function getGrid() {
        $select = Model::zendDbSelect()->from(Table::prefix(modelBonuscode::TABLE_TYPES));

        $grid = [
            'id' => 'bonuscode_types',
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
            'columns' => [
                'title' => [
                    'title' => 'Название типа бонус-кодов',
                    'filter' => 'text',
                    'sort' => true,
                ],
                'hint' => [
                    'title' => 'Описание',
                    'filter' => 'text',
                    'sort' => true,
                ],
                'component' => [
                    'title' => 'Компонент',
                    'filter' => 'select',
                    'sort' => 'true',
                    'format' => $this->model->getComponentList()
                ],
                'data' => [
                    'title' => 'Параметры',
                    'format' => __CLASS__.'::formatTypeData'
                ]
            ]
        ];

        return $grid;
    }

    protected $_item = null;
    public function actionEdit($id = null, $item = null) {
        $this->formName = self::FORM_EDIT;
        $id = $this->getParam();
        if (!$id) {
            cmsCore::error404();
        }

        $item = $this->getItem($id);

        if (!$item) {
            cmsUser::addSessionMessage($this->messages['error_edit_no_item'] ?? 'Запись не найдена', 'error');
            $this->redirectBack();
        }

        $this->_item = $item;
        return parent::actionEdit($id, $item);
    }

    public function getForm($name) {
        if ($name == self::FORM_EDIT) {
            $component = $this->_item['component'];
            $form = $this->controller->getControllerForm($component, 'bonuscode_type', $this->_item);
        } else {
            $form = $this->controller->getForm($name);
        }
        return $form;
    }

    function getItem($id) {
        return $this->model->getBonusCodeType($id);
    }

    function afterAdd($id = null)
    {
        $this->redirectToAction($this->current_action, ['edit', $id] , [
            'back' => $this->request->get('back', href_to($this->root_url, $this->name))
        ]);
    }

    static function formatTypeData($value, $row) {
        return nl2br(trim($value, "\t\r\n-"));
    }
}
