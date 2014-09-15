<?php
/*
* @author Maciej "Gilek" Kłak
* @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
* @version 1.0b
* @package Yii2-GTreeTable
*/
namespace gilek\gtreetable;

use yii\web\AssetBundle;

class GTreeTableHelperAsset extends AssetBundle {
    /**
     * @inheritdoc
     */
    public $sourcePath = '@gilek/gtreetable/assets';

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
