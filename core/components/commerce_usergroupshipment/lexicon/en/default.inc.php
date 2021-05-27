<?php

$_lang['commerce_usergroupshipment'] = 'UserGroupShipment';
$_lang['commerce_usergroupshipment.description'] = 'Enables a new Shipment type, UserGroupShipment, which will automatically assign a user to a per-product specific user group. To use, first add the shipment type to a delivery type, and then select the usergroup on a product that uses the assigned delivery type.';
$_lang['commerce.UserGroupShipment'] = 'Assign User to User Group';

$_lang['commerce.UserGroupStatusChangeAction'] = 'Assign User Groups to purchaser';
$_lang['commerce.add_UserGroupStatusChangeAction'] = 'Assign User Groups to purchaser';
$_lang['commerce_usergroupshipment.user_group'] = 'User Group';
$_lang['commerce_usergroupshipment.user_group.description'] = 'The user group(s) to grant customers access to when they\'ve bought this product.';
$_lang['commerce_usergroupshipment.run_ugs_automatically'] = 'Run automatically when an order is moved to processing';
$_lang['commerce_usergroupshipment.run_ugs_automatically.description'] = 'When unchecked, you need to edit your status workflow to add the "Assign User Groups to purchaser" action to the status change you want to run it in.';