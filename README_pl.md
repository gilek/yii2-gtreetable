# Yii2-GTreeTable

Yii2-GTreeTable jest rozszerzeniem frameworka Yii 2, które z jednej strony stanowi opakowanie pluginu [bootstrap-gtreetable](https://github.com/gilek/bootstrap-gtreetable), z drugiej zapewnia jego obsługę od strony serwerowej.

Dzięki oprogramowaniu możliwe staje się odwzorowanie aktualnego stanu drzewa w bazie danych.

Działanie aplikacji można przetestować na [demo projektu](http://gtreetable2.gilek.net).

![](http://gilek.net/images/gtt2-demo.png)

## Instalacja

Instalacja odbywa się za pomocą menadżera [Composer](https://getcomposer.org).

W konsoli wpisz:

```
composer require gilek/yii2-gtreetable "*"
```

lub dodaj poniższą linię w sekcji require pliku `composer.json`

```
"gilek/yii2-gtreetable": "*"
```

Niestety, rozszerzenie `fxp/composer-asset-plugin` w wersji 1.0.0-beta3 zawiera błędy, które uniemożliwiają poprawną instalację [URI.js](https://github.com/medialize/URI.js). W związku z czym niezbędna jest aktualizacja:

```
composer global require fxp/composer-asset-plugin "1.0.*@dev"
```

## Minimalna konfiguracja<a name="minimalna-konfiguracja"></a>

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
  INSERT INTO `tree` (`id`, `root`, `lft`, `rgt`, `level`, `type`, `name`) VALUES (1, 1, 1, 2, 1, 'default', 'Węzeł główny');
  ```

3. Tworzymy nową klasę [aktywnego rekordu](http://www.yiiframework.com/doc-2.0/guide-db-active-record.html) na postawie tabeli z punktu 1. Istotne jest, aby dziedziczyła z klasy `gilek\gtreetable\models\TreeModel`:

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

4. Tworzymy nowy kontroler lub dodajemy do istniejącego następujące akcje:

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
      return $this->render('gilek\gtreetable\views\widget');
    }
  }
  ?>
  ```

## Konfiguracja

### Akcje

Wszystkie akcje z lokalizacji `gilek\gtreetable\actions` posiadają parametry:

  + `afterAction` (callback(`gilek\gtreetable\models\TreeModel` $model)) - funkcja wywoływania bezpośrednio po wykonaniu zadania za które odpowiada akcja np. po usunięciu węzła,
  
  + `$afterRun` (callback) - funkcja wywoływana po zakończeniu akcji,

  + `$beforeAction` (callback(`gilek\gtreetable\models\TreeModel` $model)) - funkcja wywoływania bezpośrednio przed wykonaniem zadania za które odpowiada akcja np. przed usunięciem węzła, 

  + `$beforeRun` (callback) - funkcja wywoływana przed uruchomieniem akcji. Więcej informacji w [dokumentacji klasy yii\base\Action](http://www.yiiframework.com/doc-2.0/yii-base-action.html#afterRun%28%29-detail).

    Przykład użycia, w którym sprawdzany jest dostęp do jednostki autoryzacyjnej:

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

  + `$treeModelName` (TreeModel) - odwołanie do modelu danych dziedziczącego z `gilek\gtreetable\models\TreeModel` (patrz [Minimalna konfiguracja](#minimalna-konfiguracja) punkt 1).
 
### Model 

Obsługa struktury drzewiastej w bazie danych oparta jest na modelu [Nested set model](http://en.wikipedia.org/wiki/Nested_set_model). 

Abstrakcyjna klasa `gilek\gtreetable\models\TreeModel` zapewnia obsługę w/w modelu po stronie PHP, definiuje reguły walidacyjne oraz dostarcza dodatkowe metody. Jej konfiguracji można dokonać poprzez właściwości:
    
  + `$hasManyRoots` (boolean) - określa, czy możliwe jest tworzenie więcej niż jednego węzła głównego. Domyślnie `true`,

  + `$leftAttribute` (string) - nazwy kolumny przechowującej lewą wartość.  Domyślnie `lft`,

  + `$levelAttribute` (string) - nazwy kolumny przechowującej poziom węzła. Domyślnie `level`,

  + `$nameAttribute` (string) - nazwy kolumny przechowującej etykietę węzła. Domyślnie `name`,

  + `$rightAttribute` (string) - nazwy kolumny przechowującej prawą wartość. Domyślnie `rgt`,

  + `$rootAttribute` (string) - nazwy kolumny przechowującej odwołanie od ID węzła głównego. Domyślnie `root`,

  + `$typeAttribute` (string) - nazwy kolumny przechowującej typ węzła . Domyślnie `type`.

### Widok 

Klasa widoku `gilek\gtreetable\views\widget` zawiera gotową konfigurację [operacji CUD](https://github.com/gilek/bootstrap-gtreetable#cud) wraz z odwołaniem do [źródła węzłów](https://github.com/gilek/bootstrap-gtreetable#source). Nie ma konieczności, aby z niej korzystać, jednak może okazać się bardzo pomocna w przypadku prostych projektów. 
Całość można dostosować do swoich potrzeb poprzez parametry:

  + `$controller` (string) - nazwa kontrolera, w którym zdefiniowano akcje (patrz [Minimalna konfiguracja](#minimalna-konfiguracja) punkt 4). Domyślnie przyjmowana jest nazwa kontrolera w którym nastąpiło wywołanie widoku `gilek\gtreetable\views\widget`,

  + `$options` (array) - opcje przekazywane bezpośrednio do pluginu bootstrap-gtreetable,

  + `$routes` (array) - w przypadku, gdy poszczególne akcje ulokowane są w różnych kontrolerach lub ich nazwy są odmienne w stosunku do przedstawionych w punkcie 4 rozdziału [minimalna konfiguracja](#minimalna-konfiguracja), wówczas konieczne staje się ich zdefiniowanie. 

  Wymaganą strukturę danych, najlepiej obrazuje poniższy przykład:

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

  + `$title` (string) - definiuje tytuł strony, gdy widok jest wywoływany bezpośrednio z poziomu akcji (patrz [Minimalna konfiguracja](#minimalna-konfiguracja) punkt 4).

### Widżet 

Głównym zadaniem widżetu `gilek\gtreetable\widget` jest wygenerowanie parametrów konfiguracyjnych pluginu bootstrap-gtreetable oraz dołączenie wymaganych plików. W przypadku braku kontenera, odpowiada on również za jego stworzenie. Klasa posiada następujące właściwości:

  + `$assetBundle` (AssetBundle) - parametr umożliwia nadpisane domyślnego pakietu `AssetBundle` tj. `Asset`,

  + `$columnName` (string) - nazwa kolumny tabeli. Domyślna wartość `Name` pobierana jest z pliku tłumaczeń,

  + `$htmlOptions` (array) - opcje HTML kontenera, renderowane w momencie jego tworzenia (paramert `$selector` ustawiony na `null`),

  + `$options` (array) - opcje przekazywane bezpośrednio do pluginu bootstrap-gtreetable,

  + `$selector` (string) - selektor jQuery wskazujący kontener drzewa (tag `<table>`). Ustawienie parametru na wartość `null` spowoduje automatyczne wygenerowanie tabeli. Domyślnie `null`.

## Ograniczenia

Yii2-GTreeTable korzysta z rozszerzenia [Nested Set behavior for Yii 2](https://github.com/creocoder/yii2-nested-set-behavior), które na obecną chwilę (październik 2014) ma pewnie ograniczenia odnośnie kolejności elementów głównych (węzły, których poziom = 1). 

W przypadku dodania lub przesunięcia węzła jako element główny, wówczas zostanie on zawsze ulokowany, po ostatnim elemencie tego stopnia. W związku z czym, kolejność wyświetlanych węzłów głównych, nie zawsze ma swoje odwzorowanie w bazie danych.
