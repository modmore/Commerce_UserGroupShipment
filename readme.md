UserGroupShipment 
---

Custom shipment type for [Commerce](https://www.modmore.com/commerce/) (v0.11+) that allows automatic "delivery" of User Group access. Useful for granting access to paid access of a site.

General instructions:

- Set up a Delivery Type that has the shipment type set to "Assign User to User Group".
- Add a product, choose the Delivery Type you created. Save the product.
- Choose the User Group to assign to the product.

Now, when a customer orders this product, the shipment type will automatically add the user to the specified User Group. (Note: the user may need to login/logout to see new permissions)

## Installation

Download & install from the modmore package provider.

Looking to contribute? Clone a fork of the repository, and run `php _bootstrap/index.php` to get set up quickly. 
