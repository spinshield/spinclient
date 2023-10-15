# Spinshield API
To assist easier integration for operators using PHP. Works on any PHP > 5, requires GuzzleHttp.

## Install
You can use this package in your existing PHP based projects. 

This includes any PHP based framework, like Laravel, Lumen, Yiii and so on.

Run:
`composer require spinshield/spinclient`

## API Client
This package includes API client to communicate with our api gameserver more easily. See examples below how to implement.

API Client functions:
```php
getGame($username, $userpassword, $game_id, $currency, $homeurl, $cashierurl,$play_for_fun, $lang); // returns game URL, e.g. openGame("player123", "playerPass123", "platipus/egyptiangold", "USD", "https://casino.com", "https://casino.com/deposit", 0, "en");
getGameList($currency, $list_type); // e.g. getGameList("USD", 1);
createPlayer($username, $userpassword, $usernickname, $currency); // e.g. createPlayer("player123", "playerPass123", "Malone", "USD");
addFreeRounds($username, $userpassword, $game_id, $currency, $freespins, $betlevel); // e.g. addFreeRounds("player123", "playerPass123", "platipus/egyptiangold", "USD", 10, 0);
getFreeRounds($username, $userpassword, $currency); // e.g. getFreeRounds("player123", "playerPass123", "USD");
```

## Helpers
This package also includes helpers to assist you in for example returning responses on callbacks. 

Helper functions to assist you on callbacks:
```php
isValidKey($key, $timestamp, $salt); // validate the callback request coming from our gameserver, take 'key', 'timestamp' from each callback and salt from your apikey configuration in backoffice
balanceResponse($intBalance); // construct balance response in JSON format
insufficientBalance($intBalance); // construct insufficient balance in JSON format
processingError(); //construct generic error reponse (when you have error processing callback) in JSON format
```

Generic Helper functions:
```php
responseHasError($apiResponse); // check for error code on api responses
morphJsonToArray($input); // morphs json to array
floatToIntHelper($floatValue); // because our API communicates using int value in cents, this can assist you to convert for example ($) 2.00 to 200 securely
intToFloatHelper($intValue); // converts int back to float (2 decimals)
```

See examples below how to implement.
## Examples
### PHP Usage Example
```php
<?php
use spinshield/spinclient;

require __DIR__.'/vendor/autoload.php';

$client = new spinclient\ApiClient(array(
    "endpoint" => "https://secretendpoint.com",
    "api_login" => "12345",
    "api_password" => "12345",
));

var_export($client->getGamesList());
```

### Laravel Usage Example
routes/web.php:
```php
Route::get('/spinshield_example/gamelist', [\App\Http\Controllers\SpinController::class, 'gamelist']);
Route::get('/spinshield_example/gameflow', [\App\Http\Controllers\SpinController::class, 'gameflow']);

```

app/Http/Controllers/SpinController.php
```php
<?php
namespace App\Http\Controllers;
use spinshield\spinclient;

class SpinController extends \App\Http\Controllers\Controller
{
  function __construct()
  {
    $this->api_client = new spinclient\ApiClient(array(
      "endpoint" => "https://secretendpoint.net",
      "api_login" => "12345",
      "api_password" => "12345",
    ));

    $this->helpers = new spinclient\Helpers();
  }
  
  public function gamelist() {
    return $this->client->getGameList("USD", 1);
  }

  public function exampleGameFlow() {
    $createPlayer = $this->client->createPlayer("playerId1337", "playerPassword", "Tiernan", "USD");
    if($this->helpers->responseHasError($createPlayer)) {
      return $createPlayer;
    } else {
      $getGame = $this->client->getGame("playerId1337", "playerPassword", "platipus/egyptiangold", "USD", "https://casino.com", "https://casino.com/deposit", 0, "en");
      return $getGame;
    }
  }
}
```





