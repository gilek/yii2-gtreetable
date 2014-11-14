<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0.1-alpha
 * @package yii2-gtreetable
 */

namespace gilek\gtreetable\models;

use creocoder\behaviors\NestedSet;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * @property integer $parent
 * @property string $position
 * @property integer $related
 * @property string $nameAttribute
 * @property string $typeAttribute
 * @property boolean $hasManyRoots
 * @property string $rootAttribute
 * @property string $leftAttribute
 * @property string $rightAttribute
 * @property string $levelAttribute
 */
abstract class TreeModel extends ActiveRecord {

    const POSITION_BEFORE = 'before';
    const POSITION_AFTER = 'after';
    const POSITION_FIRST_CHILD = 'firstChild';
    const POSITION_LAST_CHILD = 'lastChild';
    const TYPE_DEFAULT = 'default';

    public $parent;
    public $position;
    public $related;
    public $nameAttribute = 'name';
    public $typeAttribute = 'type';
    public $hasManyRoots = true;
    public $rootAttribute = 'root';
    public $leftAttribute = 'lft';
    public $rightAttribute = 'rgt';
    public $levelAttribute = 'level';
    
    public function getName() {
        return $this->{$this->nameAttribute};
    }

    public function getType() {
        return $this->{$this->typeAttribute};
    }

    public function getLeft() {
        return $this->{$this->leftAttribute};
    }

    public function getRight() {
        return $this->{$this->rightAttribute};
    }

    public function getLevel() {
        return $this->{$this->levelAttribute};
    }

    public function setName($name) {
        $this->{$this->nameAttribute} = $name;
    }

    public function setType($type) {
        $this->{$this->typeAttribute} = $type;
    }

    public function setLeft($left) {
        $this->{$this->leftAttribute} = $left;
    }

    public function setRight($right) {
        $this->{$this->rightAttribute} = $right;
    }

    public function setLevel($level) {
        $this->{$this->levelAttribute} = $level;
    }  

    public function __toString() {
        return $this->{$this->nameAttribute};
    }

    public function getPositions() {
        return [
            self::POSITION_BEFORE,
            self::POSITION_AFTER,
            self::POSITION_FIRST_CHILD,
            self::POSITION_LAST_CHILD
        ];
    }

    public function getNestedSetParams() {
        $params = [];
        foreach (['rootAttribute', 'leftAttribute', 'rightAttribute', 'levelAttribute', 'hasManyRoots'] as $attribute) {
            if ($this->$attribute !== null) {
                $params[$attribute] = $this->$attribute;
            }
        }        
        return $params;
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            array_merge(['class' => NestedSet::className()], $this->getNestedSetParams())
        ];
    }

    public function findNestedSet() {
        $query = new TreeQuery(get_called_class(), ['nestedSetParams' => $this->getNestedSetParams()]);
        return $query;   
    }
    
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['parent', 'required',],
            ['related', 'required',],
            ['position', 'required',],
            ['position', 'in', 'range' => $this->getPositions(),],
            [$this->nameAttribute, 'required'],
            [$this->nameAttribute, 'string', 'max' => 128],
            [$this->nameAttribute, 'filter', 'filter' => function($value) {
                    return Html::encode($value);
                }, 'skipOnError' => true]
        ];
    }

    function scenarios() {
        return [
            'create' => ['parent', 'related', 'position', $this->nameAttribute],
            'update' => [$this->nameAttribute],
            'move' => ['related', 'position'],
            self::SCENARIO_DEFAULT => [],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert) {
        parent::beforeSave($insert);

        if ($this->isNewRecord) {
            $this->{$this->typeAttribute} = self::TYPE_DEFAULT;
        }
        return true;
    }

    public function getRelatedNode() {
        return $this->hasOne(get_class($this), ['id' => 'related']);
    }

    /**
     * 
     * @param string $glue
     * @return string
     */
    public function getPath($glue = ' » ') {
        $path = array();
        foreach ($this->ancestors()->all() as $model) {
            $path[] = (string) $model;
        }
        $path[] = (string) $this;
        krsort($path);
        return implode($glue, $path);
    }

}
