# php-MVC-system
php MVC  framework like laravel

function route():\
The route function generates a URL for a given named route:
```php
  $url = route('route.name');
  ```
If the route accepts parameters, you may pass them as the second argument to the function:
```php
  $url = route('route.name', ['id' =>1]);
  ```
