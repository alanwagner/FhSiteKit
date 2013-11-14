Farther Horizon Site Kit
===

Installation
------------------------------

### 1.  Install *FhSiteKit* as a submodule
```bash
git submodule add https://github.com/alanwagner/FhSiteKit.git  vendor/FhSiteKit
```

### 2.  Modify `index.php`

This will make methods of `FhSiteKit\FhskCore\FhskSite\Core\Site` available via the autoloader even before the modules have been processed.

```diff
// Setup autoloading
require 'init_autoloader.php';

+ $loader->add('FhSiteKit\FhskCore', 'vendor/FhSiteKit/FhskCore/src');

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
```

### 3.  Modify `application.config.php`

For in-depth understanding of this step, see ZF2 Advanced Configuration Tricks:

http://framework.zend.com/manual/2.2/en/tutorials/config.advanced.html

```diff
<?php

+ $siteKey = FhSiteKit\FhskCore\FhskSite\Core\Site::getKey();
+ 
+ $modules = array(
+     'Application',
+     'FhSiteKit\FhskCore',
+     'FhSiteKit\FhskConfig',
+     'Ndg\NdgSite',
+     'Ndg\NdgPattern',
+     'Ndg\NdgTemplate',
+     'Ndg\NdgNetwork',
+ );
+ 
+ switch ($siteKey) {
+     case 'pennshape' :
+         $modules[] = 'PennShape\PennShapeSite';
+         break;
+ }
+ 
return array(
    // This should be an array of module namespaces used in the application.
-    'modules' => array('Application'),
+    'modules' => $modules,

    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => array(
        // ...
        'config_glob_paths' => array(
-            'config/autoload/{,*.}{global,local}.php',
+            sprintf('config/autoload/{,*.}{global,%s,local}.php', $siteKey),
        ),
```

### 4.  Create links to web assets
```bash
cd public/css
ln -s ../../vendor/FhSiteKit/*/resources/public/css/*

cd public/js
ln -s ../../vendor/FhSiteKit/*/resources/public/js/*

cd public/img
ln -s ../../vendor/FhSiteKit/*/resources/public/img/*
```

Testing
-------------------------

### Unit Tests

```bash
./bin/phpunit.sh
```

Add phpunit to `composer.json` and run `php composer.phar update` if you need to install phpunit into your project.

```diff
    "require": {
        "php": ">=5.3.3",
-        "zendframework/zendframework": "2.2.*"
+        "zendframework/zendframework": "2.2.*",
+        "phpunit/phpunit": "3.7.*"
    }
}
```

API Doc
-------------------------

http://FhSiteKit.com/apigen
