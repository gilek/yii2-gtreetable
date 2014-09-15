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
use yii\db\Exception;
use yii\helpers\Html;

class NodeDeleteAction extends BaseAction { 
    public $depending = [];

    public function run($id) {
        parent::run();       
                
        $depending = array_keys($this->depending);
        $model = $this->getNodeById($id, $depending);
            

        if ($model->isRoot() && (integer)$model->find()->roots()->count() === 1) {
            throw new HttpException(500, Yii::t('gtreetable','Main element can`t be deleted!'));            
        }
        
        $nodes = $model->descendants()->with($depending)->all();
        $nodes[] = $model;
        
        $trans = $model->getDB()->beginTransaction();
        try {        
            foreach($nodes as $node) {
                foreach((array)$this->depending as $rel=>$message) {
                    if ($node->$rel > 0) {
                        throw new HttpException(400,  str_replace ('{count}', $node->$rel, $message));           
                    }  
                }
            }
            if (!$model->deleteNode()) { 
                throw new Exception(Yii::t('gtreetable','Deleting operation `{name}` failed!',['{name}'=>Html::encode((string)$model)]));               
            }
            $trans->commit();
            return true;
        } catch(\Exception $e) {
            $trans->rollBack();
            throw new HttpException(500,$e->getMessage());
        }         
    }
}
?>
