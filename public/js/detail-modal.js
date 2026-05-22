/**
 * Detail Modal Handler
 * Provides utility functions for displaying record details in modals
 */

function showDetailModal(title, data) {
    // Create or get the detail modal
    let modal = document.getElementById('detail-modal');

    if (!modal) {
        // Create modal if it doesn't exist
        modal = document.createElement('div');
        modal.id = 'detail-modal';
        modal.className = 'modal fade detail-modal';
        modal.tabIndex = '-1';
        modal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detail-modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="detail-modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    // Set title
    document.getElementById('detail-modal-title').textContent = title;

    // Build detail content
    let html = '';
    for (const [key, value] of Object.entries(data)) {
        if (value === null || value === undefined || value === '') continue;

        // Format label from key
        const label = key
            .replace(/_/g, ' ')
            .replace(/([a-z])([A-Z])/g, '$1 $2')
            .split(' ')
            .map(w => w.charAt(0).toUpperCase() + w.slice(1))
            .join(' ');

        // Check if it's a currency field
        const isCurrency = key.includes('harga') || key.includes('price') ||
                          key.includes('nominal') || key.includes('diskon') ||
                          key.includes('bayar') || key.includes('total');

        const valueClass = isCurrency ? 'detail-value currency' : 'detail-value';
        const displayValue = isCurrency && !isNaN(value) ?
            'Rp ' + parseInt(value).toLocaleString('id-ID') :
            value;

        html += `
            <div class="detail-row">
                <div class="detail-label">${label}</div>
                <div class="${valueClass}">${displayValue}</div>
            </div>
        `;
    }

    document.getElementById('detail-modal-body').innerHTML = html;

    // Show modal using jQuery (since Bootstrap is available)
    $('#detail-modal').modal('show');
}

/**
 * Fetch and display detail
 * @param {string} url - API endpoint to fetch details
 * @param {string} title - Modal title
 */
function fetchAndShowDetail(url, title) {
    $.ajax({
        url: url,
        method: 'GET',
        success: function(data) {
            showDetailModal(title, data);
        },
        error: function() {
            alert('Gagal memuat detail data');
        }
    });
}

// Expose a simple global alias used by server-rendered buttons
window.showDetail = function(url, title = 'Detail') {
    fetchAndShowDetail(url, title);
};
