<?php
/*
* @author Maciej "Gilek" Kłak
* @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
* @version 1.0b
* @package Yii2-GTreeTable
*/
namespace gilek\gtreetable\actions;

use yii\base\Action;

class BaseAction extends Action {
    public $treeModelName;
    public $beforeRun;
    public $afterRun;
    
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
        $model = call_user_func([$this->treeModelName,'find'])->andWhere(['id' => $id])->with($with)->one();        
  
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('gtreetable','Position is not exists!'));
        }    
        return $model;
    } 

}
