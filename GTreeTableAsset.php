<?php
namespace gilek\gtreetable;

use yii\web\AssetBundle;

class GTreeTableAsset extends AssetBundle {
    /**
     * @inheritdoc
     */
    public $sourcePath = '@gilek/gtreetable/gtreetable';

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
        'yii\jui\JuiAsset',
    ];  
    
    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV
    ];    
    
    public $language;
    
    public $draggable = false;
            
    public $minSuffix = '.min';
    
    public function registerAssetFiles($view)
    {
        if ($this->draggable === true) {
            $this->js[] = 'jquery.browser.js';        
        }
        
        $this->js[] = 'bootstrap-gtreetable'.(YII_ENV_DEV ? '' : $this->minSuffix).'.js';
        $this->css[] = 'gtreetable'.(YII_ENV_DEV ? '' : $this->minSuffix).'.css';
        
        if ($this->language !== null) {
            $this->js[] = 'languages/bootstrap-gtreetable.'.$this->language. (YII_ENV_DEV ? '' : '.'.$this->minSuffix). '.js';
        }

        parent::registerAssetFiles($view);
    }    
}
