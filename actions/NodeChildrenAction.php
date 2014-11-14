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
use yii\helpers\Json;
use yii\validators\NumberValidator;

class NodeChildrenAction extends BaseAction {

    public function run($id) {
        $validator = new NumberValidator();
        $validator->integerOnly = true;
        if (!$validator->validate($id, $error)) {
            throw new HttpException(500, $error);
        }

        $query = (new $this->treeModelName)->findNestedSet();
        
        $nodes = [];
        if ($id == 0) {
            $nodes = $query->roots()->all();
        } else {
            $parent = $query->where(['id' => $id])->one();
            if ($parent === null) {
                throw new NotFoundHttpException(Yii::t('gtreetable', 'Position indicated by parent ID is not exists!'));
            }
            $nodes = $parent->children()->all();
        }
        $result = [];
        foreach ($nodes as $node) {
            $result[] = array(
                'id' => $node->getPrimaryKey(),
                'name' => $node->getName(),
                'level' => $node->getLevel(),
                'type' => $node->getType()
            );
        }
        echo Json::encode($result);
    }

}