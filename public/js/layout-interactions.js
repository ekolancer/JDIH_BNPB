(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const advancedSearchToggle = document.getElementById('advanced-search-toggle');
        const searchForm = document.querySelector('.search-form');

        if (advancedSearchToggle) {
            advancedSearchToggle.addEventListener('click', function () {
                const filters = document.querySelector('.advanced-filters');
                if (!filters) {
                    return;
                }

                if (filters.classList.contains('show')) {
                    filters.classList.remove('show');
                    setTimeout(() => {
                        filters.style.display = 'none';
                    }, 500);
                } else {
                    filters.style.display = 'block';
                    setTimeout(() => {
                        filters.classList.add('show');
                    }, 200);
                }
            });
        }

        if (searchForm) {
            searchForm.addEventListener('submit', function () {
                const inputs = this.querySelectorAll('input, select');
                inputs.forEach(input => {
                    if (!input.value || input.value === '') {
                        input.name = '';
                    }
                });
            });
        }
    });

    window.addEventListener('show-lampiran-modal', function () {
        const mediaFile = document.getElementById('mediaFile');
        if (!mediaFile || typeof bootstrap === 'undefined') {
            return;
        }

        const modal = new bootstrap.Modal(mediaFile);
        modal.show();
    });
})();