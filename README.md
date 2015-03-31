# Yii2-GTreeTable

Yii2-GTreeTable is extension of Yii 2 framework which is wrapper for [bootstrap-gtreetable](https://github.com/gilek/bootstrap-gtreetable) plugin, on the other hand provides support to server side application.

Thanks to software it's possible to map actual state of nodes to data base.

Test available on [demo project](http://gtreetable2.gilek.net).

![](http://gilek.net/images/gtt2-demo.png)

## Installation

Installation is realized by [Composer](https://getcomposer.org).

In the console write:

```
composer require gilek/yii2-gtreetable "*"
```
or add following line in `require` section of `composer.json` file.

```
"gilek/yii2-gtreetable": "*"
```

One more thing, don't forget about the `fxp/composer-asset-plugin` installation:

```
composer global require fxp/composer-asset-plugin "1.0.0"
```

## Minimal configuration<a name="minimal-configuration"></a>

> Note: You can also use a migrate file and omit following two steps:
> `yii migrate --migrationPath=<app_dir>/vendor/gilek/yii2-gtreetable/migrations`

1. Create table to store nodes:

  ``` sql
  CREATE TABLE `tree` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `root` INT(10) UNSIGNED DEFAULT NULL,
    `lft` INT(10) UNSIGNED NOT NULL,
    `rgt` INT(10) UNSIGNED NOT NULL,
    `level` SMALLINT(5) UNSIGNED NOT NULL,
    `type` VARCHAR(64) NOT NULL,
    `name` VARCHAR(128) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `root` (`root`),
    KEY `lft` (`lft`),
    KEY `rgt` (`rgt`),
    KEY `level` (`level`)
  );
  ```

2. Add main node:

  ``` sql
  INSERT INTO `tree` (`id`, `root`, `lft`, `rgt`, `level`, `type`, `name`) VALUES (1, 1, 1, 2, 0, 'default', 'Main node');
  ```

3. Create new [active record](http://www.yiiframework.com/doc-2.0/guide-db-active-record.html) model, based on table described in point 1.
It's important that model extend `gilek\gtreetable\models\TreeModel` class:
    
  ``` php
  <?php
  class Tree extends \gilek\gtreetable\models\TreeModel 
  {
    public static function tableName()
    {
      return 'tree';
    }
  }
  ?>
  ```
    
4. Create new controller or add to existing one following actions:	

  ``` php
  <?php
  use app\models\Tree;
  
  class TreeController extends \yii\web\Controller
  {        
    public function actions() {
      return [
        'nodeChildren' => [
          'class' => 'gilek\gtreetable\actions\NodeChildrenAction',
          'treeModelName' => Tree::className()
        ],
        'nodeCreate' => [
          'class' => 'gilek\gtreetable\actions\NodeCreateAction',
          'treeModelName' => Tree::className()
        ],
        'nodeUpdate' => [
          'class' => 'gilek\gtreetable\actions\NodeUpdateAction',
          'treeModelName' => Tree::className()
        ],
        'nodeDelete' => [
          'class' => 'gilek\gtreetable\actions\NodeDeleteAction',
          'treeModelName' => Tree::className()
        ],
        'nodeMove' => [
          'class' => 'gilek\gtreetable\actions\NodeMoveAction',
          'treeModelName' => Tree::className()
        ],            
      ];
    }
    
    public function actionIndex() {
      return $this->render('@gilek/gtreetable/views/widget', ['options'=>[
        // 'manyroots' => true 
        // 'draggable' => true
      ]]);
    }
  }
  ?>
  ```

## Configuration

### Actions

All actions from `gilek\gtreetable\actions` location have properties:

  + `afterAction` (callback(`gilek\gtreetable\models\TreeModel` $model)) function triggered directly after code responsible for action task i.e. after node deleting,

  + `$afterRun` (callback) - function triggered after run the action,

  + `beforeAction` (callback(`gilek\gtreetable\models\TreeModel` $model)) - function triggered directly before code responsible for action task i.e. before node deleting,

  + `$beforeRun` (callback) - function triggered before run the action. More info in [yii\base\Action class documentation](http://www.yiiframework.com/doc-2.0/yii-base-action.html#afterRun%28%29-detail).

  Example of use, checking access to authorization unit:

  ```php
  <?php  
  [
  'nodeCreate' => [
    'class' => 'gilek\gtreetable\actions\NodeCreateAction',
    'treeModelName' => Tree::className(),
    'beforeRun' => function() {
      if (!Yii::$app->user->can('Node create')) {
        throw new \yii\web\ForbiddenHttpException();
      }
    }
  ]
  ?>  
  ```

  + `$treeModelName` (TreeModel) - reference to model data extending form `gilek\gtreetable\models\TreeModel` (see [Minimal configuration](#minimal-configuration) point 1).
 
### Model 

Support of tree structure in data base is based on [Nested set model](http://en.wikipedia.org/wiki/Nested_set_model).

Abstract class `gilek\gtreetable\models\TreeModel` provides Nested set model on PHP side. It defines validation rules and other required methods. Its configuration can by adjusted by parameters:

  + `$depthAttribute` (string) - column name storing level of node. Defualt `level`, 

  + `$leftAttribute` (string) - column name storing left value. Default `lft`,  

  + `$nameAttribute` (string) - column name storing label of node. Defualt `name`,    

  + `$rightAttribute` (string) - column name storing left value. Default `rgt`,   

  + `$treeAttribute` (string) - column name storing reference to main element ID. Default `root`,  

  + `$typeAttribute` (string) - column name storing type of node. Default `type`.  

### View

`gilek\gtreetable\views\widget` view class consists configuration of [CUD operation](https://github.com/gilek/bootstrap-gtreetable#cud) with reference to [nodes source](https://github.com/gilek/bootstrap-gtreetable#source). There is no necessity to use it, but it can be very helpful in simple projects. 

Class may be adjusted by properties:

  + `$controller` (string) - controller name where the actions are defined (see [Minimal configuration](#minimal-configuration) point 4). By default is getting the controller name where the `gilek\gtreetable\views\widget` view was triggered,

  + `$options` (array) - options supplied directly to bootstrap-gtreetable plugin,

  + `$routes` (array) - in the case when particular nodes are located in different containers or its name is different in relation to presented in point 4 of the chapter [Minimal configutarion](#minimal-configutarion), then it's necessary to define it,

  Following example shows structure of data:

  ``` php
  <?php
  [
    'nodeChildren' => 'controllerA/source',
    'nodeCreate' => 'controllerB/create',
    'nodeUpdate' => 'controllerC/update',
    'nodeDelete' => 'controllerD/delete',
    'nodeMove' => 'controllerE/move'
  ]
  ?>
  ```

  + `$title` (string) - define site title, when view is called directly from action level (see [Minimal configuration](#minimal-configuration) point 4).

### Widget   

The main task of `gilek\gtreetable\Widget` widget is generate parameters to bootstrap-gtreetable plugin and adding required files.
When container in not available he also response for creating it. Class has following properties:

  + `$assetBundle` (AssetBundle) - parameter allows to overflow the main `AssetBundle` packet i.e. `Asset`, 

  + `$columnName` (string) - table column name. Default value is `Name` which is getting from translation file,

  + `$htmlOptions` (array) - html options of container, they are rendering in the moment of its creation (parameter `$selector` set on `null`),  

  + `$options` (array) - options supplied directly to bootstrap-gtreetable plugin,  

  + `$selector` (string) - jQuery selector indicated on tree container (`<table>` tag). When parameter is set on `null`, table will be automatically created. Default `null`.

## Limitations

Yii2-GTreeTable use [Nested Set behavior for Yii 2](https://github.com/creocoder/yii2-nested-set-behavior) extension, which in for the moment (Januray 2015) has some limitation regarding ordering main elements (nodes which level = 0). 

In case of adding or moving node as the main node, then it will be located after last element in this level. Therefore order of displayed main nodes may not have the same mapping in data base.
