<?php
/*
* @author Maciej "Gilek" Kłak
* @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
* @version 1.0b
* @package Yii2-GTreeTable
*/
namespace gilek\gtreetable\actions;

use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class BaseAction extends Action {
    public $treeModelName;
    public $access;
    
    public function run($params = []) {
        if ($this->access!==null) {
            if (!Yii::$app->user->can($this->access)) {
                throw new ForbiddenHttpException();  
            }    
        }         
    }
    
    public function getNodeById($id, $with = []) {
        $model = call_user_func([$this->treeModelName,'find'])->andWhere(['id' => $id])->with($with)->one();        
  
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('gtreetable','Position is not exists!'));
        }    
        return $model;
    } 

}
