<?php

namespace gilek\gtreetable;

class GTreeTableJuiAsset extends yii\web\AssetBundle
{
    public $sourcePath = '@bower/jquery-ui';
    public $js = [
        'jquery-ui.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
