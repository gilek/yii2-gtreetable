<?php

/**
* @link https://github.com/gilek/yii2-gtreetable
* @copyright Copyright (c) 2015 Maciej Kłak
* @license https://github.com/gilek/yii2-gtreetable/blob/master/LICENSE
*/

namespace gilek\gtreetable\models;

use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class TreeQuery extends ActiveQuery {

    public $nestedSetParams = [];
    
    public function behaviors() {
        return [
            array_merge(['class' => NestedSetsQueryBehavior::className()], $this->nestedSetParams)
        ];
    }

}
