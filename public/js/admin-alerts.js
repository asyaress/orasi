/**
 * SweetAlert2 — flash session & helper global
 */
(function () {
    'use strict';

    if (typeof Swal === 'undefined') return;

    window.AdminAlert = {
        success(message, title) {
            return Swal.fire({
                icon: 'success',
                title: title || 'Berhasil',
                text: message,
                confirmButtonColor: '#f8b803',
            });
        },
        error(message, title) {
            return Swal.fire({
                icon: 'error',
                title: title || 'Gagal',
                text: message,
                confirmButtonColor: '#f8b803',
            });
        },
        warning(message, title) {
            return Swal.fire({
                icon: 'warning',
                title: title || 'Perhatian',
                text: message,
                confirmButtonColor: '#f8b803',
            });
        },
        confirm(options) {
            return Swal.fire({
                icon: 'question',
                title: options.title || 'Konfirmasi',
                text: options.text || '',
                showCancelButton: true,
                confirmButtonText: options.confirmText || 'Ya',
                cancelButtonText: options.cancelText || 'Batal',
                confirmButtonColor: '#f8b803',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
            });
        },
        toast(icon, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
            });
            return Toast.fire({ icon, title: message });
        },
        fromValidation(errors) {
            const list = Object.values(errors).flat().join('\n');
            return this.error(list || 'Validasi gagal.');
        },
        fromFetchError(err, fallback) {
            if (err?.status === 419) {
                return this.error('Sesi habis. Silakan muat ulang halaman dan login kembali.', 'Sesi Berakhir');
            }
            if (err?.status === 403) {
                return this.error('Anda tidak memiliki akses untuk aksi ini.');
            }
            if (err?.status === 404) {
                return this.error('Data tidak ditemukan.');
            }
            if (err?.data?.message) {
                return this.error(err.data.message);
            }
            if (err?.data?.errors) {
                return this.fromValidation(err.data.errors);
            }
            return this.error(fallback || 'Terjadi kesalahan. Coba lagi.');
        },
    };

    document.addEventListener('DOMContentLoaded', () => {
        const body = document.body;
        const success = body.dataset.flashSuccess;
        const warning = body.dataset.flashWarning;
        const error = body.dataset.flashError;

        if (success) AdminAlert.toast('success', success);
        if (warning) AdminAlert.toast('warning', warning);
        if (error) AdminAlert.toast('error', error);
    });
})();
