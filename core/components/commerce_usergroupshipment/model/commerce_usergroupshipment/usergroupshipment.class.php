<?php

use modmore\Commerce\Admin\Widgets\Form\CheckboxField;
use modmore\Commerce\Admin\Widgets\Form\ClassField;
use modmore\Commerce\Admin\Widgets\Form\SelectMultipleField;

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

        $fields[] = new SelectMultipleField($commerce, [
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
        $fields = [];
        $fields[] = new CheckboxField($commerce, [
            'name' => 'properties[run_ugs_automatically]',
            'label' => 'Run automatically when an order is moved to processing',
            'description' => 'When disabled, you need to edit your status workflow to add the User Group Shipment processing to the status change you want to run it in.',
            'value' => $deliveryType->getProperty('run_ugs_automatically', true),
        ]);
        return $fields;
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

        $deliveryType = $this->getDeliveryType();
        if ($deliveryType && !$deliveryType->getProperty('run_ugs_automatically', true)) {
            return false;
        }

        \modmore\Commerce\UserGroupShipment\Modules\UserGroupShipment::process($user, $this);

        return true;
    }

}
