## Using the new plugin structure

The root folder for the plugin is now the plugin itself which means the following directories have been moved:
- src/Resources/config -> config/
- src/Resources/views -> templates/
- src/Resources/translations -> translations/

For your project this means:
```yaml
# config/packages/brille24_sylius_tierprice_plugin.yaml
imports:
    - { resource: "@Brille24SyliusTierPricePlugin/config/config.yaml" }
```
```yaml
# config/routing.yaml

brille24_tierprice_bundle:
    resource: '@Brille24SyliusTierPricePlugin/config/routing.yml'
```

### The templates have been split to match the template structure in Sylius.
The Sylius 2 does not support extending the ProductVariant Menu via a listener, so templates have been created instead and the MenuListeners have been removed.

### The custom javascript has been removed
All custom javascript has been removed as it does not play nicely with the Live Components. This means that the price on the product page does no longer update live.


