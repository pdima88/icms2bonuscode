<?php
/** @var cmsTemplate $this */
$action = 'types';
$this->renderAsset('icms2ext/backend/grid', [
    'grid' => $grid,
    'page_title' => $page_title,
    'page_url' => $page_url,
    'toolbar' => [
        'add' => [
            'title' => 'Создать тип',
            'href' => $this->href_to($action, ['add']).'?back={returnUrl}',
        ],
        'excel' => [
            'title' => 'Экспорт',
            'export' => 'csv',
            'target' => '_blank',
        ]
    ],
]);


