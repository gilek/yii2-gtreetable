<?php
/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0a
 * @package yii2-gtreetable
 */

namespace gilek\gtreetable\assets;

class GTreeTableJuiAsset extends yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */    
    public $sourcePath = '@bower/jquery-ui';
    
    /**
     * @inheritdoc
     */    
    public $js = [
        'jquery-ui.js',
    ];
    
    /**
     * @inheritdoc
     */    
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
