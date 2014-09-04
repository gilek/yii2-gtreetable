<?php
namespace gilek\gtreetable;

use yii\web\AssetBundle;

class GTreeTableHelperAsset extends AssetBundle {
    /**
     * @inheritdoc
     */
    public $sourcePath = '@gilek/gtreetable/widgetAssets';

    /**
     * @inheritdoc
     */    
    public $js = [
        'URI.js'
    ];
    
    /**
     * @inheritdoc
     */    
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];      

   
}
