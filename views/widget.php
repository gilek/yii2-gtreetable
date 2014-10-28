<?php
use gilek\gtreetable\GTreeTableWidget;
use gilek\gtreetable\assets\UrlAsset;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;

UrlAsset::register($this);

if (isset($title)) {
    $this->title = $title;
}

if (!isset($routes)) {
    $routes = [];
}

$controller = (!isset($controller)) ? '' : $controller.'/';

$routes = array_merge([
    'nodeChildren' => $controller.'nodeChildren',
    'nodeCreate' => $controller.'nodeCreate',
    'nodeUpdate' => $controller.'nodeUpdate',
    'nodeDelete' => $controller.'nodeDelete',
    'nodeMove' => $controller.'nodeMove'
],$routes);

$defaultOptions = [
    'source' => new JsExpression("function (id) {  
        return URI('".Url::to([$routes['nodeChildren']])."').addSearch({'id':id});
    }"),
    'onSave' => new JsExpression("function (oNode) {
        return jQuery.ajax({
            type: 'POST',
            url: !oNode.isSaved() ? '".Url::to([$routes['nodeCreate']])."' : URI('".Url::to([$routes['nodeUpdate']])."').addSearch({'id':oNode.getId()}),
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
            url: URI('".Url::to([$routes['nodeDelete']])."').addSearch({'id':oNode.getId()}),
            dataType: 'json',
            error: function(XMLHttpRequest) {
                alert(XMLHttpRequest.status+': '+XMLHttpRequest.responseText);
            }
        });        
    }"),
    'onMove' => new JsExpression("function(oSource, oDestination, position) {
        return jQuery.ajax({
            type: 'POST',
            url: URI('".Url::to([$routes['nodeMove']])."').addSearch({'id':oSource.getId()}),
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

echo GTreeTableWidget::widget([
    'options'=> !isset($options) ? $defaultOptions : ArrayHelper::merge($defaultOptions, $options),
]);
