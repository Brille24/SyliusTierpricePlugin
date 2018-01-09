<img src="https://gitlab.dev-b24.de/mamazu/sylius-tierprice/raw/master/images/logo.png" />
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

If you want to use the API for creating and updating tierpices, you also have to add the `routing.yml` to the `app/config/routing.yml`
```yml
...
brille24_tierprice_bundle:
	resource: '@Brille24TierPriceBundle/Resources/config/routing.yml'
```

* Finally update the database and install the assets:
```bash
bin/console doctrine:schema:update --force
bin/console assets:install
```

## Usage
First of all you have to set up a product with as many variants as you want. Then in each of these variants you can set the tierpricing based on the channels.
The table automatically sorts itself to provide a better overview for all different tiers, you configured.
<img src="https://gitlab.dev-b24.de/mamazu/sylius-tierprice/raw/master/images/Front-End.png" />

In the frontend the user will see a nice looking table right next to the "add to cart" button that shows the discount for the different tiers like so:
<img src="https://gitlab.dev-b24.de/mamazu/sylius-tierprice/raw/master/images/Backend.png" />
