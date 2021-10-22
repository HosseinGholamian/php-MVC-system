# php-MVC-system
php MVC  framework like laravel but simpler

# Folders 
#### The assets of template must be located at 
```
public\
```

#### The controller of website must be located at:
```
App\Http\Controllers
```

#### The Models Must be located at :
```
app\
```

#### Your template theme must be located at :
```
recources\view\
```

#### The database configueation located at :
```
config\database.php
```
change the values base on your database setting:
```php
return [

    'DBHOST'     => 'localhost',
    'DBPASSWORD' => '',
    'DBUSERNAME' => 'root',
    'DBNAME'     => 'dbname'

];
```

### Blade Template
Blade is a simple, yet powerful templating engine provided with Laravel. Unlike controller layouts, Blade is driven by template inheritance and sections. All Blade templates should use the .blade.php extension.

Defining A Blade Layout
<!-- Stored in resources/views/layouts/master.blade.php -->
```php
<html>
    <head>
        <title>App Name - @yield('title')</title>
    </head>
    <body>
        @section('sidebar')
            This is the master sidebar.
        @endsection

        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
```

```php
@extends('layouts.master')

@section('sidebar')   
    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <p>This is my body content.</p>
@endsection
```


# Routing System

You can register the routes in :  routes / web.php
```php
Route::get("url", "controller@method", "name");
```
For Example :
```php
Route::get("", "HomeController@index", "index");
Route::get("create", "HomeController@create", "create");
Route::post("store", "HomeController@store", "store");
Route::get("edit/{id}", "HomeController@edit", "edit");
Route::put("/update/{id}", "HomeController@update", "update");
Route::delete("/delete/{id}", "HomeController@destroy", "delete");
```


# Helpers 
### route():\
The route function generates a URL for a given named route:
```php
  $url = route('route.name');
  ```
If the route accepts parameters, you may pass them as the second argument to the function:
```php
  $url = route('route.name', ['id' =>1]);
  ```
### asset()\
The asset function generates a URL for an asset using the current scheme of the request (HTTP or HTTPS):
```php
  $url = asset('img/photo.jpg');
  ```
 ### view()\
The view function load a view instance that exists in resources/view:
```php
return view('auth.login');
```
# Auth 
  \System\Auth\Auth::user()\
retrun currently authenticated user
```php
    \System\Auth\Auth::user()->first_name;
```

### Auth::check()\
To determine if the user making the incoming HTTP request is authenticated, you may use the check method on the Auth facade. This method will return true if the user is authenticated:
```php
use App\Http\Controllers\Controller;

if (Auth::check()) {
    // The user is logged in...
}
```


