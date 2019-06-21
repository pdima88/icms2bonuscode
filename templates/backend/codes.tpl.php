<?php
/** @var cmsTemplate $this */
$action = 'codes';
$this->renderAsset('icms2ext/backend/treeandgrid', [
    'tree' => $groups,
    'grid' => $grid,
    'id' => $group_id,
    'page_title' => 'Бонус-коды',
    'page_url' => $page_url,
    'treeitem_detail_url' => $this->href_to($action, 'group_info'),
    'toolbar' => [
        'add' => [
            'title' => 'Создать бонус-код',
            'href' => $this->href_to($action, ['add', '{id}']).'?back={returnUrl}',
        ],
        'add_folder' => [
            'title' => 'Новая группа',
            'href'  => $this->href_to($action, 'group_add').'?back={returnUrl}',
        ],
        'edit' => [
            'title' => 'Редактировать группу',
            'href'  => $this->href_to($action,['group_edit', '{id}']).'?back={returnUrl}',
            'hide' => true,
        ],
        'delete' => [
            'title' => 'Удалить группу',
            'href'  => $this->href_to($action, ['group_delete', '{id}']).'?back={returnUrl}&csrf_token='.cmsForm::getCSRFToken(),
            'hide' => true,
            'onclick' => "return confirm('Все бонус-коды внутри группы останутся, но будут отвязаны от группы!')"
        ],
        'excel' => [
            'title' => 'Экспорт',
            'export' => 'csv',
            'target' => '_blank',
        ]
    ],
]);


