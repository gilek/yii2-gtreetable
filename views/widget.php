<?php
use gilek\gtreetable\GTreeTableWidget;
use gilek\gtreetable\GTreeTableAsset;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;

if (isset($title)) {
    $this->title = $title;
}

if (!isset($routes)) {
    $routes = [];
}

$controller = (!isset($controller)) ? '' : $controller.'/';

$routes = array_merge([
    'source' => $controller.'nodeChildren',
    'create' => $controller.'nodeCreate',
    'update' => $controller.'nodeUpdate',
    'delete' => $controller.'nodeDelete',
    'move' => $controller.'nodeMove'
],$routes);

$defaultOptions = [
    'draggable' => false,
    'source' => new JsExpression("function (id) {  
        return URI('".Url::to([$routes['source']])."').addSearch({'id':id});
    }"),
    'onSave' => new JsExpression("function (oNode) {
        return jQuery.ajax({
            type: 'POST',
            url: !oNode.isSaved() ? '".Url::to([$routes['create']])."' : URI('".Url::to([$routes['update']])."').addSearch({'id':oNode.getId()}),
            data: {
                parent: oNode.getParent(),
                name: oNode.getName(),
                position: oNode.getInsertPosition(),
                related: oNode.getRelatedNodeId()
            },
            dataType: 'json',
            error: function(XMLHttpRequest) {
                alert(XMLHttpRequest.status+': '+XMLHttpRequest.responseText);
            }
        });        
    }"),
    'onDelete' => new JsExpression("function(oNode) {
        return jQuery.ajax({
            type: 'POST',
            url: URI('".Url::to([$routes['delete']])."').addSearch({'id':oNode.getId()}),
            dataType: 'json',
            error: function(XMLHttpRequest) {
                alert(XMLHttpRequest.status+': '+XMLHttpRequest.responseText);
            }
        });        
    }"),
    'onMove' => new JsExpression("function(oSource, oDestination, position) {
        return jQuery.ajax({
            type: 'POST',
            url: URI('".Url::to([$routes['move']])."').addSearch({'id':oSource.getId()}),
            data: {
                related: oDestination.getId(),
                position: position
            },
            dataType: 'json',
            error: function(XMLHttpRequest) {
                alert(XMLHttpRequest.status+': '+XMLHttpRequest.responseText);
            }
        });        
    }"),    
];

$asset = GTreeTableAsset::register($this);
$asset->urlHelper = true;

echo GTreeTableWidget::widget([
    'options'=> !isset($options) ? $defaultOptions : ArrayHelper::merge($defaultOptions, $options),
    'asset' => $asset
]);
