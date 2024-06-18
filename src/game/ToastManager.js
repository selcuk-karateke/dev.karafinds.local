export class ToastManager {
    constructor() {
        this.toastContainer = document.querySelector('.toast-container');
    }

    showToast(message, options = {}) {
        const { title = 'Benachrichtigung', delay = 5000, type = 'info' } = options;
        const toastTemplate = `
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="${delay}">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong>: ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;
        this.toastContainer.insertAdjacentHTML('beforeend', toastTemplate);
        const newToast = this.toastContainer.lastElementChild;
        const bootstrapToast = new bootstrap.Toast(newToast);
        bootstrapToast.show();
    }
}
