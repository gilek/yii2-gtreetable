<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0.1-alpha
 * @package yii2-gtreetable
 */

namespace gilek\gtreetable\actions;

use Yii;
use yii\base\Action;

abstract class BaseAction extends Action {

    public $treeModelName;
    public $beforeRun;
    public $afterRun;
    public $beforeAction;
    public $afterAction;

	public function init() {
		parent::init();
		$this->registerTranslations();
	}
	
    protected function beforeRun() {
        if (is_callable($this->beforeRun)) {
            return call_user_func($this->beforeRun);
        }
        return parent::beforeRun();
    }

    protected function afterRun() {
        if (is_callable($this->afterRun)) {
            return call_user_func($this->afterRun);
        }
        parent::afterRun();
    }

    public function getNodeById($id, $with = []) {
        $model = (new $this->treeModelName)->findNestedSet()->andWhere(['id' => $id])->with($with)->one();
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('gtreetable', 'Position is not exists!'));
        }
        return $model;
    }

	public function registerTranslations() {
        if (!isset(Yii::$app->i18n->translations['gtreetable'])) {
            Yii::$app->i18n->translations['gtreetable'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__.'/../messages',
            ];
        }
    }
	
}
