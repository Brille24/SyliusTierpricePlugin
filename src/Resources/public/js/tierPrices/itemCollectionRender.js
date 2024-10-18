/**
 * Adds an element to the tier prices table
 *
 * @param {string} bodyId - ID of the tbody element
 * @param {string} channelCode - Channel code
 */
function tierPriceTableAdd(bodyId, channelCode) {
  // Sort the table and add the body
  const tableId = bodyId + '_table';
  tierPriceTableSort(tableId);

  const prototypeHolder = document.getElementById('prototype_holder');
  const prototype_text = prototypeHolder.getAttribute('data-prototype');
  const tableElement = document.getElementById(tableId);
  const prototype_currency = tableElement.getAttribute('data-prototype');

  const body = document.getElementById(bodyId);

  // Replace '__name__' in the prototype HTML with a number based on the number of elements
  var elementSource = prototype_text.replace(
    new RegExp('__name__', 'g'),
    tierPriceIndex
  );
  body.insertAdjacentHTML('beforeend', elementSource);

  // Select the new element and set the channel
  const newElement_channel = body.querySelector(
    '#sylius_product_variant_tierPrices_' + tierPriceIndex + '_channel'
  );
  if (newElement_channel) {
    newElement_channel.value = channelCode;
  }

  // Set the currency
  const newElement_currency = body.querySelector('tr:last-child div.ui.label');
  if (newElement_currency) {
    newElement_currency.innerHTML = prototype_currency;
  }

  // Add the new element to the sorting listener
  setSortingListener();

  tierPriceIndex++;
}

/**
 * Removes an element from the table
 *
 * @param {HTMLElement} element - The element clicked for removal
 */
function tierPriceTableRemove(element) {
  const row = element.closest('tr');
  if (row) {
    row.remove();
  }
}

/**
 * Sorts the table by quantity
 *
 * @param {string} tableId - The ID of the table to sort
 */
function tierPriceTableSort(tableId) {
  const table = document.getElementById(tableId);
  if (!table) return;

  const tbody = table.querySelector('tbody');
  if (!tbody) return;

  const rows = Array.from(tbody.querySelectorAll('tr'));

  function comparator(rowA, rowB) {
    const inputA = rowA.querySelector('input');
    const inputB = rowB.querySelector('input');
    const valueA = inputA ? Number(inputA.value) : 0;
    const valueB = inputB ? Number(inputB.value) : 0;
    return valueA - valueB;
  }

  rows.sort(comparator);

  // Remove existing rows
  while (tbody.firstChild) {
    tbody.removeChild(tbody.firstChild);
  }

  // Add sorted rows
  rows.forEach(function (row) {
    tbody.appendChild(row);
  });
}

/**
 * Adds a sorting listener to quantity fields
 */
function setSortingListener() {
  const elements = document.querySelectorAll('.TIERPRICE_SORTING_CHANGED');
  elements.forEach(function (element) {
    element.addEventListener('change', function (event) {
      const tableElement = element.closest('table');
      if (tableElement) {
        tierPriceTableSort(tableElement.id);
      }
    });
  });
}

// Initialize the sorting listener
setSortingListener();

document.addEventListener('DOMContentLoaded', function () {
  const tables = document.querySelectorAll('table');
  tables.forEach(function (table) {
    const tableId = table.id;
    if (tableId && tableId.indexOf('tierPricesTable_') === 0) {
      tierPriceTableSort(tableId);
    }
  });
});
