<p align="center"><a href="https://sylius.com/plugins/" target="_blank"><img src="https://sylius.com/assets/badge-approved-by-sylius.png" width="100"></a></p>

# Sylius Tier Price Plugin
[![Build Status](https://travis-ci.org/Brille24/SyliusTierpricePlugin.svg?branch=master)](https://travis-ci.org/Brille24/SyliusTierpricePlugin)

This plugin adds tier pricing to Sylius one product has different prices based on the quantity.

## Installation
* Install the bundle via composer `composer require brille24/sylius-tierprice-plugin`
* Register the bundle in your `bundles.php`:
```php
return [
    //...

    Brille24\SyliusTierPricePlugin\Brille24SyliusTierPricePlugin::class => ['all' => true],
];
```

* Add the `config.yml` to your local `config/config.yml`
```yml
imports:
    ...
    - { resource: '@Brille24SyliusTierPricePlugin/config/config.yml'}
```

* For API functionality add the bundle's `routing.yml` to the local `app/config/routing.yml`
```yml
...
brille24_tierprice_bundle:
    resource: '@Brille24SyliusTierPricePlugin/config/routing.yml'
```

* Go into your ProductVariant class and add the following trait and add one method call to the constructor
```php
class ProductVariant extends BaseProductVariant implements \Brille24\SyliusTierPricePlugin\Entity\ProductVariantInterface
{
    use \Brille24\SyliusTierPricePlugin\Traits\TierPriceableTrait;

    public function __construct() {
        parent::__construct(); // Your contructor here

        $this->initTierPriceableTrait(); // "Constructor" of the trait
    }

    protected function createTranslation(): ProductVariantTranslationInterface
    {
        return new ProductVariantTranslation();
    }
}
````

* Finally update the database, install the assets and update the translations:
```sh
bin/console doctrine:schema:update --force
bin/console assets:install
bin/console translation:update <locale> --force
```

### Integration
* This bundle decorates the `sylius.calculator.product_variant_price` service. If you wish to change that, you could register a [compiler pass](https://symfony.com/doc/current/service_container/compiler_passes.html).
* This bundle decorates the `sylius.order_processing.order_prices_recalculator` service. If you wish to use your own order processor or change its priority, you could register a [compiler pass](https://symfony.com/doc/current/service_container/compiler_passes.html).

## Usage
First of all you have to set up a product with as many variants as you want. Then in each of these variants you can set the tier pricing based on the channels.
The table automatically sorts itself to provide a better overview for all different tiers, you configured.

<img src="images/Backend.png" />

In the frontend the user will see a nice looking table right next to the "add to cart" button that shows the discount for the different tiers like so:

<img src="images/Front-End.png" />

### Creating data
You can easily create the tier prices with fixtures like that.
```yaml
sylius_fixtures:
    suites:
        my_suite:
            fixtures:
                tier_prices:
                    options:
                        custom:
                            - product_variant: "20125148-54ca-3f05-875f-5524f95aa85b"
                              channel: US_WEB
                              quantity: 10
                              price: 5
```
For this the products need to be created first and the product variant must also exist.

