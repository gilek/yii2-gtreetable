<?php

/**
* @link https://github.com/gilek/yii2-gtreetable
* @copyright Copyright (c) 2015 Maciej Kłak
* @license https://github.com/gilek/yii2-gtreetable/blob/master/LICENSE
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
