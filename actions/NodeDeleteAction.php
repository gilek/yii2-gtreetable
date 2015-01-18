<?php

/**
* @link https://github.com/gilek/yii2-gtreetable
* @copyright Copyright (c) 2015 Maciej Kłak
* @license https://github.com/gilek/yii2-gtreetable/blob/master/LICENSE
*/

namespace gilek\gtreetable\actions;

use Yii;
use yii\web\HttpException;
use yii\db\Exception;
use yii\helpers\Html;

class NodeDeleteAction extends ModifyAction {

    public function run($id) {
        $model = $this->getNodeById($id);

        if ($model->isRoot() && (integer) $model->find()->roots()->count() === 1) {
            throw new HttpException(500, Yii::t('gtreetable', 'Main element can`t be deleted!'));
        }

        $trans = $model->getDB()->beginTransaction();
        try {
            if (is_callable($this->beforeAction)) {
                call_user_func_array($this->beforeAction,['model' => $model]);
            }
            
            if (!$model->deleteWithChildren()) {
                throw new Exception(Yii::t('gtreetable', 'Deleting operation `{name}` failed!', ['{name}' => Html::encode((string) $model)]));
            }
            
            if (is_callable($this->afterAction)) {
                call_user_func_array($this->afterAction,['model' => $model]);
            }          
            
            $trans->commit();
            return true;
        } catch (\Exception $e) {
            $trans->rollBack();
            throw new HttpException(500, $e->getMessage());
        }
    }

}
