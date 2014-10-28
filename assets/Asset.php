<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0a
 * @package yii2-gtreetable
 */

namespace gilek\gtreetable\assets;

class Asset extends yii\web\AssetBundle\AssetBundle {

    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/bootstrap-gtreetable';

    /**
     * @inheritdoc
     */
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    public $language;
    public $draggable = false;
    public $minSuffix = '.min';

    public function registerAssetFiles($view) {
        if ($this->draggable === true) {
            $this->js[] = 'jquery.browser.js';
            JuiAsset::register($view);
            BrowserAsset::register($view);
        }

        $this->js[] = 'bootstrap-gtreetable' . (YII_ENV_DEV ? '' : $this->minSuffix) . '.js';
        $this->css[] = 'gtreetable' . (YII_ENV_DEV ? '' : $this->minSuffix) . '.css';

        if ($this->language !== null) {
            $this->js[] = 'languages/bootstrap-gtreetable.' . $this->language . (YII_ENV_DEV ? '' : '.' . $this->minSuffix) . '.js';
        }

        parent::registerAssetFiles($view);
    }

}
