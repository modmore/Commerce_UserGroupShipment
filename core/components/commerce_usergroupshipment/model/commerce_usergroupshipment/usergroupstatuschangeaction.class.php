<?php

use MODX\Revolution\modUser;

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
class UserGroupStatusChangeAction extends comStatusChangeAction
{
    public function process(comOrder $order, comStatus $oldStatus, comStatus $newStatus, comStatusChange $statusChange): bool
    {
        $shipments = $order->getShipments();

        $userId = $order->get('user');
        if ($userId < 1) {
            return false;
        }
        /** @var modUser $user */
        $user = $this->adapter->getObject('modUser', ['id' => $userId]);
        if (!$user) {
            return false;
        }

        foreach ($shipments as $shipment) {
            if ($shipment instanceof UserGroupShipment) {
                \modmore\Commerce\UserGroupShipment\Modules\UserGroupShipment::process($user, $shipment);
            }
        }

        return true;
    }
}
