/**
 * Vanilla JS handler for reload-free News & Stories catalog filtering,
 * pagination, and search.
 */
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.querySelector('.rp-news-filters');
    const gridWrapper = document.querySelector('.rp-news-grid-wrapper');
    
    if (filterForm && gridWrapper) {
        const gridContainer = gridWrapper.querySelector('.rp-news-grid');
        const paginationWrapper = gridWrapper.querySelector('.rp-news-pagination-wrapper');
        
        // Ensure a loader element exists in the grid wrapper
        let loader = gridWrapper.querySelector('.rp-loader-container');
        if (!loader) {
            loader = document.createElement('div');
            loader.className = 'rp-loader-container';
            loader.innerHTML = '<div class="rp-loader-spinner"></div>';
            loader.style.display = 'none';
            gridWrapper.appendChild(loader);
        }

        let debounceTimeout = null;

        // Fetch news results via WP Admin AJAX API
        function fetchNewsResults(paged = 1) {
            // Show loader
            loader.style.display = 'flex';
            gridContainer.style.opacity = '0.5';

            const params = new URLSearchParams();
            params.append('action', 'rp_filter_news');
            params.append('paged', paged);
            
            const searchVal = filterForm.querySelector('#rp_q') ? filterForm.querySelector('#rp_q').value : '';
            params.append('q', searchVal);

            const categoryVal = filterForm.querySelector('#rp_news_category') ? filterForm.querySelector('#rp_news_category').value : '0';
            params.append('category', categoryVal);

            // Fetch request
            fetch(rp_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: params.toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    gridContainer.innerHTML = data.data.grid;
                    if (paginationWrapper) {
                        paginationWrapper.innerHTML = data.data.pagination;
                    }
                } else {
                    console.error('Filtering failed:', data);
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
            })
            .finally(() => {
                loader.style.display = 'none';
                gridContainer.style.opacity = '1';
                
                // Scroll smoothly to top of news list
                gridWrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        }

        // Event listener for form submission
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (debounceTimeout) clearTimeout(debounceTimeout);
            fetchNewsResults(1);
        });

        // Event listener for category select dropdown change
        const categorySelect = filterForm.querySelector('#rp_news_category');
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                if (debounceTimeout) clearTimeout(debounceTimeout);
                fetchNewsResults(1);
            });
        }

        // Event listener for search input
        const searchInput = filterForm.querySelector('#rp_q');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (debounceTimeout) clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => {
                    fetchNewsResults(1);
                }, 400); // 400ms debounce
            });
        }

        // Intercept pagination clicks via event delegation
        gridWrapper.addEventListener('click', function(e) {
            const paginationLink = e.target.closest('.rp-pagination a');
            if (paginationLink) {
                e.preventDefault();
                try {
                    const url = new URL(paginationLink.href);
                    const paged = url.searchParams.get('rp_page') || 1;
                    fetchNewsResults(paged);
                } catch (err) {
                    const pageMatch = paginationLink.href.match(/[?&]rp_page=(\d+)/);
                    const paged = pageMatch ? pageMatch[1] : 1;
                    fetchNewsResults(paged);
                }
            }
        });
    }
});
