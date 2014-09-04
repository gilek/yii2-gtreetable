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
    public $css = [
        'gtreetable.css'
    ];    

    /**
     * @inheritdoc
     */
    public $js = [
        'bootstrap-gtreetable.js',
        //'bootstrap-gtreetable.min.js'
    ];

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
        'forceCopy' => YII_DEBUG
    ];    
    
    public $language;
        
    public $urlHelper = false;
    
    public function registerAssetFiles($view)
    {
        if ($this->language !== null) {
            $this->js[] = 'languages/bootstrap-gtreetable.'.$this->language.'.js';
        }
        
        if ($this->urlHelper === true) {
            $this->js[] = 'URI.js';
        } 

        parent::registerAssetFiles($view);
    }    
}
