const baseurl = "http://localhost:8080/websites/bharatbank/laravel-base-folder/public/";
function setupGenericFilter(options) {
    if (!options || !options.url || !options.filterFields || !options.renderCallback) return;

    const { url, filterFields, renderCallback, debounceTime = 300 } = options;
    const cache = {};

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    function gatherFilters() {
        const filters = {};
        filterFields.forEach(field => {
            const { name, selector, type } = field;
            const el = document.querySelector(selector);
            if (!el) return;
            if (type === 'checkbox') filters[name] = Array.from(document.querySelectorAll(selector + ':checked')).map(chk => chk.value);
            else filters[name] = el.value ? (type === 'number' || type === 'range' ? parseFloat(el.value) : el.value) : null;
        });
        return filters;
    }

    const fetchData = () => {
        const params = gatherFilters();
        const cacheKey = JSON.stringify(params);
        if (cache[cacheKey]) { renderCallback(cache[cacheKey]); return; }

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
            body: JSON.stringify(params)
        })
        .then(res => res.ok ? res.json() : Promise.reject('Network error'))
        .then(data => { cache[cacheKey] = data; renderCallback(data); })
        .catch(err => console.error(err));
    };

    const debouncedFetch = debounce(fetchData, debounceTime);

    filterFields.forEach(field => {
        const elements = document.querySelectorAll(field.selector);
        elements.forEach(el => { el.addEventListener('input', debouncedFetch); el.addEventListener('change', debouncedFetch); });
    });

    debouncedFetch();
}

document.addEventListener('DOMContentLoaded', function() {
    setupGenericFilter({
        url: baseurl + 'auctions/filter',
        filterFields: [
            { name: 'states', selector: '.state-checkbox', type: 'checkbox' },
            { name: 'cities', selector: '.city-checkbox', type: 'checkbox' },
            { name: 'price', selector: '#price', type: 'range' },
            { name: 'sqft_min', selector: '#sqft-min', type: 'number' },
            { name: 'sqft_max', selector: '#sqft-max', type: 'number' }
        ],
        // renderCallback: function(data) {
        //     const container = document.querySelector('.content-area');
        //     container.innerHTML = '';
        //     if (!data.length) { container.innerHTML = '<p>No results found.</p>'; return; }
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
        // },
        renderCallback: function(data) {
    const container = document.querySelector('.content-area');
    container.innerHTML = '';
    if (!data.length) {
        container.innerHTML = '<p>No results found.</p>';
        return;
    }
    data.forEach(item => {
        const div = document.createElement('div');
        div.className = 'auction-item';
        div.innerHTML = `
            <div class="auction-description">${item.description}</div>
            <div class="auction-details">
                Price: ₹${Number(item.price).toLocaleString()} <br/>
                Sq.Ft: ${item.sq_ft ?? 'N/A'} <br/>
                State: ${item.state_name ?? 'N/A'} <br/>
                City: ${item.city_name ?? 'N/A'}
            </div>
        `;
        container.appendChild(div);
    });
}
,
        debounceTime: 400
    });
});
