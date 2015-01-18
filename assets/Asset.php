<?php

/**
* @link https://github.com/gilek/yii2-gtreetable
* @copyright Copyright (c) 2015 Maciej Kłak
* @license https://github.com/gilek/yii2-gtreetable/blob/master/LICENSE
*/

namespace gilek\gtreetable\assets;

use Yii;

class Asset extends \yii\web\AssetBundle {

    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/bootstrap-gtreetable/dist';

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $language;
    public $minSuffix = '.min';
    
    public function registerAssetFiles($view) {
        $this->js[] = 'bootstrap-gtreetable' . (YII_ENV_DEV ? '' : $this->minSuffix) . '.js';
        $this->css[] = 'bootstrap-gtreetable' . (YII_ENV_DEV ? '' : $this->minSuffix) . '.css';

        if ($this->language !== null) {
            $langFile = 'languages/bootstrap-gtreetable.' . $this->language . (YII_ENV_DEV ? '' : '.' . $this->minSuffix) . '.js';
            if (file_exists(Yii::getAlias($this->sourcePath . DIRECTORY_SEPARATOR . $langFile))) {
                $this->js[] = $langFile;
            }
        }

        parent::registerAssetFiles($view);
    }

}
