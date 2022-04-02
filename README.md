# acmef-todo

## Basic design

### Items
	id(int)*, title(varchar200)*, owner_id(int)*, is_done(bool), created_at, updated_at

### Categories
	id*, title*, owner_id*, created_at, updated_at

### Items/Categories
	pivot

### Items/Users
	pivot



## Zadanie
#### Vytvor jednoduchú ToDo appku v Laraveli ktorá by mala obsahovať:
- Registráciu a prihlásenie (auth)
- Vytváranie ToDo itemov
- Zdieľanie ToDo itemov iným userom
- Zaradenie itemov do kategórií (môžu byť statické ale uložené v DB, aspoň 3)
- Označenie itemov ako dokončené
- Filtrovanie podľa kategórie, podľa toho či sú dokončené, podľa toho či sú moje alebo zdieľané

Predpokladá sa správne používanie Laravel frameworku - ORM, Auth wrapper, Mailer, Storage wrapper etc.

#### Spôsob implementácie: (môžeš si vybrať)
1. Statická appka (pre fullstack developera)
    - Použitie HTML + Custom (SCSS/LESS/SASS - nie čisté CSS)/Bootstrap/Material/Tailwind
	- Logicky štruktúrované views
2. REST API (pre backend developera)
	- Auth musí byť stateless podľa princípov REST
	- Dodržať princípy REST
	- Pridať pagination k zoznamu ToDo itemom

## Pozn
- vytvorit mysql, pridat creds do .env
- vytvorit basic dizajn
- vytvorit db modely param m pre vytvorenie migracie
```
php artisan make:model Nazov -m
```
- upravit migraciu podla potreby (matchnut poziadavky z dizajnu)
- proces opakovat pre vsetky modely
- vytvorit controller, parametre vygeneruju zaklandne REST fukcnie za mna, request vyrobi validaciu a vyhodi zbytocne views
```
php artisan make:controller v1\\NazovController --model=Nazovmodelu --requests --api
```
- skontroluj ci kontrolery maju spravny prefix v1 a ci je pouzity nadradeny controller
```
namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
```
- custom hack - vsetky responses do json (aj 500 a pod) aby mi postman nevracal html 404 a pod
```
	app/exception/handler.php
	 /**
     * Custom force all response to be json
     */
    public function render($request, Throwable $e)
    {
        //return $e;
        $request->headers->set('Accept', 'application/json');
        return parent::render($request, $e);
    }
```
- app/http/requests pridat zakladnu validaciu
- doplnit snippety do kontroloverov, modelov a pod
- nahodit routes s prefixom pre buduci upgrade, apiResource vygeneruje vsetky routes co treba (pozor na v1 prefix pre kontrolery)
```
use App\Http\Controllers\v1\CategoryController;
use App\Http\Controllers\v1\ItemController;

Route::prefix('v1')->group(function(){
    //
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    //
    Route::apiResource('categories',CategoryController::class);
    //
    Route::apiResource('items',ItemController::class);
});
```
- vytvorit resources (mapping pre verziovane api)
```
php artisan make:resource v1\\CategoryResource
```
