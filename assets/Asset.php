<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0.1-alpha
 * @package yii2-gtreetable
 */

namespace gilek\gtreetable\assets;

class Asset extends \yii\web\AssetBundle {

    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/bootstrap-gtreetable/dist';

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
    public $language;
    public $minSuffix = '.min';

    public function registerAssetFiles($view) {        
        $this->js[] = 'bootstrap-gtreetable' . (YII_ENV_DEV ? '' : $this->minSuffix) . '.js';
        $this->css[] = 'bootstrap-gtreetable' . (YII_ENV_DEV ? '' : $this->minSuffix) . '.css';

        if ($this->language !== null) {
            $this->js[] = 'languages/bootstrap-gtreetable.' . $this->language . (YII_ENV_DEV ? '' : '.' . $this->minSuffix) . '.js';
        }

        parent::registerAssetFiles($view);
    }

}
