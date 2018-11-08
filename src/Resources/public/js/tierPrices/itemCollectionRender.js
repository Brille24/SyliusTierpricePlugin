/**
 * Adds an element to the tierprice table
 *
 * @param bodyId Id of the body element
 * @param channelCode Code of the channel
 */
function tierPriceTableAdd(bodyId, channelCode) {
    // Sorts the table and adds the body
    const tableId = bodyId + '_table';
    tierPriceTableSort(tableId);

    const prototype_text = $('#prototype_holder').attr('data-prototype');
    const prototype_currency = $('table#' + tableId).attr('data-prototype');

    const bodySelector = '#' + bodyId;
    const body = $(bodySelector);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var elementSource = prototype_text.replace(new RegExp('__name__', 'g'), tierPriceIndex);
    body.append(elementSource);

    // Selects the element again and set the channel
    const newElement_channel = $(bodySelector + ' #sylius_product_variant_tierPrices_' + tierPriceIndex + '_channel');
    newElement_channel.val(channelCode);

    // Setting the currency
    const newElement_currency = $('tbody#'+bodyId + ' tr:last-child div.ui.label');
    newElement_currency.html(prototype_currency);

    // Adds the new element to the sorting listener
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
    $('table').filter(function () {
        return $(this).attr('id').indexOf('tierPricesTable_') === 0;
    }).each(function () {
        var tableId = $(this).attr('id');
        tierPriceTableSort(tableId);
    })
});
