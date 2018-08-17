# Tier prices in Sylius
[![Build Status](https://travis-ci.org/Brille24/SyliusTierpricePlugin.svg?branch=master)](https://travis-ci.org/Brille24/SyliusTierpricePlugin)

The tierpricing plugin in Sylius allows for many different customization like channels and different prices for different product variants. However, one thing that Sylius is missing is the ability to set the price bases on the amount of items the customer wants to buy. This tier pricing methodology can be implemented when this plugin is installed.

## Installation
* Install the bundle via composer `composer require brille24/sylius-tierprice-plugin`
* Register the Plugin in your AppKernel file:
```php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...

        new \Brille24\SyliusTierPricePlugin\Brille24SyliusTierPricePlugin(),
    ]);
}
```

* Add the `config.yml` and `resources.yml` to your local `app/config/config.yml`
```yml
imports:
    ...
    - { resource: '@Brille24SyliusTierPricePlugin/Resources/config/config.yml'}
```

That way all the Sylius resource overriding happens in the `app/config/resources.yml`

* For API functionality add the bundle's `routing.yml` to the local `app/config/routing.yml`
```yml
...
brille24_tierprice_bundle:
    resource: '@Brille24SyliusTierPricePlugin/Resources/config/routing.yml'
```

* Finally update the database, install the assets and update the translations:
```sh
bin/console doctrine:schema:update --force
bin/console assets:install
bin/console translation:update
```

### Integration
* The Bundle overrides the `ProductVariant` class that is provided by Sylius. This will be overridden in the `resource.yml` of the Bundle. If you want to override that class in your application too, you have to merge the two configurations.
* This bundle registers an [order processor](https://docs.sylius.com/en/1.2/components_and_bundles/components/Order/processors.html) service `brille24_tier_price.order_processing.order_prices_recalculator`. If you wish to use your own order processor or change its priority, you could register a [compiler pass](https://symfony.com/doc/current/service_container/compiler_passes.html).

If you want to override the default price shown for a variant in the product overview and detail page, override the default product variant price calculation class of Sylius.
```yaml
  brille24.calculator.product_variant_price.decorator:
    class: Brille24\SyliusTierPricePlugin\Services\ProductVariantPriceCalculator
    decorates: sylius.calculator.product_variant_price
    public: false
    arguments:
      - '@sylius.calculator.product_variant_price.decorator.inner'
      - '@brille24_tier_price.services.tier_price_finder'
```

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
