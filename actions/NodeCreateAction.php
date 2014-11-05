<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0.1-alpha
 * @package yii2-gtreetable
 */

namespace gilek\gtreetable\actions;

use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\db\Exception;
use yii\helpers\Json;
use yii\helpers\Html;
use gilek\gtreetable\models\TreeModel;

class NodeCreateAction extends ModifyAction {

    public function run() {
        $model = new $this->treeModelName();
        $model->scenario = 'create';
        $model->load(Yii::$app->request->post(), '');

        if (!$model->validate()) {
            throw new HttpException(500, current(current($model->getErrors())));
        }

        $isRootNode = !(integer) $model->parent > 0;

        if (!$isRootNode && !($model->relatedNode instanceof $this->treeModelName)) {
            throw new NotFoundHttpException(Yii::t('gtreetable', 'Position indicated by related ID is not exists!'));
        }

        try {
            if (is_callable($this->beforeAction)) {
                call_user_func_array($this->beforeAction,['model' => $model]);
            }
            
            $action = $isRootNode ? 'saveNode' : $this->getInsertAction($model);
            if (!call_user_func(array($model, $action), $model->relatedNode)) {
                throw new Exception(Yii::t('gtreetable', 'Adding operation `{name}` failed!', ['{name}' => Html::encode((string) $model)]));
            }
            
            if (is_callable($this->afterAction)) {
                call_user_func_array($this->afterAction,['model' => $model]);
            }             
            
            echo Json::encode([
                'id' => $model->getPrimaryKey(),
                'name' => $model->name,
                'level' => $model->level,
                'type' => $model->type
            ]);
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    protected function getInsertAction($model) {
        if ($model->position === TreeModel::POSITION_BEFORE) {
            return 'insertBefore';
        } else if ($model->position === TreeModel::POSITION_AFTER) {
            return 'insertAfter';
        } else if ($model->position === TreeModel::POSITION_FIRST_CHILD) {
            return 'prependTo';
        } else if ($model->position === TreeModel::POSITION_LAST_CHILD) {
            return 'appendTo';
        } else {
            throw new HttpException(500, Yii::t('gtreetable', 'Unsupported insert position!'));
        }
    }

}

?>