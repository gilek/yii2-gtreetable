<?php
namespace gilek\gtreetable;

use yii\web\AssetBundle;

class GTreeTableAsset extends AssetBundle {
    /**
     * @inheritdoc
     */
    public $sourcePath = '@gilek/gtreetable/assets';

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
        'yii\web\JqueryAsset',
        'yii\jui\DraggableAsset',
        'yii\jui\DroppableAsset'
    ];  
    
    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV
    ];    
    
    public $language;
            
    public $minSuffix = '.min';
    
    public function registerAssetFiles($view)
    {
        $this->js[] = 'bootstrap-gtreetable'.(YII_ENV_DEV ? '' : $this->minSuffix).'.js';
        $this->css[] = 'gtreetable'.(YII_ENV_DEV ? '' : $this->minSuffix).'.css';
        
        if ($this->language !== null) {
            $this->js[] = 'languages/bootstrap-gtreetable.'.$this->language.'.js';
        }

        parent::registerAssetFiles($view);
    }    
}
