<?php

/**
* @link https://github.com/gilek/yii2-gtreetable
* @copyright Copyright (c) 2015 Maciej Kłak
* @license https://github.com/gilek/yii2-gtreetable/blob/master/LICENSE
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

        $output = '';
        if ($this->selector === null) {
            $this->htmlOptions = ArrayHelper::merge([
                'id' => $this->getId()
            ], $this->htmlOptions);

            Html::addCssClass($this->htmlOptions, 'gtreetable');
            Html::addCssClass($this->htmlOptions, 'table');

            $output = Html::beginTag('table', $this->htmlOptions);
            $output .= Html::beginTag('thead');
            $output .= Html::beginTag('tr');
            $output .= Html::beginTag('th', array('width' => '100%'));
            $output .= $this->columnName;
            $output .= Html::endTag('th');
            $output .= Html::endTag('tr');
            $output .= Html::endTag('thead');
            $output .= Html::endTag('table');
        }
        $this->registerClientScript();
        return $output;
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

        $selector = $this->selector === null ? '#' . $this->htmlOptions['id'] : $this->selector;
        $options = Json::encode($this->options);

        $view->registerJs("jQuery('$selector').gtreetable($options);");
    }

    public function registerTranslations() {
        if (!isset(Yii::$app->i18n->translations['gtreetable'])) {
            Yii::$app->i18n->translations['gtreetable'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@gilek/gtreetable/messages',
            ];
        }
    }

}
