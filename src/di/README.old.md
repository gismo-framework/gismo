# Swop Dependency Injection -- Concept draft

## Context

Ich möchte verhindern, dass sich unpreviligierte Module mit meiner Datenbank
verbinden. Außerdem soll es das `Config`-Objekt nicht aufrufen können.

Allerdings soll das `NavigationPartial` global das gleiche sein.

Dazu nutzen wird Context-Inheritance:


```
$unpriviledgedContext = new DiContext();
$unpriviledgedContext->enableAutoLoading();

$privilegedContext = $unpriviledgedContext->inherit();

$privilegedContext->addInstance(new Config("sensible Login Data"));
$privilegedContext->addFactory (ShopDatabase::class, function (Config $config)) {
    $db = new ShopDatabase($config->dbCredentials);
    $db->connect();
    return $db;    
}
```

D.h. alle Instanzen, die innerhalb des Privileged Context generiert
werden, sind auch nur dort verfügbar.


## Interfaces

Die oberhohheit liegt immer beim angeforderten Objekt. Über die folgenden
Interfaces kann das angeforderte Objekt den DI-Prozess beeinflussen

* `DiStatelessObject`: Das Objekt wird bei jeder Anforderung neu per
    Factory erzeugt.
    
* `DiUninjectableObject`: Das Objekt kann nicht direkt per Dependency Injection
    angefordert werden. Decoratoren sollten dieses Interface unbedingt
    implementieren.
    
* `DiNoAutoloadingObject`: Das Objekt kann nicht per Autoloader instantiiert
    werden
    
    
    
* `DiAwareObject`: Das Objekt implementiert Methoden, mit der sich
    der Di-Prozess steuern lässt.

* `DiRequesterAwareObject`: Das Objekt implementiert Methoden, die
    bei jeder Anforderung das Verhalten steuern können

## Factories


### Interface Factories
```
$context->addFactory (SomeInterface::class, function () {
    return new ImplementedClass();
});
```

kann so aktiviert werden.

```
function (SomeInterface $xyz) {}
```

nicht jedoch so:

```
function (ImplementedClass $xyz) {}
```

(Klar, denn die Factory muss ja nicht unbedingt die Implementierende
Klasse erzeugen). Im Bag wird die Instanz sowohl in der InterfaceTable
als auch in der ObjectTable gehalten. D.h. nachdem die Factory
einmal aufgerufen wurde, würde das Objekt eigentlich für die direkt-
Anforderung zur Verfügung stehen.

Dies wird jedoch vom Container verhindert, da dies die Unabhängigkeit
vom Aufrufzeitpunkt abhängig macht. 


Intern sieht das so aus:

```
DiContextBag {
    $instances = [
        SomeInterface => <objectInstance>,
        ImplementedClass => NamingConflictException("Class 'ImplementeClass' can be only accessed by Interface 'SomeInterface'");
    ]
}
```

### Object Factories

```
$context->addFactory (ConcreteClass::class, function () {
    return new ConcreteClass();
});
```

cannot be called by Interface:

```
function (ImplementedInterface $xyz) {}
```
=> NoInterfaceFactoryDefinedException();


```
function(SomeParentClass $obj) {}
```
=> NoFactoryDefinedException()

Will result in new Class Creation: Es kann nicht sichergestellt werden,
dass nicht eine andere Oberklasse bereits erstellt wurde, die den gleichen
Parent hält.

> Fraglich ist, ob nicht alle Parent-Klassen per ConflictException gesperrt
> werden sollten.


### Named Factories

```
$this->addFactory("§someName", function () {});
```

kann per

```
function ($§someName) {}
```

abgefragt werden. Wichtig ist das voranstellen des Paragraphen-Zeichens.


## Resolution

### Interface Maps

```
$this->addMap(SomeInterface::class, SomeImplementaion::class);
```

Damit werden Anfragen an SomeInterface von der Factory von SomeImplementation
behandelt.

> Achtung: Interface-Maps überprüfen, ob eine Interface-Factory gesetzt
> ist und schmeissen in diesem Fall eine Exeption.

```
$this->addMap(SomeInterface::class, SomeOtherImplementation::class, [RequestingObject::class]);
```

Wenn RequestinObject jetzt SomeInterface abfragt, würde dann SomeOtherImplementation
per Factory erzeugt.

Dies eignet sich perfekt fürs Logging, da hiermit für bestimmte
Objekte der Logger einfach ausgetauscht werden kann.





## Interceptors

Interceptors werden vor jedem Aufruf eines Objekts oder Interfaces
aufgerufen, können jedoch die Factory nicht ersetzen.

```
$this->addInterceptor (InterfaceOrClass::class, function ($§__interceptedObject, $§__requestingObject, $§__requestingFunction, $§__privateBag) {
});
```

Interceptors können genutzt werden, um Dekorierer anzufügen.

### Dekorierer

Angenommen ich habe folgende Klasse

```
class NavigationPartial {
    public function addButton($link, $name) {}
}
```

Ich möchte nun über ein Plugin die Funktion dieses Objekts in allen
anderen Objekten beeinflussen, so dass der Name immer groß geschrieben
wird

Dazu schreibe ich zunächst einen Dekorierer:

```
class NavigationPartialDecorator1 extends NavigationPartial {
    private $original;

    public function __construct (NavigationPartial $original) {
        $this->original = $original;
    }

    public function addButton($link, $name) {
        $this->original->addButton($link, strtoupper($name));    
    }
}
```

Und setze den Dekorierer über die Dependency Injection

```
$di->addInterceptor(NavigationPartial::class, function (NavigationPartial $partial) {
    return new NavigationPartialDecorator1($partial); 
});
```

Achtung: Dekorierer dürfen nicht über die Dependency-Injection direkt angefordert werden

```
// FEHLER:
$di->addInterceptor(NavigationPartial::class, function (NavigationPartialDecorator1 $decorator) {
    return $decorator;
})
```

Dies würde zu einer Endlosschleife führen, da der Constructor erneut den Decorierer aufrufen
würde.

Der Decorierer sollte daher das Interface `DiUninjectableObject`

> Mehrere Dekorierer: Es können beliebig viele Dekorierer ineinander
> geschachtelt werden. Es ist dann aber auf die Reihenfolge der Registrierung
> der Interceptoren zu achten.



## Gettas uns Settas per Trait

Um Code-Competion besser zu nutzen, sollte der DiContainer mit Traits 
bestückt werden:


```
/**
 * @property $request HttpRequest
 */
trait {

    public function __di_init() {
        
        $this->request = function () {
            return new HttpRequest();
        }
    }
}
```