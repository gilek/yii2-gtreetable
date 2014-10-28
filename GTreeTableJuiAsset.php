<?php
/*
* @author Maciej "Gilek" Kłak
* @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
* @version 1.0b
* @package Yii2-GTreeTable
*/

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
