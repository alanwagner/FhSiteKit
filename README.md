Farther Horizon Site Kit
===

Installation
------------------------------

### 1.  Get the ZF2 Skeleton Application working
http://framework.zend.com/manual/2.2/en/user-guide/skeleton-application.html

### 2.  Replace your `module/` directory with a clone of FHSK
```bash
rm -Rf module
git clone https://github.com/alanwagner/FHSK.git module
```

### 3.  Install databases
```bash
mysql -uroot -e"create database ndg_ngame; create database ndg_igame"
mysql -uroot ndg_ngame < NgameSite/data/ngame_schema.sql
mysql -uroot ndg_ngame < NgameSite/data/ngame_fixture.sql
mysql -uroot ndg_igame < IgameSite/data/igame_schema.sql
mysql -uroot ndg_igame < IgameSite/data/igame_fixture.sql
```

### 4.  Modify index.php

This will make methods of `FhskSite\Core\Site` available via the autoloader even before the modules have been processed.

```diff
// Setup autoloading
require 'init_autoloader.php';

+ $loader->add('FhskSite', 'module/Fhsk/src');

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
```

### 5.  Modify application.config.php

For in-depth understanding of this step and the next, see ZF2 Advanced Configuration Tricks:

http://framework.zend.com/manual/2.2/en/tutorials/config.advanced.html

```diff
<?php

+ $siteKey = FhskSite\Core\Site::getKey();
+ 
+ $modules = array(
+     'Application',
+     'Fhsk',
+     'NdgPattern',
+ );
+ 
+ switch ($siteKey) {
+     case 'ngame' :
+         $modules[] = 'NgameSite';
+         break;
+     case 'igame' :
+         $modules[] = 'IgameSite';
+         break;
+ }
+ 
return array(
    // This should be an array of module namespaces used in the application.
-    'modules' => array('Application'),
+    'modules' => $modules,

    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => array(
```
```diff
        'config_glob_paths' => array(
-            'config/autoload/{,*.}{global,local}.php',
+            sprintf('config/autoload/{,*.}{global,%s,local}.php', $siteKey),
        ),
```

### 6.  Install site-specific application configs

The dist files contain the database configs

```bash
cp NgameSite/config/ngame.php.dist config/autoload/ngame.php
cp IgameSite/config/igame.php.dist config/autoload/igame.php
```

You can also clean up `config/autoload/global.php` and `local.php`
```diff
    'db' => array(
        'driver'         => 'Pdo',
-        'dsn'            => 'mysql:dbname=zf2tutorial;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
```
```diff
return array(
-    'db' => array(
-        'username' => 'YOUR USERNAME HERE',
-        'password' => 'YOUR PASSWORD HERE',
-    ),
);
```
Testing
-------------------------

http://fhsk.local/ngame/admin/pattern

http://fhsk.local/igame/admin/pattern
