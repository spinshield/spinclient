# Spinclient
To assist easier integration for operators using PHP. Works on any PHP > 5, requires GuzzleHttp.

You can use this package in your existing PHP based projects. This includes any PHP based framework, like Laravel, Lumen, Yiii and so on.

# Install
Run in your PHP project:
`composer require spinshield/spinclient`

## Function Overview

### API Client Functions
```php
// e.g. getGame("player123", "playerPass123", "platipus/egyptiangold", "USD", "https://casino.com", "https://casino.com/deposit", 0, "en");
getGame($username, $userpassword, $game_id, $currency, $homeurl, $cashierurl, $play_for_fun, $lang);

// e.g. getGameDemo("platipus/egyptiangold", "USD", "https://casino.com", "https://casino.com/deposit", "en");
getGameDemo($game_id, $currency, $homeurl, $cashierurl, $lang);

// e.g. getGameList("USD", 1);
getGameList($currency, $list_type);

// e.g. createPlayer("player123", "playerPass123", "Malone", "USD");
createPlayer($username, $userpassword, $usernickname, $currency);

// e.g. addFreeRounds("player123", "playerPass123", "platipus/egyptiangold", "USD", 10, 0);
addFreeRounds($username, $userpassword, $game_id, $currency, $freespins, $betlevel); 

// e.g. getFreeRounds("player123", "playerPass123", "USD");
getFreeRounds($username, $userpassword, $currency);

// e.g. deleteFreeRounds("platipus/egyptiangold", "player123", "playerPass123", "USD");
deleteFreeRounds($gameid, $username, $userpassword, $currency);

// e.g. deleteFreeRounds("player123", "playerPass123", "USD");
deleteAllFreeRounds($username, $userpassword, $currency);

```

### Helpers Functions
This package also includes helpers to assist you in for example returning responses on callbacks. See examples below how to implement.

Helper functions to assist you on callbacks:
```php
// validate the callback request coming from our gameserver, take 'key', 'timestamp' from each callback and salt from your apikey configuration in backoffice
isValidKey($key, $timestamp, $salt);

// construct balance response in JSON format
balanceResponse($intBalance);

// construct insufficient balance in JSON format
insufficientBalance($intBalance);

//construct generic error reponse (when you have error processing callback) in JSON format
processingError(); 
```

Generic Helper functions:
```php
// check for error code on api responses
responseHasError($apiResponse);

// morphs json object to associative array
morphJsonToArray($input);

// because our API communicates using int value in cents, this can assist you to convert for example ($) 2.00 to 200 securely
floatToIntHelper($floatValue); 

// converts int back to float (2 decimals)
intToFloatHelper($intValue); 
```

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

var_export($client->getGameList("USD", 1));
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

class SpinController
{
  function __construct()
  {
    $this->client = new spinclient\ApiClient(array(
      "endpoint" => "https://secretendpoint.net",
      "api_login" => "12345",
      "api_password" => "12345",
    ));

    $this->helpers = new spinclient\Helpers();
  }
  
  public function gamelist() {
    return $this->client->getGameList("USD", 1);
  }

  public function gameflow() {
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





