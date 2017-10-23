# Interfaces

eXtalion interfaces

## Install
```bash
composer install extalion/interfaces
```

### FrontController
Interface to dispatch controller from [Route](https://github.com/eXtalionTeam/routing).

If you don't have any special needs during dispaching controller you can use example [trait](src/Traits/FrontController.php)
```php
use eXtalion\Component\Interfaces\FrontController;
use eXtalion\Component\Interfaces\Traits;

class MyFronController implements FrontController {
    use Traits\FrontController;
}

$frontController = new MyFrontController();
$response = $frontController->dispatch($route, $request);
```
