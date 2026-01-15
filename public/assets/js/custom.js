const baseurl = "http://localhost:8080/websites/bharatbank/laravel-base-folder/public/";
// function setupGenericFilter(options) {
//     if (!options || !options.url || !options.filterFields || !options.renderCallback) return;

//     const { url, filterFields, renderCallback, debounceTime = 300 } = options;
//     const cache = {};

//     function debounce(func, wait) {
//         let timeout;
//         return function(...args) {
//             clearTimeout(timeout);
//             timeout = setTimeout(() => func.apply(this, args), wait);
//         };
//     }

//     function getCsrfToken() {
//         return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
//     }

//     function gatherFilters() {
//         const filters = {};
//         filterFields.forEach(field => {
//             const { name, selector, type } = field;
//             const el = document.querySelector(selector);
//             if (!el) return;
//             if (type === 'checkbox') filters[name] = Array.from(document.querySelectorAll(selector + ':checked')).map(chk => chk.value);
//             else filters[name] = el.value ? (type === 'number' || type === 'range' ? parseFloat(el.value) : el.value) : null;
//         });
//         return filters;
//     }

//     const fetchData = () => {
//         const params = gatherFilters();
//         const cacheKey = JSON.stringify(params);
//         if (cache[cacheKey]) { renderCallback(cache[cacheKey]); return; }

//         fetch(url, {
//             method: 'POST',
//             headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
//             body: JSON.stringify(params)
//         })
//         .then(res => res.ok ? res.json() : Promise.reject('Network error'))
//         .then(data => { cache[cacheKey] = data; renderCallback(data); })
//         .catch(err => console.error(err));
//     };

//     const debouncedFetch = debounce(fetchData, debounceTime);

//     filterFields.forEach(field => {
//         const elements = document.querySelectorAll(field.selector);
//         elements.forEach(el => { el.addEventListener('input', debouncedFetch); el.addEventListener('change', debouncedFetch); });
//     });

//     debouncedFetch();
// }

// document.addEventListener('DOMContentLoaded', function() {
//     setupGenericFilter({
//         url: baseurl + 'auctions/filter',
//         filterFields: [
//             { name: 'states', selector: '.state-checkbox', type: 'checkbox' },
//             { name: 'cities', selector: '.city-checkbox', type: 'checkbox' },
//             { name: 'price', selector: '#price', type: 'range' },
//             { name: 'sqft_min', selector: '#sqft-min', type: 'number' },
//             { name: 'sqft_max', selector: '#sqft-max', type: 'number' }
//         ],
//         // renderCallback: function(data) {
//         //     const container = document.querySelector('.content-area');
//         //     container.innerHTML = '';
//         //     if (!data.length) { container.innerHTML = '<p>No results found.</p>'; return; }
//         //     data.forEach(item => {
//         //         const div = document.createElement('div');
//         //         div.className = 'auction-item';
//         //         div.innerHTML = `
//         //             <div class="auction-description">${item.description}</div>
//         //             <div class="auction-details">
//         //                 Price: ₹${Number(item.price).toLocaleString()} <br/>
//         //                 Sq.Ft: ${item.sq_ft ?? 'N/A'} <br/>
//         //                 State: ${item.state_name ?? 'N/A'} <br/>
//         //                 City: ${item.city_name ?? 'N/A'}
//         //             </div>
//         //         `;
//         //         container.appendChild(div);
//         //     });
//         // },
//         renderCallback: function(data) {
//     const container = document.querySelector('.content-area');
//     container.innerHTML = '';
//     if (!data.length) {
//         container.innerHTML = '<p>No results found.</p>';
//         return;
//     }
//     data.forEach(item => {
//         const div = document.createElement('div');
//         div.className = 'auction-item';
//         div.innerHTML = `
//             <div class="auction-description">${item.description}</div>
//             <div class="auction-details">
//                 Price: ₹${Number(item.price).toLocaleString()} <br/>
//                 Sq.Ft: ${item.sq_ft ?? 'N/A'} <br/>
//                 State: ${item.state_name ?? 'N/A'} <br/>
//                 City: ${item.city_name ?? 'N/A'}
//             </div>
//         `;
//         container.appendChild(div);
//     });
// }
// ,
//         debounceTime: 400
//     });
// });

function auctionFilteration() {
    const filters = document.querySelectorAll('.filter-checkbox, #price_max, #sqft_min, #sqft_max');
    const resultsContainer = document.getElementById('auction-results');

    function getFilterData() {
        const params = new URLSearchParams();

        // Collect all checked checkboxes
        document.querySelectorAll('.filter-checkbox:checked').forEach(el => {
            params.append(el.name, el.value); // supports multiple values for same name
        });

        // Price and Sq.Ft
        const price = document.getElementById('price_max').value;
        if (price) params.append('price_max', price);

        const sqft_min = document.getElementById('sqft_min').value;
        if (sqft_min) params.append('sqft_min', sqft_min);

        const sqft_max = document.getElementById('sqft_max').value;
        if (sqft_max) params.append('sqft_max', sqft_max);

        return params.toString(); // return as query string
    }

    function fetchFilteredData() {
        const queryString = getFilterData();
        const url = baseurl + "auctions" + (queryString ? '?' + queryString : '');

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.text())
        .then(html => {
            resultsContainer.innerHTML = html;
        })
        .catch(err => console.error('AJAX Error:', err));
    }

    // Attach change event to all filters
    filters.forEach(el => {
        el.addEventListener('change', fetchFilteredData);
    });

    // Price label update
    const priceLabel = document.getElementById('price-label');
    const priceRange = document.getElementById('price_max');
    priceRange.addEventListener('input', () => priceLabel.textContent = priceRange.value);
}

// Initialize
// auctionFilteration();


function auctionFilterationGeneric(containerSelector, url, filters) {
    const resultsContainer = document.querySelector(containerSelector);

    function getFilterData() {
        const params = new URLSearchParams();
        for (const [filterName, selector] of Object.entries(filters)) {
            document.querySelectorAll(selector).forEach(el => {
                if (el.type === 'checkbox' && el.checked) params.append(filterName, el.value);
                else if ((el.type === 'text' || el.type === 'number') && el.value) params.append(filterName, el.value);
            });
        }
        return params.toString();
    }

    function fetchFilteredData() {
        const queryString = getFilterData();
        const fetchUrl = url + (queryString ? '?' + queryString : '');
        fetch(fetchUrl, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(html => resultsContainer.innerHTML = html)
            .catch(err => console.error('AJAX Error:', err));
    }

    for (const selector of Object.values(filters)) {
        document.querySelectorAll(selector).forEach(el => {
            el.addEventListener('change', fetchFilteredData);
            el.addEventListener('input', fetchFilteredData);
        });
    }
}

auctionFilterationGeneric(
    '#auction-results',
    baseurl + 'auctions',
    {
        search: '#auction-search',
        state: '.state-checkbox',
        city: '.city-checkbox',
        town: '.town-checkbox',
        price_min: '#price_min',
        price_max: '#price_max',
        sqft_min: '#sqft_min',
        sqft_max: '#sqft_max'
    }
);


