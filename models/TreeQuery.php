<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0.1-alpha
 * @package yii2-gtreetable
 */

namespace gilek\gtreetable\models;

use yii\db\ActiveQuery;
use creocoder\behaviors\NestedSetQuery;

class TreeQuery extends ActiveQuery {

    public $nestedSetParams = [];
    
    public function behaviors() {
        return [
            array_merge(['class' => NestedSetQuery::className()], $this->nestedSetParams)
        ];
    }

}
