<?php
/*
* @author Maciej "Gilek" Kłak
* @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
* @version 1.0b
* @package Yii2-GTreeTable
*/
namespace gilek\gtreetable\actions;

use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\db\Exception;
use yii\helpers\Json;
use yii\helpers\Html;
use gilek\gtreetable\models\TreeModel;

class NodeMoveAction extends BaseAction {
        
    public function run($id) {
        $model = $this->getNodeById($id);
        $model->scenario = 'move';
        $model->load(Yii::$app->request->post(),'');
                
        if (!$model->validate()) {             
            throw new HttpException(500,current(current($model->getErrors())));
        }        
                
        if (!($model->relatedNode instanceof $this->treeModelName)) {
            throw new NotFoundHttpException(Yii::t('gtreetable','Position indicated by related ID is not exists!'));
        }        
        
        try {
            $action = $this->getMoveAction($model);
            if (!call_user_func(array($model, $action), $model->relatedNode)) {
                throw new Exception(Yii::t('gtreetable','Moving operation `{name}` failed!',['{name}'=>Html::encode((string)$model)]));
            }
            echo Json::encode([
                'id'    => $model->getPrimaryKey(),
                'name'  => $model->name,
                'level' => $model->level,
                'type'  => $model->type                    
            ]);

        } catch(\Exception $e) {
            throw new HttpException(500,$e->getMessage());
        }   
    }
    
    protected function getMoveAction($model) {
        if ($model->relatedNode->isRoot() && $model->position !== TreeModel::POSITION_LAST_CHILD) {
            return 'moveAsRoot';
        } else if ($model->position === TreeModel::POSITION_BEFORE) {
            return 'moveBefore';
        } else if ($model->position === TreeModel::POSITION_AFTER) {
            return 'moveAfter';
        } else if ($model->position === TreeModel::POSITION_LAST_CHILD) {
            return 'moveAsLast';
        } else {   
            throw new HttpException(500, Yii::t('gtreetable','Unsupported move position!'));
        }     
    }    
}
?>