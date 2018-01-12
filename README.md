<img src="images/logo.png" />
# The Brille24 TierPrices Bundle
The pricing in Sylius allows for many different customization like channels and different prices for different product variants. However, one thing that Sylius is missing is the ability to set the price bases on the amount of items the customer wants to buy. This tierpricing methodology can be implemented when this plugin is installed.

## Installation
* Install the bundle via composer `composer require brille24/tierprice-plugin`
* Register the Plugin in your AppKernel file:
```php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...

        new \Brille24\TierPriceBundle\Brille24TierPriceBundle(),
    ]);
}
```

* Add the `config.yml` to the `app/config/config.yml`
```yml
imports:
    ...
    - { resource: '@Brille24TierPriceBundle/Resources/config/config.yml'}
```

Add the `resources.yml` to the `app/config/resources.yml`
```yml
imports:
    ...
    - { resource: '@Brille24TierPriceBundle/Resources/config/resources.yml'}
```

If the `resources.yml` did not exist before add its reference to the `app/config/config.yml`
```yml
imports:
    ...
    - { resource: 'resources.yml'}
```

That way all the Sylius resource overriding happens in the `app/config/resources.yml`

* If you want to use the API for creating and updating tier prices, you also have to add the `routing.yml` to the `app/config/routing.yml`
```yml
...
brille24_tierprice_bundle:
    resource: '@Brille24TierPriceBundle/Resources/config/routing.yml'
```

* Finally update the database, install the assets and update the translations:
```sh
bin/console doctrine:schema:update --force
bin/console assets:install
bin/console translation:update
```

### Integration
* The Bundle overrides the `ProductVariant` class that is provided by Sylius. This will be overridden in the `resource.yml` of the Bundle. If you want to override that class in your application too, you have to merge the two configurations.
* Furthermore there is an entry in the `services.yml` inside the config folder that you have to uncomment if you want to have the default implementation of the tier price finder.

## Usage
First of all you have to set up a product with as many variants as you want. Then in each of these variants you can set the tier pricing based on the channels.
The table automatically sorts itself to provide a better overview for all different tiers, you configured.

<img src="images/Backend.png" />

In the frontend the user will see a nice looking table right next to the "add to cart" button that shows the discount for the different tiers like so:

<img src="images/Front-End.png" />
