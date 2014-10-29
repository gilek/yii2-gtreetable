<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0a
 * @package yii2-gtreetable
 */

namespace gilek\gtreetable\assets;

class UrlAsset extends \yii\web\AssetBundle {

    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/uri.js/src';

    /**
     * @inheritdoc
     */
    public $js = [
        'URI.min.js'
    ];

}
