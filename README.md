# acmef-todo

## Routes
```
  GET|HEAD  / .......................................................................................... 
  POST      _ignition/execute-solution ignition.executeSolution › Spatie\LaravelIgnition › ExecuteSolut…
  GET|HEAD  _ignition/health-check ignition.healthCheck › Spatie\LaravelIgnition › HealthCheckController
  POST      _ignition/update-config ignition.updateConfig › Spatie\LaravelIgnition › UpdateConfigContro…
  POST      api/auth/login ........................................................ UserController@login
  POST      api/auth/logout ...................................................... UserController@logout
  POST      api/auth/register .................................................. UserController@register
  POST      api/categories .................................................... CategoryController@store
  GET|HEAD  api/categories/list ............................................... CategoryController@index
  GET|HEAD  api/categories/{id} ................................................ CategoryController@show
  PUT       api/categories/{id} .............................................. CategoryController@update
  DELETE    api/categories/{id} ............................................. CategoryController@destroy
  POST      api/items ............................................................. ItemController@store
  GET|HEAD  api/items/list ........................................................ ItemController@index
  GET|HEAD  api/items/{id} ......................................................... ItemController@show
  PUT       api/items/{id} ....................................................... ItemController@update
  DELETE    api/items/{id} ...................................................... ItemController@destroy
  GET|HEAD  api/users/list ........................................................ UserController@index
  GET|HEAD  api/users/{id} ......................................................... UserController@show
  PUT       api/users/{id} ....................................................... UserController@update
  DELETE    api/users/{id} ...................................................... UserController@destroy
  GET|HEAD  sanctum/csrf-cookie ............................ Laravel\Sanctum › CsrfCookieController@show
```

## Usage
API use common RESP principes.

### Point /list
For listing use GET method on '/list' endpoint instead of '/'.

### Syncing categories in items and vice-versa
When you update resources and you want to "sync" relations only what you need to do is pass relation key in request body.

This snippet update the title and sync (update), add item to the provided categories
```
[
    "title":"Updated item title",
    "categories":[
        1,
        2
    ]
]
```
This snippet do nothing with relations (relations stay untouched)
```
[
    "title":"Updated item title",
]
```
This snippet do clear relations
```
[
    "title":"Updated item title",
    "categories":[]
]
```

Note: If you pass relation key with non-existing id you get exception response.

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
- vytvorit controller s api flagom (nevyrobia sa zbytocnosti)
```
php artisan make:controller NazovController --model=Nazovmodelu --api
```
- doplnit vztahy do kontrolerov a modelov, fillable, belongsTo a pod
- nahodit routes, importnut kontrolery
```
Route::middleware('guest')->prefix('auth')->group(function(){
...
Route::middleware('auth:sanctum')->group(function(){
...
    Route::prefix('users')->group(function(){

        Route::get('/list', [UserController::class, 'index' ]);
        Route::get('/{id}', [UserController::class, 'show' ]);
        Route::put('/{id}', [UserController::class, 'update' ]);
        Route::delete('/{id}', [UserController::class, 'destroy' ]);

    });
...
```
