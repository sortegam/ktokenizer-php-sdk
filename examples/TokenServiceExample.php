<?php

require 'vendor/autoload.php';

use KTokenizer\KTokenizerClient;
use KTokenizer\KTokenizerClientModes;
use KTokenizer\TokenService;

define('API_KEY', '222222');

$kTokenizerClient = new KTokenizerClient(API_KEY, KTokenizerClientModes::SANDBOX);
// Enable for local development
$kTokenizerClient->setApiHost('localhost:3000');
$kTokenizerClient->setApiIsHttps(false);
$tokenService = new TokenService($kTokenizerClient);

// -----------------------------------------------------------------------------------
// Tokenization Example
// -----------------------------------------------------------------------------------

// An string can be tokenized
// $token = $tokenService->tokenize('hello');
$importantData = [
    'pan' => '4100123412341234',
    'expirationDate' => '12/22',
    'cardHolder' => 'John Doe',
];
$result = $tokenService->tokenize($importantData);

echo 'Tokenization Result' . PHP_EOL;
print_r($result);

// -----------------------------------------------------------------------------------
// Detokenization Example
// -----------------------------------------------------------------------------------

$token = $result->token;
$result = $tokenService->detokenize($token);

echo 'Detokenization Result' . PHP_EOL;
print_r($result);

// -----------------------------------------------------------------------------------
// Validate Token Example
// -----------------------------------------------------------------------------------

$result = $tokenService->validate($token);

echo 'Validation Result' . PHP_EOL;
print_r($result);

// -----------------------------------------------------------------------------------
// Delete Token Example
// -----------------------------------------------------------------------------------

$result = $tokenService->delete($token);

echo 'Delete Token Result' . PHP_EOL;
print_r($result);
