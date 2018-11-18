# TicTacToe

## Requires

```
PHP 7.2 or 7.3RC
```

## Installing

- Setup a virtual host (tic-tac-toe.test) (on NGINX or Apache or whatever you use to host your pages)
- Point it to public/index.php
- Open the page: http://tic-tac-toe.test

## Demo

It's also available [online at https://tictactoe.antoniocarlosribeiro.com](https://tictactoe.antoniocarlosribeiro.com/)

## Testing

``` bash
composer test

or 

vendor/bin/phpunit
```

## Static Analysis

``` bash
composer stan

or 

vendor/bin/phpstan analyse --level=7 app tests
```
