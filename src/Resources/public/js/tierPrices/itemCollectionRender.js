/**
 * Adds an element to the tierprice table
 *
 * @param bodyId Id of the body element
 * @param channelCode The channel code
 */
function tierPriceTableAdd(bodyId, channelCode) {
    // Sorts the table and adds the body
    var tableId = bodyId + '_table';
    tierPriceTableSort(tableId);

    var prototypeHtml = $('#sylius_product_variant_tierPrices_prototype_holder').data('prototype');
    var currencySymbol = $('#' + tableId).data('currency');

    var body = $('#' + bodyId);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newRow = $(prototypeHtml.replace(/__name__/g, tierPriceIndex));

    // Set the currency symbol
    newRow.find('.priceField .ui.label').html(currencySymbol);

    // Set the channel code
    newRow.find('select[name$="[channel]"]').val(channelCode);

    body.append(newRow);

    // Add the new element to the sorting listener
    setSortingListener();

    tierPriceIndex++;
}

/**
 * Removes an element from the table
 *
 * @param element
 */
function tierPriceTableRemove(element) {
    $(element).parent().parent().remove();
}

/**
 * Sorts the table by quantity
 *
 * @param tableId
 */
function tierPriceTableSort(tableId) {
    var table = $('#' + tableId);

    function comperator(a, b) {

        function getValue(cell) {
            return Number(cell.find('input')[0].value);
        }

        return getValue($(a)) > getValue($(b));
    }

    table.find('th.table-column-quantity')
        .wrapInner('<span title="sort this column"/>')
        .each(function () {
            var th = $(this),
                thIndex = th.index();
            // Filters through the table to just extract the sorting column
            table.find('td').filter(function () {
                return $(this).index() === thIndex;
            }).sortElements(comperator, function () {
                // parentNode is the element we want to move
                return this.parentNode;
            });

        });

}

/**
 * Adds a sorting listener to the quantity
 */
function setSortingListener() {
    $('.TIERPRICE_SORTING_CHANGED').on('change', function (event) {
        var element = event.target;
        var tableElement = $(element).parent().parent().parent().parent().parent();
        tierPriceTableSort(tableElement.attr('id'));
    });
}

// Sets the event listener
setSortingListener();

$(document).ready(function () {
    $('table[id^="sylius_product_variant_tierPrices_"]').each(function () {
        var tableId = $(this).attr('id');
        tierPriceTableSort(tableId);
    });
});
