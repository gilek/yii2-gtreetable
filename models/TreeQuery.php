<?php
/*
* @author Maciej "Gilek" Kłak
* @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
* @version 1.0b
* @package Yii2-GTreeTable
*/
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