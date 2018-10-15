<?php

namespace core\components\TreeManager;


use core\entities\Article\Article;
use core\entities\Board\Board;
use core\entities\Board\BoardCategory;
use core\entities\Company\Company;
use core\entities\Trade\Trade;
use Yii;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\Url;

class TreeManagerWidget extends Widget
{
    /* @var $roots array|ActiveRecord[] */
    public $roots;

    /* @var $url string Начало Url с контроллером без экшина в котором используется виджет(например '/board/category') */
    public $url;

    public $regionsEnabled = true; //Выводить иконку глобуса для настроек регионов раздела

    private $source;

    public function init()
    {
        $this->source = self::getSourceData($this->roots, $this->url);
        return parent::init();
    }

    public function run()
    {
        ?>
        <table id="tree_<?= $this->id ?>" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <?php
        //echo Html::tag('div', '', ['id' => 'tree_' . $this->id]);
        $this->registerJs();
    }

    private function registerJs(): void
    {
        $view = $this->getView();
        TreeAsset::register($view);
        $view->registerJs($this->js());
    }

    public static function getSourceData($items, $url): string
    {
        $source = [];
        array_walk($items, function ($item, $key) use (&$source, $url) {
            /* @var $item BoardCategory */
            $hasChildren = $item->getChildren()->count() ? true : false;
            $elementsCount = $item->getElementsCount();
            $source[] = [
                'key' => $item->id,
                'title' => $item->name . " (" . $elementsCount . ")",
                'folder' => $hasChildren,
                'lazy' => $hasChildren,
                'data' => [
                    'geoUrl' => Url::to([$url . '/update', 'id' => $item->id, 'tab' => 'geo']),
                    'editUrl' => Url::to([$url . '/update', 'id' => $item->id]),
                ],
            ];
        });
        return Json::encode($source);
    }

    private function js(): string
    {
        $actionUrl = Url::to([$this->url . '/tree-manage']);

        return <<<JS
$('#tree_{$this->id}').fancytree({
    source: {$this->source},
    titlesTabbable: true, 
    extensions: ["table","dnd"],
	table: {
		indentation: 16         // indent every node level by 16px
		//nodeColumnIdx: 2         // render node expander, icon, and title to this column (default: #0)
	},
    lazyLoad: function(event, data) {
      var node = data.node;
      data.result = {
        url: "{$actionUrl}",
        data: {id: node.key}
      }
    },
    renderColumns: function(event, data) {
		var node = data.node,
			tdList = $(node.tr).find(">td");
		
		tdList.eq(1).html(
		    '<a href="' + node.data.geoUrl + '" data-toggle="tooltip" title="Привязка к регионам"><i class="fa fa-globe"></i></a>' +
		    '<a href="' + node.data.editUrl + '" data-toggle="tooltip" title="Редактировать"><i class="fa fa-pencil"></i></a>'
		);
	},
	dnd: {
        // Available options with their default:
        autoExpandMS: 400,   // Expand nodes after n milliseconds of hovering
        //draggable: null,      // Additional options passed to jQuery UI draggable
        //droppable: null,      // Additional options passed to jQuery UI droppable
        dropMarkerOffsetX: -24,  // absolute position offset for .fancytree-drop-marker
                                 // relatively to ..fancytree-title (icon/img near a node accepting drop)
        dropMarkerInsertOffsetX: -16, // additional offset for drop-marker with hitMode = "before"/"after"
        focusOnClick: false,  // Focus, although draggable cancels mousedown event (#270)
        preventRecursiveMoves: true, // Prevent dropping nodes on own descendants
        preventVoidMoves: true,      // Prevent dropping nodes 'before self', etc.
        smartRevert: true,    // set draggable.revert = true if drop was rejected
    
        dragStart: function(node, data) { return true; },
        dragEnter: function(node, data) { return true; },
        dragExpand: function(node, data) { return true; },
        dragOver: function(node, data) {},
        dragLeave: function(node, data) {},
        dragStop: function(node, data) {},
        dragDrop: function(node, data) {
            data.otherNode.moveTo(node, data.hitMode);
            console.log(data);
            $.get('{$actionUrl}', { act: data.hitMode, to: data.node.key, id: data.otherNode.key })
                .fail(function() {
                    alert( "Ошибка на сервере..." );
                });
        }
    }
});
JS;
    }

}