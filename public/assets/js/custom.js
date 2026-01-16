
// function auctionFilterationGeneric(containerSelector, url, filters) {
//     const resultsContainer = document.querySelector(containerSelector);

//     function getFilterData() {
//         const params = new URLSearchParams();
//         for (const [filterName, selector] of Object.entries(filters)) {
//             document.querySelectorAll(selector).forEach(el => {
//                 if (el.type === 'checkbox' && el.checked) params.append(filterName, el.value);
//                 else if ((el.type === 'text' || el.type === 'number') && el.value) params.append(filterName, el.value);
//             });
//         }
//         return params.toString();
//     }

//     function fetchFilteredData() {
//         const queryString = getFilterData();
//         const fetchUrl = url + (queryString ? '?' + queryString : '');
//         fetch(fetchUrl, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
//             .then(res => res.text())
//             .then(html => resultsContainer.innerHTML = html)
//             .catch(err => console.error('AJAX Error:', err));
//     }

//     for (const selector of Object.values(filters)) {
//         document.querySelectorAll(selector).forEach(el => {
//             el.addEventListener('change', fetchFilteredData);
//             el.addEventListener('input', fetchFilteredData);
//         });
//     }
// }

function auctionFilterationGeneric(containerSelector, url, filters) {
    const resultsContainer = document.querySelector(containerSelector);

    function getFilterData() {
        const params = new URLSearchParams();

        for (const [filterName, selector] of Object.entries(filters)) {
            document.querySelectorAll(selector).forEach(el => {
                if (el.type === 'checkbox' && el.checked) {
                    params.append(filterName + '[]', el.value);
                }
                else if ((el.type === 'text' || el.type === 'number') && el.value) {
                    params.append(filterName, el.value);
                }
            });
        }

        return params.toString();
    }

    function fetchFilteredData() {
        const queryString = getFilterData();
        const fetchUrl = url + (queryString ? '?' + queryString : '');

        fetch(fetchUrl, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => resultsContainer.innerHTML = html)
        .catch(err => console.error(err));
    }

    for (const selector of Object.values(filters)) {
        document.querySelectorAll(selector).forEach(el => {
            el.addEventListener('change', fetchFilteredData);
            el.addEventListener('input', fetchFilteredData);
        });
    }
}


let page_id = document.body.id;

if (page_id === "auction-list"){
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
}

if (page_id === "unclaimed-deposit-list"){
    auctionFilterationGeneric(
    '#unclaimed-results',
    baseurl + 'unclaimed-deposit',
    {
        search: '#unclaimedDeposit-search',
        state: '.state-checkbox',
        city: '.city-checkbox',
        town: '.town-checkbox',
    }
);
}







