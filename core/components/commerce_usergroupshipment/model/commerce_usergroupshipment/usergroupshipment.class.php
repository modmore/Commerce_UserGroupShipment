<?php

use modmore\Commerce\Admin\Widgets\Form\CheckboxField;
use modmore\Commerce\Admin\Widgets\Form\SelectMultipleField;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;

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
    public static function getFieldsForProduct(Commerce $commerce, comProduct $product, comDeliveryType $deliveryType): array
    {
        $fields = [];

        $fields[] = new SelectMultipleField($commerce, [
            'name' => 'properties[usergroup]',
            'label' => $commerce->adapter->lexicon('commerce_usergroupshipment.user_group'),
            'description' => $commerce->adapter->lexicon('commerce_usergroupshipment.user_group.description'),
            'value' => $product->getProperty('usergroup'),
            'optionsClass' => class_exists(modUserGroup::class) ? modUserGroup::class : \modUserGroup::class
        ]);

        return $fields;
    }

    public static function getFieldsForDeliveryType(Commerce $commerce, comDeliveryType $deliveryType): array
    {
        $fields = [];
        $fields[] = new CheckboxField($commerce, [
            'name' => 'properties[run_ugs_automatically]',
            'label' => $commerce->adapter->lexicon('commerce_usergroupshipment.run_ugs_automatically'),
            'description' => $commerce->adapter->lexicon('commerce_usergroupshipment.run_ugs_automatically.description'),
            'value' => $deliveryType->getProperty('run_ugs_automatically', true),
        ]);
        return $fields;
    }

    /**
     * @return bool
     */
    public function onOrderStateProcessing(): bool
    {
        if (!$order = $this->getOrder()) {
            return false;
        }
        $userId = $order->get('user');
        if ($userId < 1) {
            return false;
        }
        
        $modUserClass = class_exists(modUser::class) ? modUser::class : \modUser::class;
        $user = $this->adapter->getObject($modUserClass, ['id' => $userId]);
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
