function hideOtherTierPriceTable(targetTableId) {
    $('#tier_prices_tables').children().each(function () {
        var element = $(this);
        if (element.attr('id') === targetTableId) {
            element.show();
        } else {
            element.hide();
        }
    });

    var quantity = $('#sylius_add_to_cart_cartItem_quantity')[0].value;
    recalculatePrices(quantity);
}

$(document).ready(function () {
    var currentVariantCode = $('[name="sylius_add_to_cart[cartItem][variant]"]:checked').attr('value');
    if (currentVariantCode === undefined) {
        return;
    }
    var tableId = currentVariantCode + '_table';
    hideOtherTierPriceTable(tableId);
});

$('[name="sylius_add_to_cart[cartItem][variant]"]').on('change', function (event) {
    var targetTableId = event.currentTarget.getAttribute('value') + '_table';
    hideOtherTierPriceTable(targetTableId);
});