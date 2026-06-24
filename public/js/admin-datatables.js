/**
 * DataTables default untuk halaman admin Orasi Unmul
 */
(function () {
    'use strict';

    const LANG_ID = {
        emptyTable: 'Tidak ada data',
        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
        infoEmpty: 'Menampilkan 0 data',
        infoFiltered: '(difilter dari _MAX_ data)',
        lengthMenu: 'Tampilkan _MENU_ data',
        loadingRecords: 'Memuat…',
        processing: 'Memproses…',
        search: 'Cari:',
        zeroRecords: 'Data tidak ditemukan',
        paginate: {
            first: 'Awal',
            last: 'Akhir',
            next: '›',
            previous: '‹',
        },
        buttons: {
            copy: 'Salin',
            copyTitle: 'Salin ke clipboard',
            copySuccess: { 1: '1 baris disalin', _: '%d baris disalin' },
            excel: 'Excel',
            pdf: 'PDF',
            print: 'Cetak',
            colvis: 'Kolom',
        },
    };

    function exportButtons() {
        const exportOpts = { columns: ':visible:not(.no-export)' };

        return [
            { extend: 'copy', text: 'Salin', exportOptions: exportOpts },
            { extend: 'excel', text: 'Excel', exportOptions: exportOpts, title: document.title },
            { extend: 'pdf', text: 'PDF', exportOptions: exportOpts, orientation: 'landscape', pageSize: 'A4', title: document.title },
            { extend: 'print', text: 'Cetak', exportOptions: exportOpts, title: document.title },
            { extend: 'colvis', text: 'Kolom' },
        ];
    }

    function parseOrder(table) {
        const raw = table.dataset.order;
        if (!raw) return [[0, 'asc']];
        try {
            return JSON.parse(raw);
        } catch {
            return [[0, 'asc']];
        }
    }

    function initTable(table) {
        if (table.dataset.dtInitialized === '1') return;
        if (!table.closest('.admin-table-wrap')) {
            const wrap = document.createElement('div');
            wrap.className = 'admin-table-wrap';
            table.parentNode.insertBefore(wrap, table);
            wrap.appendChild(table);
        }

        table.classList.add('admin-table');
        if (table.tBodies[0] && table.tBodies[0].rows.length === 1) {
            const only = table.tBodies[0].rows[0];
            if (only.cells.length === 1 && only.cells[0].colSpan > 1) {
                return;
            }
        }

        const simple = table.classList.contains('admin-datatable-simple');
        const pageLength = parseInt(table.dataset.pageLength || '25', 10);

        const config = {
            responsive: true,
            autoWidth: false,
            pageLength: pageLength,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            order: parseOrder(table),
            language: LANG_ID,
            columnDefs: [
                { orderable: false, targets: 'no-sort' },
                { searchable: false, targets: 'no-search' },
            ],
            dom:
                "<'dt-top row g-2 align-items-center mb-3'<'col-12 col-lg-4'l><'col-12 col-lg-4 text-lg-center'B><'col-12 col-lg-4'f>>" +
                "rt" +
                "<'dt-bottom row g-2 align-items-center mt-3'<'col-12 col-md-6'i><'col-12 col-md-6'p>>",
        };

        if (!simple) {
            config.buttons = exportButtons();
        }

        new DataTable(table, config);
        table.dataset.dtInitialized = '1';
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('table.admin-datatable').forEach(initTable);
    });
})();
