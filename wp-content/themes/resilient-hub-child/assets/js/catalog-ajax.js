/**
 * Vanilla JS handler for reload-free resource catalog filtering,
 * pagination, and moderation dashboard approvals.
 */
document.addEventListener('DOMContentLoaded', function() {
    // -------------------------------------------------------------
    // 1. Catalog Filtering & Pagination
    // -------------------------------------------------------------
    const filterForm = document.querySelector('.rp-catalog-filters');
    const gridWrapper = document.querySelector('.rp-resource-grid-wrapper');
    
    if (filterForm && gridWrapper) {
        const gridContainer = gridWrapper.querySelector('.rp-resource-grid');
        const paginationWrapper = gridWrapper.querySelector('.rp-pagination-wrapper');
        
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

        // Fetch catalog results via WP Admin AJAX API
        function fetchCatalogResults(paged = 1) {
            // Show loader
            loader.style.display = 'flex';
            gridContainer.style.opacity = '0.5';

            const formData = new FormData(filterForm);
            const params = new URLSearchParams();
            
            params.append('action', 'rp_filter_resources');
            params.append('paged', paged);
            
            // Map form fields to AJAX params
            const searchVal = filterForm.querySelector('#rp_q') ? filterForm.querySelector('#rp_q').value : '';
            params.append('q', searchVal);

            const categoryVal = filterForm.querySelector('#rp_resource_category') ? filterForm.querySelector('#rp_resource_category').value : '0';
            params.append('resource_category', categoryVal);

            const hazardVal = filterForm.querySelector('#rp_hazard_type') ? filterForm.querySelector('#rp_hazard_type').value : '0';
            params.append('hazard_type', hazardVal);

            const audienceVal = filterForm.querySelector('#rp_target_audience') ? filterForm.querySelector('#rp_target_audience').value : '0';
            params.append('target_audience', audienceVal);

            const orgVal = filterForm.querySelector('#rp_contributing_org') ? filterForm.querySelector('#rp_contributing_org').value : '0';
            params.append('contributing_org', orgVal);

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
                
                // Scroll smoothly to top of catalog
                gridWrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        }

        // Event listener for inputs change / text input with debounce
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (debounceTimeout) clearTimeout(debounceTimeout);
            fetchCatalogResults(1);
        });

        filterForm.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', function() {
                if (debounceTimeout) clearTimeout(debounceTimeout);
                fetchCatalogResults(1);
            });
        });

        const searchInput = filterForm.querySelector('#rp_q');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (debounceTimeout) clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => {
                    fetchCatalogResults(1);
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
                    fetchCatalogResults(paged);
                } catch (err) {
                    // Fallback in case of weird URLs
                    const pageMatch = paginationLink.href.match(/[?&]rp_page=(\d+)/);
                    const paged = pageMatch ? pageMatch[1] : 1;
                    fetchCatalogResults(paged);
                }
            }
        });
    }

    // -------------------------------------------------------------
    // 1b. Opportunity submission filtering
    // -------------------------------------------------------------
    const submissionFilterForm = document.querySelector('.rp-opportunity-submission-filters');
    const submissionResults = document.querySelector('.rp-opportunity-submissions-results');

    if (submissionFilterForm && submissionResults && typeof rp_ajax !== 'undefined') {
        let submissionDebounce = null;

        function fetchSubmissionResults() {
            submissionResults.style.opacity = '0.55';

            const formData = new FormData(submissionFilterForm);
            const params = new URLSearchParams();
            params.append('action', 'rp_filter_opportunity_submissions');
            params.append('type', formData.get('submission_type') || submissionResults.dataset.type || '');
            params.append('opportunity_id', formData.get('opportunity_id') || submissionResults.dataset.opportunityId || '');

            formData.forEach((value, key) => {
                if (key.indexOf('filter_') === 0) {
                    params.append(key, value);
                }
            });

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
                    submissionResults.innerHTML = data.data.html;
                } else {
                    console.error('Submission filtering failed:', data);
                }
            })
            .catch(error => {
                console.error('Submission AJAX Error:', error);
            })
            .finally(() => {
                submissionResults.style.opacity = '1';
            });
        }

        submissionFilterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchSubmissionResults();
        });

        submissionFilterForm.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', fetchSubmissionResults);
        });

        submissionFilterForm.querySelectorAll('input[type="search"]').forEach(input => {
            input.addEventListener('input', function() {
                if (submissionDebounce) clearTimeout(submissionDebounce);
                submissionDebounce = setTimeout(fetchSubmissionResults, 400);
            });
        });
    }

    // -------------------------------------------------------------
    // 2. Admin Moderation Actions
    // -------------------------------------------------------------
    const moderationTable = document.querySelector('.rp-moderation-table');
    if (moderationTable) {
        moderationTable.addEventListener('click', function(e) {
            const actionBtn = e.target.closest('.rp-approve-btn, .rp-reject-btn');
            if (!actionBtn) return;

            e.preventDefault();

            if (actionBtn.disabled || actionBtn.classList.contains('processing')) {
                return;
            }

            const isReject = actionBtn.classList.contains('rp-reject-btn');
            const postId = actionBtn.getAttribute('data-post-id');
            const nonce = actionBtn.getAttribute('data-nonce');
            const row = actionBtn.closest('tr');
            let reason = '';

            if (!postId || !nonce || !row) return;

            if (isReject) {
                reason = prompt('Enter the reason for rejecting this submission. The author will receive this feedback:');
                if (reason === null) return;
                reason = reason.trim();
                if (!reason) {
                    alert('A rejection reason is required.');
                    return;
                }
                if (reason.length > 1000) {
                    alert('The rejection reason must be 1,000 characters or fewer.');
                    return;
                }
            } else if (!confirm('Are you sure you want to approve and publish this submission?')) {
                return;
            }

            const rowButtons = row.querySelectorAll('button');
            rowButtons.forEach(button => { button.disabled = true; });
            actionBtn.classList.add('processing');
            const originalText = actionBtn.textContent;
            actionBtn.textContent = isReject ? 'Rejecting...' : 'Approving...';

            const params = new URLSearchParams();
            params.append('action', isReject ? 'rp_reject_resource' : 'rp_approve_resource');
            params.append('post_id', postId);
            params.append('nonce', nonce);
            if (isReject) params.append('reason', reason);

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
                    actionBtn.classList.remove('processing');
                    actionBtn.classList.add(isReject ? 'success-rejected' : 'success-approved');
                    actionBtn.textContent = isReject ? 'Rejected ✓' : 'Approved ✓';

                    if (data.data.warning) alert(data.data.warning);
                    
                    setTimeout(() => {
                        row.classList.add('rp-row-fading');
                        const removeRow = function() {
                            if (!row.parentNode) return;
                            row.remove();
                            const remainingRows = moderationTable.querySelectorAll('tbody tr');
                            if (remainingRows.length === 0) {
                                const container = moderationTable.parentNode;
                                if (container) {
                                    container.innerHTML = '<div class="rp-moderation-empty"><p>No pending submissions to review.</p></div>';
                                }
                            }
                        };
                        row.addEventListener('transitionend', removeRow, { once: true });
                        setTimeout(removeRow, 500);
                    }, 600);
                } else {
                    alert(data.data.message || 'An error occurred. Please try again.');
                    rowButtons.forEach(button => { button.disabled = false; });
                    actionBtn.classList.remove('processing');
                    actionBtn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Moderation AJAX Error:', error);
                alert('Connection error. Please try again.');
                rowButtons.forEach(button => { button.disabled = false; });
                actionBtn.classList.remove('processing');
                actionBtn.textContent = originalText;
            });
        });
    }
});
