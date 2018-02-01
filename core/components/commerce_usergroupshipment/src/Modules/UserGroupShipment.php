<?php
namespace modmore\Commerce\UserGroupShipment\Modules;
use modmore\Commerce\Modules\BaseModule;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

class UserGroupShipment extends BaseModule {

    public function getName()
    {
        $this->adapter->loadLexicon('commerce_usergroupshipment:default');
        return $this->adapter->lexicon('commerce_usergroupshipment');
    }

    public function getAuthor()
    {
        return 'modmore';
    }

    public function getDescription()
    {
        return $this->adapter->lexicon('commerce_usergroupshipment.description');
    }

    public function initialize(EventDispatcher $dispatcher)
    {
        // Load our lexicon
        $this->adapter->loadLexicon('commerce_usergroupshipment:default');

        // Add the xPDO package, so Commerce can detect the derivative classes
        $root = dirname(dirname(__DIR__));
        $path = $root . '/model/';
        $this->adapter->loadPackage('commerce_usergroupshipment', $path);

        // As we have static methods, the class has to be loaded
        $this->adapter->loadClass('UserGroupShipment', $path . 'commerce_usergroupshipment/');
    }
}
