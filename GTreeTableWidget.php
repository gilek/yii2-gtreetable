<?php
/*
* @author Maciej "Gilek" Kłak
* @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
* @version 1.0b
* @package Yii2-GTreeTable
*/
namespace gilek\gtreetable;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\base\Widget;
use yii\web\AssetBundle;

class GTreeTableWidget extends Widget {
    
    public $options = [];
    
    public $htmlOptions = [];
    
    public $selector;   
    
    public $columnName;
    
    public $assetBundle;
    
    /**
     * @inheritdoc
     */
    public function init()
    {     
        $this->columnName = Yii::t('gtreetable','Name');
    } 
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();
        
        if ($this->selector===null) {       
            $htmlOptions = ArrayHelper::merge([
                'id'=>$this->getId()
            ],$this->htmlOptions);
            
            Html::addCssClass($htmlOptions, 'gtreetable');
            Html::addCssClass($htmlOptions, 'table');
            
            $output =  Html::beginTag('table', $htmlOptions);      
            $output .= Html::beginTag('thead');        
            $output .= Html::beginTag('tr');        
            $output .= Html::beginTag('th',array('width'=>'100%'));        
            $output .= $this->columnName;
            $output .= Html::endTag('th');        
            $output .= Html::endTag('tr');        
            $output .= Html::endTag('thead');        
            $output .= Html::endTag('table');
            
            return $output;
        }        
    }
    
    /**
     * Register widget asset.
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        $assetBundle = $this->assetBundle instanceof AssetBundle ? $this->assetBundle : GTreeTableAsset::register($view);
        
        if (array_key_exists('language', $this->options) && $this->options['language'] !== null) {
            $assetBundle->language = $this->options['language'];
        }

        if (array_key_exists('draggable', $this->options) && is_bool($this->options['draggable'])) {
            $assetBundle->draggable = $this->options['draggable'];
        }        
        
        $selector = $this->selector===null ? '#'.(array_key_exists('id', $this->htmlOptions) ? $this->htmlOptions['id'] : $this->getId()) : $this->selector;
        $options = Json::encode($this->options);

        $view->registerJs("jQuery('$selector').gtreetable($options);");
    }    
    
}

