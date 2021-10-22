# php-MVC-system
php MVC  framework like laravel



# Helpers 
function route():\
The route function generates a URL for a given named route:
```php
  $url = route('route.name');
  ```
If the route accepts parameters, you may pass them as the second argument to the function:
```php
  $url = route('route.name', ['id' =>1]);
  ```
asset()\
The asset function generates a URL for an asset using the current scheme of the request (HTTP or HTTPS):
```php
  $url = asset('img/photo.jpg');
  ```
 view()\
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


