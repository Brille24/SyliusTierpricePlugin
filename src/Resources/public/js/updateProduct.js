function recalculatePrices(quantity) {
    $('#tier_prices_tables').children().each(function (i, table) {
        var jQueryTable = $(table);
        if (jQueryTable.css('display') !== 'none') {

            var price = getPricesFromTierpriceTable(table, quantity);
            if (price === null) {
                var id = jQueryTable.attr('id');
                price = getDefaultPriceForProductVariant(id.substr(0, id.length - 6));
            }

            $('#product-price').text(price);
        }
    });
}

function getPricesFromTierpriceTable(element, quantity) {
    var cheapestPrice = null;
    $(element).find('tr').each(function (i, row) {
        var currentAmount = Number($(row.children[0]).text());
        if (quantity >= currentAmount) {
            cheapestPrice = $(row.children[1]).text()
        }
    });

    return cheapestPrice;
}

function getDefaultPriceForProductVariant(productVariantName) {
    console.log('finding price for ' + productVariantName);
    var variantSelector = $('[value="' + productVariantName + '"]');
    var priceText = variantSelector.parent().prev();
    return priceText.text();
}

$('#sylius_add_to_cart_cartItem_quantity').on('change', function (event) {
    var quantity = Number(event.target.value);
    recalculatePrices(quantity);
});