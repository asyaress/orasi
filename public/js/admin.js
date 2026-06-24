/**
 * Admin UI helpers — Orasi Unmul
 */
(function () {
    'use strict';

    /** Fakultas → Prodi cascading select */
    function initFakultasProdiCascade() {
        const fakultasSelect = document.getElementById('fakultas_id');
        const prodiSelect = document.getElementById('prodi_id');
        if (!fakultasSelect || !prodiSelect) return;

        const prodiOptions = Array.from(prodiSelect.querySelectorAll('option[data-fakultas-id]'));
        const initialProdi = prodiSelect.dataset.selected || '';

        function filterProdi(preserveSelection) {
            const fakultasId = fakultasSelect.value;
            const placeholder = prodiSelect.querySelector('option[value=""]');

            if (placeholder) {
                placeholder.textContent = fakultasId ? '— Pilih prodi —' : 'Pilih fakultas terlebih dahulu';
            }

            prodiSelect.disabled = !fakultasId;

            prodiOptions.forEach((opt) => {
                const match = Boolean(fakultasId) && opt.getAttribute('data-fakultas-id') === fakultasId;
                opt.hidden = !match;
                opt.disabled = !match;
            });

            if (!fakultasId) {
                prodiSelect.value = '';
                return;
            }

            const desired = preserveSelection ? (prodiSelect.value || initialProdi) : '';
            const valid = prodiOptions.some(
                (opt) => opt.value === desired && !opt.hidden && !opt.disabled
            );

            prodiSelect.value = valid ? desired : '';
        }

        fakultasSelect.addEventListener('change', () => {
            prodiSelect.dataset.selected = '';
            filterProdi(false);
        });

        filterProdi(true);
    }

    /** Toggle manual snapshot fields */
    function initSnapshotToggle() {
        const toggle = document.getElementById('toggle-snapshot');
        const panel = document.getElementById('snapshot-panel');
        if (!toggle || !panel) return;

        const hasValues = Array.from(panel.querySelectorAll('input')).some(
            (input) => input.value.trim() !== ''
        );
        if (hasValues) {
            panel.classList.remove('d-none');
            toggle.setAttribute('aria-expanded', 'true');
        }

        toggle.addEventListener('click', () => {
            const hidden = panel.classList.toggle('d-none');
            toggle.setAttribute('aria-expanded', hidden ? 'false' : 'true');
        });
    }

    /** Close mobile sidebar after nav click */
    function initSidebarAutoClose() {
        const sidebar = document.getElementById('adminSidebar');
        if (!sidebar || typeof bootstrap === 'undefined') return;

        sidebar.querySelectorAll('.nav-admin .nav-link').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.innerWidth >= 992) return;
                const offcanvas = bootstrap.Offcanvas.getInstance(sidebar);
                if (offcanvas) offcanvas.hide();
            });
        });
    }

    /** Re-enable disabled selects before submit so values are posted */
    function initFormSubmitFix() {
        document.querySelectorAll('form').forEach((form) => {
            form.addEventListener('submit', () => {
                const prodiSelect = document.getElementById('prodi_id');
                if (prodiSelect) prodiSelect.disabled = false;
            });
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        initFakultasProdiCascade();
        initSnapshotToggle();
        initSidebarAutoClose();
        initFormSubmitFix();
    });
})();
