# KTokenizer PHP-SDK

> Official PHP bindings to the KTokenizer API

# SDK Documentation

## Introduction

Every request happening on our systems are going to return an operationId which represents that the operation has been
processed and logged onto our systems.

## Requirements

- A valid KTokenizer APIKey provided by our service.
- PHP >= 5.6

## Getting Stared

Import the KTokenizer PHP-SDK to your codebase.

Following you will find some examples to integrate your system with KTokenizer. Additionally you can take a look to the
examples provided on the /examples folder of this SDK.

#### Initialize the core KTokenizer SDK

Currently KTokenizerClient object supports 2 different modes:

- Sandbox Mode (To be used when integrating or doing tests) (Sandbox Mode will be used by default if none informed)
- Live Mode (To be used when going live for production)

> To enable Sandbox mode manually initialize KTokenizerClient with the following parameters:

```php
  new KTokenizerClient("YOUR_API_KEY", KTokenizerClientModes::SANDBOX);
```

> To enable Live mode (Production) initialize KTokenizerClient with the following parameters:

```php
  new KTokenizerClient("YOUR_API_KEY", KTokenizerClientModes::LIVE);
```

##### Code Snippet

```php
use KTokenizer\KTokenizerClient;
use KTokenizer\KTokenizerClientModes;
use KTokenizer\TokenService;

$kTokenizerClient = new KTokenizerClient("YOUR_API_KEY", KTokenizerClientModes::SANDBOX);
$tokenService = new TokenService($kTokenizerClient);
```

#### Generate a basic tokenization on SandBox environment ($tokenService->tokenize)

This method will tokenize sensible data into KTokenizer servers.

Tokenize method accepts any kind of data (Integers, Strings, Objects, Arrays, etc).

##### Code Snippet

```php
$importantData = [
    'pan' => '4100123412341234',
    'expirationDate' => '12/22',
    'cardHolder' => 'John Doe',
];
$tokenResult = $tokenService->tokenize($importantData);
print_r($tokenResult);

// ----------------------------------------------
// Print $tokenResult example
// ----------------------------------------------
//
// stdClass Object
//  (
//      [token] => 5af11bb4-650e-453c-aeab-28dea5059ee2
//      [operationId] => 8e600471-cee0-4342-a358-fe8477279fd8
//  )
```

#### Get the data stored by a token ($tokenService->detokenize)

This method does a request to our system to get the data stored by a given token.
A valid and existing token should be passed.

> If a bad formatted token is passed: HTTP 400 Bad Request Error will be returned.

> If the token is valid but not found in our systems: HTTP 404 error will be returned.

##### Code Snippet

```php
$result = $tokenService->detokenize("5af11bb4-650e-453c-aeab-28dea5059ee2");
print_r($result);

// ----------------------------------------------
// Print $result example
// ----------------------------------------------
//
// stdClass Object
// (
//    [data] => stdClass Object
//        (
//            [pan] => 4100123412341234
//            [expirationDate] => 12/22
//            [cardHolder] => John Doe
//        )
//
//     [operationId] => 7cb24908-7f02-4692-b798-0d451b1d33d3
// )
```

#### Validate a token ($tokenService->validate)

This method allows the developer to know whether a token is in our system or not.

> If the token is valid and found in our systems: HTTP 200 OK will be returned with the data field valid = 1.

> If a bad formatted token is passed: HTTP 400 Bad Request Error will be returned.

> If the token is valid but not found in our systems: HTTP 200 OK will be returned with the data field valid empty, null, or false.

##### Code Snippet

```php
$result = $tokenService->detokenize("5af11bb4-650e-453c-aeab-28dea5059ee2");
print_r($result);

// ----------------------------------------------
// Print $result example
// ----------------------------------------------
//
// stdClass Object
// (
//     [valid] => 1
//     [operationId] => e8066dd2-2d02-49c1-a59b-a30ac99290c0
// )
```

## Exceptions

Exceptions are handled by [Guzzle](https://github.com/guzzle/guzzle).
The KTokenizer API may return an unsuccessful HTTP response, for example when a resource is not found (404).
If you want to catch errors you can wrap your API call into a try/catch.

---

KTokenizer (c) 2018
