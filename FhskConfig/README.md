FhSiteKit Config Module
===

Usage
------------------------------


### 1.  A module registers its FhskConfig variables in its `onBootstrap()` callback

```php
namespace MyRegistrationModule;

class Module extends AbstractModule
{
    const FHSK_CONFIG_LOCK_DELAY = 'Registration: delay before locking';

    public function onBootstrap(MvcEvent $e)
    {
        $config = $e->getApplication()->getServiceManager()->get('FhskConfigRegistry');
        $config::registerKey(self::FHSK_CONFIG_LOCK_DELAY);
    }
```

The **FhskConfigRegistry** service is provided by the FhskConfig module:

```php
namespace FhSiteKit\FhskConfig;

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


### 2.  Configuration of the corresponding key is done in the site admin online

The `$key` passed to `$config::registerKey()`, 'Registration: delay before locking', 
is what will appear as the variable's label in the online interface


### 3.  The FhSiteKit `BaseController` dispatches a `EVENT_COLLECT_VIEW_DATA` event before it generates the ViewModel

```php
namespace FhSiteKit\FhskCore\FhskSite\Controller;

class BaseController extends AbstractActionController
{
    const EVENT_COLLECT_VIEW_DATA = "BaseController.collectViewData";
    
 
    protected function generateViewModel($action = null)
    {
        $this->addRouteInfoToViewData();
        $this->addFlashMessagesToViewData();

        $this->triggerCollectViewDataEvent();
//  ...
    }
    
    protected function triggerCollectViewDataEvent()
    {
        $this->getEventManager()->trigger(self::EVENT_COLLECT_VIEW_DATA, $this);
    }
```


### 4.  The FhskConfig module listens for the `BaseController::EVENT_COLLECT_VIEW_DATA` event 

It fetches data via the **FhskConfig** service which it provides to `$target->addViewData()`

```php
namespace FhSiteKit\FhskConfig;

class Module extends AbstractModule
{
    public function onBootstrap(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $eventManager = $e->getApplication()->getEventManager()->getSharedManager();
        $eventManager->attach(
            '*',
            BaseController::EVENT_COLLECT_VIEW_DATA,
            function($e) use($sm) {
                //  we need to pass in the $sm, and call it now, rather than passing in the FhskConfig service
                //    because the service might have changed (UT mocking...) since bootstrap time
                $data = $sm->get('FhskConfig')->getConfigArray();
                $configViewData = array(
                    'FhskConfig' => array(
                        'data'      => $data,
//  ...
                    ),
                );
                $target = $e->getTarget();
                $target->addViewData($configViewData);
            }
        );
    }
```

### 5.  The FhskConfig data is made available to the ViewModel as `$this->FhskConfig['data']`

Obviously, any object that can access the application service locator can call `$sm->get('FhskConfig')->getConfigArray()` directly, 
and do what it wants to with the data, which will be returned as an array of `CONST => $value` pairs.

It is done with an event trigger in `BaseController` so that this core class and its children 
can be agnostic as to whether they are being used in conjunction with the FhskConfig module or not.


Incorporation in unit testing
------------------------------

###  The MockConfig module test double

In the example `MyRegistrationModule` in §1 above, unit tests on the module will fail unless it has access to the 
`FhskConfigRegistry` and `FhskConfig` services defined by the FhskConfig module.

To avoid having to mock up these services, possibly including also calls to `ConfigTable`, 
a MockConfig module test double is provided that can be used simply by including it in your
test suite's `application.config.php`.

```php
//  MyRegistrationModule/test/resources/config/application.config.php

return array(
    'modules' => array(
//  ...
        'MockConfig',
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            'MockConfig' => './module/FhskConfig/test/resources/module/MockConfig',
//  ...
        ),
```

