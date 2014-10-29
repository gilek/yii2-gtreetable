<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0.0-alpha
 * @package yii2-gtreetable
 */

namespace gilek\gtreetable\assets;

class BrowserAsset extends \yii\web\AssetBundle {

    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/jquery.browser/dist';

    /**
     * @inheritdoc
     */
    public $js = [
        'jquery.browser.min.js'
    ];
    
    public $depends = [
        'yii\web\JqueryAsset',
    ];    

}
