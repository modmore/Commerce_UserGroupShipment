<?php
namespace modmore\Commerce\UserGroupShipment\Modules;
use modmore\Commerce\Admin\Widgets\Form\CheckboxField;
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

    public static function process(\modUser $user, \UserGroupShipment $shipment)
    {
        $groups = [];
        foreach ($shipment->getItems() as $item) {
            if ($product = $item->getProduct()) {
                $value = $product->getProperty('usergroup');
                // it might or might not be an array, due to this: https://github.com/modmore/Commerce/issues/425
                // let's make sure it's definitely an array.
                if(!is_array($value)) {
                    $value = explode(',', $value);
                }
                // remove blank
                $value = array_filter($value);
                $groups = array_merge($groups, $value);
            }
        }
        $groups = array_map('intval', $groups);
        $groups = array_unique($groups);

        foreach ($groups as $group) {
            $user->joinGroup($group);
        }

        // get the current context from the order
        $order = $shipment->getOrder();
        $ctx = $order ? $order->get('context') : 'web';

        // refresh the users' attributes (permissions)
        $user->getAttributes([], $ctx, true);
    }
}
