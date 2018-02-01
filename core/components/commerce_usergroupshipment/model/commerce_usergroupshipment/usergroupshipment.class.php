<?php
use modmore\Commerce\Admin\Widgets\Form\ClassField;
use modmore\Commerce\Admin\Widgets\Form\SelectField;

/**
 * UserGroupShipment for Commerce.
 *
 * Copyright 2018 by Mark Hamstra <mark@modmore.com>
 *
 * This file is meant to be used with Commerce by modmore. A valid Commerce license is required.
 *
 * @package commerce_usergroupshipment
 * @license See core/components/commerce_usergroupshipment/docs/license.txt
 */
class UserGroupShipment extends comOrderShipment
{
    public static function getFieldsForProduct(Commerce $commerce, comProduct $product, comDeliveryType $deliveryType)
    {
        $fields = [];

        $fields[] = new SelectField($commerce, [
            'name' => 'properties[usergroup]',
            'label' => 'User Group',
            'description' => 'The user group to grant customers access to when they\'ve bought this product.',
            'value' => $product->getProperty('usergroup'),
            'optionsClass' => 'modUserGroup',
        ]);

        return $fields;
    }

    public static function getFieldsForDeliveryType(Commerce $commerce, comDeliveryType $deliveryType)
    {
        return [];
    }

    /**
     * @return bool
     */
    public function onOrderStateProcessing()
    {
        if (!$order = $this->getOrder()) {
            return false;
        }
        $userId = $order->get('user');
        if ($userId < 1) {
            return false;
        }
        /** @var modUser $user */
        $user = $this->adapter->getObject('modUser', ['id' => $userId]);
        if (!$user) {
            return false;
        }

        $groups = [];
        foreach ($this->getItems() as $item) {
            if ($product = $item->getProduct()) {
                $groups[] = $product->getProperty('usergroup');
            }
        }
        $groups = array_map('intval', $groups);
        $groups = array_unique($groups);

        foreach ($groups as $group) {
            $user->joinGroup($group);
        }
        return true;
    }

}
