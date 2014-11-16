<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0.1-alpha
 * @package yii2-gtreetable
 */

namespace gilek\gtreetable;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\AssetBundle;
use gilek\gtreetable\assets\Asset;

class Widget extends \yii\base\Widget {

    public $options = [];
    public $htmlOptions = [];
    public $selector;
    public $columnName;
    public $assetBundle;

    /**
     * @inheritdoc
     */
    public function init() {
        $this->registerTranslations();
        if ($this->columnName === null) {
            $this->columnName = Yii::t('gtreetable', 'Name');
        }
    }

    /**
     * @inheritdoc
     */
    public function run() {
        $this->registerClientScript();

        if ($this->selector === null) {
            $htmlOptions = ArrayHelper::merge([
                'id' => $this->getId()
            ], $this->htmlOptions);

            Html::addCssClass($htmlOptions, 'gtreetable');
            Html::addCssClass($htmlOptions, 'table');

            $output = Html::beginTag('table', $htmlOptions);
            $output .= Html::beginTag('thead');
            $output .= Html::beginTag('tr');
            $output .= Html::beginTag('th', array('width' => '100%'));
            $output .= $this->options['columnName'];
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
    public function registerClientScript() {
        $view = $this->getView();
        $assetBundle = $this->assetBundle instanceof AssetBundle ? $this->assetBundle : Asset::register($view);

        if (array_key_exists('language', $this->options) && $this->options['language'] !== null) {
            $assetBundle->language = $this->options['language'];
        }

        $selector = $this->selector === null ? '#' . (array_key_exists('id', $this->htmlOptions) ? $this->htmlOptions['id'] : $this->getId()) : $this->selector;
        $options = Json::encode($this->options);

        $view->registerJs("jQuery('$selector').gtreetable($options);");
    }

    public function registerTranslations() {
        if (!isset(Yii::$app->i18n->translations['gtreetable'])) {
            Yii::$app->i18n->translations['gtreetable'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__.'/messages',
            ];
        }
    }

}
