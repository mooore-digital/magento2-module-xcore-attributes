# xCore Attributes

Magento 2 module for providing specific [xCore](https://dealer4dealer.nl/magento-2-exact-online-koppeling/) attributes in the REST API to sync data back from Magento to [Exact Online](https://www.exact.com/software/exact-online).

For customers, it exposes the following extension attributes:
- xcore_price_list (PriceList GUID).
- xcore_vat_code (Selected vat class, either 'excluding' or 'including').

## Installation
```shell script
composer require mooore/magento2-module-xcore-attributes
bin/magento setup:upgrade
```
