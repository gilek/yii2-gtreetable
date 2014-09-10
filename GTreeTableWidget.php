<?php
namespace gilek\gtreetable;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\base\Widget;

class GTreeTableWidget extends Widget {
    
    public $options = [];
    
    public $htmlOptions = [];
    
    public $selector;   
    
    public $columnName;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->registerTranslations();        
        $this->columnName = Yii::t('gtreetable','Name');
        
    } 
    
    /**
     * Register widget translations.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['gtreetable'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@gilek/gtreetable/messages',      
        ];
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
        
        if (array_key_exists('language', $this->options) && $this->options['language'] !== null) {
            $asset->language = $this->options['language'];
        }

        $selector = $this->selector===null ? '#'.(array_key_exists('id', $this->htmlOptions) ? $this->htmlOptions['id'] : $this->getId()) : $this->selector;
        $options = Json::encode($this->options);

        $view->registerJs("jQuery('$selector').gtreetable($options);");
    }    
    
}

