<?php

/**
* @link https://github.com/gilek/yii2-gtreetable
* @copyright Copyright (c) 2015 Maciej Kłak
* @license https://github.com/gilek/yii2-gtreetable/blob/master/LICENSE
*/

use gilek\gtreetable\Widget;
use gilek\gtreetable\assets\UrlAsset;
use gilek\gtreetable\assets\BrowserAsset;
use yii\jui\JuiAsset;
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
        return {
            type: 'GET',
            url: URI('".Url::to([$routes['nodeChildren']])."').addSearch({'id':id}).toString(),
            dataType: 'json',
            error: function(XMLHttpRequest) {
                alert(XMLHttpRequest.status+': '+XMLHttpRequest.responseText);
            }
        }; 
    }"),
    'onSave' => new JsExpression("function (oNode) {
        return {
            type: 'POST',
            url: !oNode.isSaved() ? '".Url::to([$routes['nodeCreate']])."' : URI('".Url::to([$routes['nodeUpdate']])."').addSearch({'id':oNode.getId()}).toString(),
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
        };        
    }"),
    'onDelete' => new JsExpression("function(oNode) {
        return {
            type: 'POST',
            url: URI('".Url::to([$routes['nodeDelete']])."').addSearch({'id':oNode.getId()}).toString(),
            dataType: 'json',
            error: function(XMLHttpRequest) {
                alert(XMLHttpRequest.status+': '+XMLHttpRequest.responseText);
            }
        };        
    }"),
    'onMove' => new JsExpression("function(oSource, oDestination, position) {
        return {
            type: 'POST',
            url: URI('".Url::to([$routes['nodeMove']])."').addSearch({'id':oSource.getId()}).toString(),
            data: {
                related: oDestination.getId(),
                position: position
            },
            dataType: 'json',
            error: function(XMLHttpRequest) {
                alert(XMLHttpRequest.status+': '+XMLHttpRequest.responseText);
            }
        };        
    }"),
    'language' => Yii::$app->language,
];

$options = !isset($options) ? $defaultOptions : ArrayHelper::merge($defaultOptions, $options);
if (array_key_exists('draggable', $options) && $options['draggable'] === true) {
    BrowserAsset::register($this);
    JuiAsset::register($this);
}
        
echo Widget::widget([
    'options'=> $options,
]);
