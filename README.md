PHP Magic Quotes Implementation
===============================

Implement magic_quotes_gpc on PHP 5.4 later version for legacy code

[![Latest Stable Version](https://poser.pugx.org/yidas/magic-quotes/v/stable?format=flat-square)](https://packagist.org/packages/yidas/magic-quotes)
[![Latest Unstable Version](https://poser.pugx.org/yidas/magic-quotes/v/unstable?format=flat-square)](https://packagist.org/packages/yidas/magic-quotes)
[![License](https://poser.pugx.org/yidas/magic-quotes/license?format=flat-square)](https://packagist.org/packages/yidas/magic-quotes)

If you are migrating legacy source code to the enviorment with PHP version 5.4 above, but including lots of vulnerable DB query codes depending on Magic Quotes `magic_quotes_gpc` SQL protection. Just use this to run smoothly on new version PHP like old time.

As PHP's Warning for [Magic Quotes](http://php.net/manual/en/security.magicquotes.php):

> Magic Quotes feature has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.

---

DEMONSTRATION
-------------

```php
print_r($_GET);
MagicQuotesGpc::init();
print_r($_GET);
```
    
After visiting URL with query `?username=1' OR '1'='1`, and the output will be: 

    Array ( [username] => 1' OR '1'='1 ) 
    Array ( [username] => 1\' OR \'1\'=\'1 )
    
    
### Recursive Input Data Concern

The recursive data input from `$_POST`, `$_COOKIE` even `$_GET` will be handled also:

```php
$_POST['users'][0] = ['username'=>"1' OR '1'='1"];
print_r($_POST);
MagicQuotesGpc::init();
print_r($_POST);
```

After simulating `$_POST` data assignment, the output will be: 

    Array ( [users] => Array ( [0] => Array ( [username] => 1' OR '1'='1 ) ) ) 
    Array ( [users] => Array ( [0] => Array ( [username] => 1\' OR \'1\'=\'1 ) ) )

---

INSTALLATION
------------

### Install via Composer

Run Composer in your legacy project:

    composer require yidas/magic-quotes
    
Then initialize it at the bootstrap of application such as `config` file:

```php
require __DIR__ . '/vendor/autoload.php';
MagicQuotesGpc::init();
```

### Install Directly by Loading Class

Load the `MagicQuotesGpc.php` and initialize it:

```php
require __DIR__ . '/MagicQuotesGpc.php';
MagicQuotesGpc::init();
```

