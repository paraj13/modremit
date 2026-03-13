document.addEventListener('DOMContentLoaded', function() {
    const searchInputs = document.querySelectorAll('[data-search-target]');
    
    searchInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const targetSelector = input.getAttribute('data-search-target');
            const tableBody = document.querySelector(targetSelector + ' tbody');
            
            if (!tableBody) return;
            
            const rows = tableBody.querySelectorAll('tr');
            
            rows.forEach(row => {
                // Skip if it's the "No items found" empty message row
                if (row.cells.length === 1 && row.cells[0].colSpan > 1) {
                    return;
                }
                
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});
