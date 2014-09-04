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
use yii\helpers\Json;
use yii\validators\NumberValidator;

class NodeChildrenAction extends BaseAction {
    
    public function run($id) {
        parent::run($id);        
        
        $validator = new NumberValidator();
        $validator->integerOnly = true;
        if (!$validator->validate($id,$error)) {
            throw new HttpException(500,$error);
        }
        
        $query = (new $this->treeModelName)->find();

        $nodes = [];
        if ($id==0) {
            $nodes = $query->roots()->all();
        } else {
            $parent = $query->where(['id'=>$id])->one();
            if ($parent===null) {
                throw new NotFoundHttpException(Yii::t('gtreetable','Position indicated by parent ID is not exists!'));
            }
            $nodes = $parent->children()->all();
        }
        $result = [];
        foreach($nodes as $node) {
            $result[] = array(
                'id'    => $node->id,
                'name'  => $node->name,
                'level' => $node->level,
                'type'  => $node->type
            );
        }
        echo Json::encode($result);
    }
}
?>
