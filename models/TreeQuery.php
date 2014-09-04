<?php
namespace gilek\gtreetable\models;

use yii\db\ActiveQuery;
use creocoder\behaviors\NestedSetQuery;

class TreeQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            [
                'class' => NestedSetQuery::className(),
            ],
        ];
    }
}