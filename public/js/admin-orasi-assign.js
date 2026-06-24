/**
 * Drag & drop penugasan Guru Besar ke Orasi Ilmiah
 */
(function () {
    'use strict';

    const root = document.getElementById('orasi-assign-app');
    if (!root || typeof Sortable === 'undefined') return;

    const attachUrl = root.dataset.attachUrl;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const countBadge = document.getElementById('assigned-count');
    const listAvailable = document.getElementById('list-available');
    const listAssigned = document.getElementById('list-assigned');
    const searchInput = document.getElementById('guru-search');
    const emptyAvailable = document.getElementById('empty-available');
    const emptyAssigned = document.getElementById('empty-assigned');

    let busy = false;

    function updateCounts() {
        const a = listAvailable.querySelectorAll('.guru-assign-card:not(.d-none)').length;
        const b = listAssigned.querySelectorAll('.guru-assign-card').length;
        if (countBadge) countBadge.textContent = `${b} orang`;
        if (emptyAvailable) emptyAvailable.classList.toggle('d-none', a > 0);
        if (emptyAssigned) emptyAssigned.classList.toggle('d-none', b > 0);
    }

    function filterSearch() {
        const q = (searchInput?.value || '').toLowerCase().trim();
        listAvailable.querySelectorAll('.guru-assign-card').forEach((card) => {
            const nama = (card.dataset.nama || '').toLowerCase();
            const text = card.textContent.toLowerCase();
            card.classList.toggle('d-none', q !== '' && !nama.includes(q) && !text.includes(q));
        });
        updateCounts();
    }

    async function apiFetch(url, method, body) {
        const headers = {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
        };
        const opts = { method, headers, credentials: 'same-origin' };
        if (body) {
            headers['Content-Type'] = 'application/json';
            opts.body = JSON.stringify(body);
        }
        const res = await fetch(url, opts);
        let data = {};
        try {
            data = await res.json();
        } catch {
            data = { message: 'Respons server tidak valid.' };
        }
        if (!res.ok) {
            const err = new Error(data.message || 'Request gagal');
            err.status = res.status;
            err.data = data;
            throw err;
        }
        return data;
    }

    function revertMove(evt) {
        const { item, from, to, oldIndex } = evt;
        if (oldIndex >= 0 && from) {
            from.insertBefore(item, from.children[oldIndex] || null);
        } else if (to && from) {
            from.appendChild(item);
        }
        updateCounts();
    }

    async function onAddToAssigned(evt) {
        const card = evt.item;
        const id = parseInt(card.dataset.id, 10);
        if (!id || busy) {
            revertMove(evt);
            return;
        }

        busy = true;
        card.classList.add('guru-assign-loading');

        try {
            const data = await apiFetch(attachUrl, 'POST', { guru_besar_id: id });
            if (data.guru?.html_assigned) {
                const temp = document.createElement('div');
                temp.innerHTML = data.guru.html_assigned.trim();
                const fresh = temp.firstElementChild;
                card.replaceWith(fresh);
            }
            updateCounts();
            if (window.AdminAlert) {
                AdminAlert.toast(data.success ? 'success' : 'warning', data.message);
            }
        } catch (err) {
            revertMove(evt);
            if (window.AdminAlert) AdminAlert.fromFetchError(err, 'Gagal menugaskan guru besar.');
        } finally {
            busy = false;
            listAssigned.querySelector(`[data-id="${id}"]`)?.classList.remove('guru-assign-loading');
        }
    }

    async function onAddToAvailable(evt) {
        const card = evt.item;
        const id = parseInt(card.dataset.id, 10);
        const detachUrl = card.dataset.detachUrl;
        if (!detachUrl) {
            revertMove(evt);
            if (window.AdminAlert) AdminAlert.error('URL lepas tidak valid. Muat ulang halaman.');
            return;
        }

        if (!id || busy) {
            revertMove(evt);
            return;
        }

        const ok = window.AdminAlert
            ? (await AdminAlert.confirm({
                  title: 'Lepas dari orasi?',
                  text: 'Guru besar tetap ada di master data, hanya tidak ikut orasi tahun ini.',
                  confirmText: 'Ya, lepas',
              })).isConfirmed
            : confirm('Lepas dari orasi ini?');

        if (!ok) {
            revertMove(evt);
            return;
        }

        busy = true;
        card.classList.add('guru-assign-loading');

        try {
            const data = await apiFetch(detachUrl, 'DELETE');
            if (data.guru?.html_available) {
                const temp = document.createElement('div');
                temp.innerHTML = data.guru.html_available.trim();
                const fresh = temp.firstElementChild;
                card.replaceWith(fresh);
            }
            updateCounts();
            if (window.AdminAlert) AdminAlert.toast('success', data.message);
        } catch (err) {
            revertMove(evt);
            if (window.AdminAlert) AdminAlert.fromFetchError(err, 'Gagal melepas guru besar.');
        } finally {
            busy = false;
        }
    }

    const sortableOpts = {
        group: 'guru-assign',
        animation: 180,
        easing: 'cubic-bezier(0.2, 0, 0, 1)',
        ghostClass: 'guru-assign-ghost',
        chosenClass: 'guru-assign-chosen',
        dragClass: 'guru-assign-drag',
        delay: 80,
        delayOnTouchOnly: true,
        filter: '.guru-assign-actions a, .guru-assign-actions button',
        preventOnFilter: true,
    };

    Sortable.create(listAvailable, {
        ...sortableOpts,
        sort: false,
        onAdd(evt) {
            if (evt.from === listAssigned) onAddToAvailable(evt);
        },
    });

    Sortable.create(listAssigned, {
        ...sortableOpts,
        onAdd(evt) {
            if (evt.from === listAvailable) onAddToAssigned(evt);
        },
    });

    searchInput?.addEventListener('input', filterSearch);

    document.getElementById('btn-move-selected')?.addEventListener('click', async () => {
        const selected = [...listAvailable.querySelectorAll('.guru-assign-card.is-selected:not(.d-none)')];
        if (selected.length === 0) {
            if (window.AdminAlert) AdminAlert.warning('Klik kartu guru besar di kolom kiri untuk memilih, lalu tekan tombol ini.');
            return;
        }
        if (busy) return;
        busy = true;
        let ok = 0;
        try {
            for (const card of selected) {
                const id = parseInt(card.dataset.id, 10);
                card.classList.add('guru-assign-loading');
                try {
                    const data = await apiFetch(attachUrl, 'POST', { guru_besar_id: id });
                    if (data.guru?.html_assigned) {
                        const temp = document.createElement('div');
                        temp.innerHTML = data.guru.html_assigned.trim();
                        listAssigned.appendChild(temp.firstElementChild);
                        card.remove();
                        ok++;
                    }
                } catch (err) {
                    if (window.AdminAlert) AdminAlert.fromFetchError(err);
                }
            }
            updateCounts();
            if (ok > 0 && window.AdminAlert) {
                AdminAlert.toast('success', `${ok} guru besar berhasil ditugaskan.`);
            }
        } finally {
            busy = false;
        }
    });

    listAvailable.addEventListener('click', (e) => {
        const card = e.target.closest('.guru-assign-card');
        if (!card || e.target.closest('a, button')) return;
        card.classList.toggle('is-selected');
    });

    listAvailable.addEventListener('dblclick', (e) => {
        const card = e.target.closest('.guru-assign-card');
        if (!card || card.classList.contains('d-none')) return;
        listAssigned.appendChild(card);
        onAddToAssigned({ item: card, from: listAvailable, to: listAssigned, oldIndex: -1 });
    });

    updateCounts();
})();
