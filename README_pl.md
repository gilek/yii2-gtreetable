# Yii2-GTreeTable

Yii2-GTreeTable jest rozszerzeniem frameworka Yii 2, które z jednej strony stanowi opakowanie pluginu [GTreeTable](https://github.com/gilek/GTreeTable), z drugiej zapewnia jego obsługę od strony serwerowej.

Dzięki oprogramowaniu możliwa staje się realizacja operacji typu CRUD oraz zmiana położenia węzła wewnątrz drzewa.

Działanie aplikacji można przetestować na [demo projektu](http://gtreetable.gilek.net).

![](http://gilek.net/images/gtt2-demo.png)

## Instalacja

Instalacja odbywa się za pomocą menadżera [Composer](https://getcomposer.org).

W konsoli wpisz polecenie:

```
php composer.phar require  "gilek/Yii2-GTreeTable *"
```

lub dodaj następującą linijkę do sekcji `require` pliku `composer.json` Twojego projektu:

```
"gilek/Yii2-GTreeTable": "*"
```

## Minimalna konfiguracja

1. Tworzymy tabelę do przechowywania węzłów:

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

2. Dodajemy węzeł główny:

    ``` sql
    INSERT INTO `tree` (`id`, `root`, `lft`, `rgt`, `level`, `type`, `name`) VALUES (1, 1, 0, 1, 0, 'default', 'Węzeł główny');
    ```

3. Tworzymy nową klasę [aktywnego rekordu](http://www.yiiframework.com/doc-2.0/guide-db-active-record.html) na postawie tabeli z punktu 1. Istotne jest aby dziedziczyła z klasy `gilek\gtreetable\models\TreeModel`:

    ``` php
    class Tree extends \gilek\gtreetable\models\TreeModel {
    
      public static function tableName()
      {
        return 'tree';
      }
    }
    ```

4. Tworzymy nowy kontroler lub dodajemy do istniejącego akcje:

    ``` php
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
        return $this->render('gilek\gtreetable\views\widget');
      }
    }
    ```

5. W pliku konfiguracyjnym dodajemy odwołanie do folderu z tłumaczeniami:

    ``` php
    'i18n' => [
      'translations' => [
        'gtreetable' => [
          'class' => 'yii\i18n\PhpMessageSource',
          'basePath' => 'gilek\gtreetable\messages',                     
        ]
      ]
    ]
    ```  

## Konfiguracja

### Akcje

Wszystkie akcje z lokalizacji `gilek\gtreetable\actions` posiadają parametry:

  + `$treeModelName` (TreeModel) - odwołanie do modelu dziedziczącego z `gilek\gtreetable\models\TreeModel`,

  + `$access` (string) - nazwa jednostki autoryzacyjnej. Przed wykonaniem akcji możliwa jest weryfikacja czy użytkownik posiada dostęp do tej podstrony. Więcej informacji na ten temat znajdziesz w [przewodniku Yii 2.0](http://www.yiiframework.com/doc-2.0/guide-security-authorization.html#role-based-access-control-rbac).

Dodatkowo w przypadku akcji `gilek\gtreetable\actions\NodeDeleteAction` możliwe jest zdefiniowanie parametru:

  + `$dependencies` (array) - umożliwia operacje na powiązanych danych. W sytuacji, gdy model powiązany jest z innymi danymi, możliwe jest wykonanie pewnych operacji, za pośrednictwem anonimowej funkcji.
    Poniższy przykład wygeneruje błąd gdy usuwany węzeł będą miał jakieś powiązana w relacjiA:

    ``` php
    [
        'relationA' => function($relationA, $model) {
            if (count($relationA) > 0) {
                throw new HttpException('500');
            }
        }
    ]
    ```

### Model `gilek\gtreetable\models\TreeModel`
    
  + `$hasManyRoots` (boolean) - określa, czy możliwe jest tworzenie więcej niż jednego wezła głównego. Domyślnie `true`,

  + `$nameAttribute` (string) - nazwy kolumny przechowującej etykietę węzła. Domyślnie `name`,

  + `$typeAttribute` (string) - nazwy kolumny przechowującej typ węzła . Domyślnie `type`,

  + `$rootAttribute` (string) - nazwy kolumny przechowującej odwołanie od ID węzła głównego. Domyślnie `root`,

  + `$leftAttribute` (string) - nazwy kolumny przechowującej lewą wartość. Więcej informacji na ten temat można dowiedzieć się z artykułu [Managing Hierarchical Data in MySQL](http://mikehillyer.com/articles/managing-hierarchical-data-in-mysql/). Domyślnie `lft`,

  + `$rightAttribute` (string) - nazwy kolumny przechowującej prawą wartość. Domyślnie `rgt`,

  + `$levelAttribute` (string) - nazwy kolumny przechowującej poziom węzła. Domyślnie `level`.

### Widok `gilek\gtreetable\views\widget`

  + $title (string) - definiuje tytuł strony,

  + $controller (string) - nazwa kontrolera zawierającego akcje,

  + $routes (array) - w przypadku, gdy akcje ulokowane są w różnych kontrolerach, konieczne staje się ich zdefiniowanie. Dla przykładu:

    ``` php
    [
      'source' => 'controllerA/nodeChildren',
      'create' => 'controllerB/nodeCreate',
      'update' => 'controllerC/nodeUpdate',
      'delete' => 'controllerD/nodeDelete',
      'move' => 'controllerE/nodeMove'
    ]
    ```

  + $options (array) - opcje pluginu GTreeTable.


### Widżet `gilek\gtreetable\GTreeTableWidget`

  + $options (array) - opcje pluginu GTreeTable,

  + $htmlOptions (array),

  + $selector (string) - selektor jQuery wskazujący pojemnik (<table>) drzewa. Ustawienie parametru na wartość null spowoduje automatyczne wygenerowanie tabeli. Domyślnie null,

  + $columnName (string),

  + $assetBundle (AssetBundle)

## Ograniczenia

Yii2-GTreeTable korzysta z rozszerzenia [Nested Set behavior for Yii 2](https://github.com/creocoder/yii2-nested-set-behavior), które na obecną chwilę (wrzesień 2014) ma pewnie ograniczenia w związku z nadawaniem kolejności elementów. W sytuacji, gdy węzeł jest dodawany lub przesuwany w obrębie elementów głównych, wówczas zostanie mu nadana pozycja po ostatnim elemencie tego typu. W związku z czym, kolejność wyświetlanych węzłów głównych, nie zawsze ma swoje odwzorowanie w bazie danych.