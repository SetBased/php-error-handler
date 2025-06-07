# ErrorHandler

An error handler for PHP.

<table>
<thead>
<tr>
<th>Social</th>
<th>Legal</th>
<th>Release</th>
<th>Tests</th>
</tr>
</thead>
<tbody>
<tr>
<td>
<a href="https://gitter.im/SetBased/php-error-handler?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge"><img src="https://badges.gitter.im/SetBased/php-error-handler.svg" alt="Gitter"/></a>
</td>
<td>
<a href="https://packagist.org/packages/setbased/error-handler"><img src="https://poser.pugx.org/setbased/error-handler/license" alt="License"/></a>
</td>
<td>
<a href="https://packagist.org/packages/setbased/error-handler"><img src="https://poser.pugx.org/setbased/error-handler/v/stable" alt="Latest Stable Version"/></a><br/>
</td>
<td>
<a href="https://github.com/SetBased/php-error-handler/actions/workflows/unit.yml"><img src="https://github.com/SetBased/php-error-handler/actions/workflows/unit.yml/badge.svg" alt="Build Status"/></a><br/>
<a href="https://codecov.io/gh/SetBased/php-error-handler"><img src="https://codecov.io/gh/SetBased/php-error-handler/branch/master/graph/badge.svg" alt="Code Coverage"/></a>
</td>
</tr>
</tbody>
</table>

PHP is a great language, however is has some quirks. One of them is that PHP has two mechanisms for signaling errors: exceptions and warnings. The following code will by default not raise an exception:
```php
$handle = fopen('no-such-file.txt', 'r');
```
It will generate a waring (which is most like to be suppressed) and return false.

This library will throw exceptions in case like above instead of generating warnings.

# Manual

Add the following code at the beginning of your PHP script to throw exceptions (when possible) when a PHP warning, 
error, or notice occurs.   

```php
use SetBased\ErrorHandler\ErrorHandler;

$handler = new ErrorHandler();
$handler->registerErrorHandler(); 
```

# Installation 

This package can be installed using composer:
```sh
composer require setbased/error-handler
```

Or you can obtain the sources at [GitHub](https://github.com/SetBased/php-error-handler).


License
=======

This project is licensed under the terms of the MIT license.
