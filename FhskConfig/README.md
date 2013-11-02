FhSiteKit Config Module
===

Usage
------------------------------

### 1.  A module registers its FhskConfig variables in its onBootstrap() callback

```diff
class Module extends AbstractModule
{
+    const FHSK_CONFIG_LOCK_DELAY = 'Registration: delay before locking';

    public function onBootstrap(MvcEvent $e)
    {
+        $config = $e->getApplication()->getServiceManager()->get('FhskConfigRegistry');
+        $config::registerKey(self::FHSK_CONFIG_LOCK_DELAY);
    }
```

The *FhskConfigRegistry* service is provided by the FhskConfig module:

```php
class Module extends AbstractModule
{
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'FhskConfigRegistry' => 'FhSiteKit\FhskConfig\Service\Config',
            ),
//  ...
        );
    }
```

