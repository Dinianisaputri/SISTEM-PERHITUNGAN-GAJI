
document.addEventListener('DOMContentLoaded', () => {
    const scrollRestoreKey = 'app:restore-scroll-after-action';
    const pendingScrollRestore = sessionStorage.getItem(scrollRestoreKey);

    if (pendingScrollRestore) {
        sessionStorage.removeItem(scrollRestoreKey);

        try {
            const savedPosition = JSON.parse(pendingScrollRestore);

            if (
                Number.isFinite(savedPosition.x) &&
                Number.isFinite(savedPosition.y) &&
                Date.now() - savedPosition.savedAt < 60_000
            ) {
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        const deleteAnchor = savedPosition.deleteAnchor;
                        const tables = Array.from(document.querySelectorAll('table'));
                        const table = deleteAnchor?.tableId
                            ? document.getElementById(deleteAnchor.tableId)
                            : tables[deleteAnchor?.tableIndex];
                        const rowGroup = Number.isInteger(deleteAnchor?.bodyIndex) &&
                            deleteAnchor.bodyIndex >= 0
                            ? table?.tBodies[deleteAnchor.bodyIndex]
                            : table;
                        const rows = rowGroup ? Array.from(rowGroup.rows) : [];
                        const row = deleteAnchor && rows.length
                            ? rows[Math.min(deleteAnchor.rowIndex, rows.length - 1)]
                            : null;

                        if (row && Number.isFinite(deleteAnchor.viewportTop)) {
                            const targetY =
                                window.scrollY +
                                row.getBoundingClientRect().top -
                                deleteAnchor.viewportTop;
                            window.scrollTo(savedPosition.x, Math.max(0, targetY));
                            return;
                        }

                        window.scrollTo(savedPosition.x, savedPosition.y);
                    });
                });
            }
        } catch (error) {
            console.error('Gagal memulihkan posisi halaman setelah aksi.', error);
        }
    }

    document.addEventListener('submit', (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement) || form.method.toLowerCase() === 'dialog') {
            return;
        }

        const submitterTarget = event.submitter?.formTarget;
        const formTarget = submitterTarget || form.target;

        if (formTarget && formTarget.toLowerCase() !== '_self') {
            return;
        }

        queueMicrotask(() => {
            if (event.defaultPrevented) return;

            const isDelete = form.method.toUpperCase() === 'DELETE' ||
                Array.from(form.elements).some((element) =>
                    element instanceof HTMLInputElement &&
                    element.name === '_method' &&
                    element.value.toUpperCase() === 'DELETE'
                );
            const row = isDelete ? form.closest('tr') : null;
            const table = row?.closest('table');
            const rowGroup = row?.parentElement instanceof HTMLTableSectionElement
                ? row.parentElement
                : table;
            const tableIndex = table
                ? Array.from(document.querySelectorAll('table')).indexOf(table)
                : -1;
            const bodyIndex = table && rowGroup instanceof HTMLTableSectionElement
                ? Array.from(table.tBodies).indexOf(rowGroup)
                : -1;
            const rowIndex = rowGroup && row
                ? Array.from(rowGroup.rows).indexOf(row)
                : -1;

            sessionStorage.setItem(
                scrollRestoreKey,
                JSON.stringify({
                    x: window.scrollX,
                    y: window.scrollY,
                    savedAt: Date.now(),
                    ...(tableIndex >= 0 && rowIndex >= 0
                        ? {
                              deleteAnchor: {
                                  tableId: table.id || null,
                                  tableIndex,
                                  bodyIndex,
                                  rowIndex,
                                  viewportTop: row.getBoundingClientRect().top,
                              },
                          }
                        : {}),
                })
            );
        });
    }, true);

    document.querySelectorAll('[data-bulk-delete-form]').forEach((form) => {
        const selectAll = form.querySelector('[data-bulk-select-all]');
        const selections = Array.from(form.querySelectorAll('[data-bulk-select]'));
        const deleteButton = form.querySelector('[data-bulk-delete-button]');
        const selectionCount = form.querySelector('[data-bulk-selection-count]');

        const updateSelectionState = () => {
            const selectedCount = selections.filter((checkbox) => checkbox.checked).length;

            if (deleteButton) {
                deleteButton.disabled = selectedCount === 0;
            }

            if (selectionCount) {
                selectionCount.textContent = String(selectedCount);
            }

            if (selectAll) {
                selectAll.checked = selections.length > 0 && selectedCount === selections.length;
                selectAll.indeterminate = selectedCount > 0 && selectedCount < selections.length;
            }
        };

        selectAll?.addEventListener('change', () => {
            selections.forEach((checkbox) => {
                checkbox.checked = selectAll.checked;
            });
            updateSelectionState();
        });

        selections.forEach((checkbox) => {
            checkbox.addEventListener('change', updateSelectionState);
        });

        updateSelectionState();
    });

    const themeToggles = document.querySelectorAll('[data-theme-toggle]');

    const updateThemeControls = () => {
        const isDark = document.documentElement.classList.contains('dark');

        themeToggles.forEach((button) => {
            button.setAttribute('aria-pressed', String(isDark));
            button.setAttribute(
                'aria-label',
                isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'
            );
            button.setAttribute(
                'title',
                isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'
            );

            const label = button.querySelector('[data-theme-label]');
            const sun = button.querySelector('[data-theme-sun]');
            const moon = button.querySelector('[data-theme-moon]');

            if (label) {
                label.textContent = isDark ? 'Mode terang' : 'Mode gelap';
            }

            sun?.classList.toggle('hidden', !isDark);
            moon?.classList.toggle('hidden', isDark);
        });
    };

    updateThemeControls();

    themeToggles.forEach((button) => {
        button.addEventListener('click', () => {
            const isDark = !document.documentElement.classList.contains('dark');
            document.documentElement.classList.toggle('dark', isDark);
            document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeControls();
        });
    });

    const navbar = document.querySelector('#desktop-navbar');

    if (!navbar) return;

    const dropdowns = navbar.querySelectorAll(
        'details.nav-dropdown'
    );

    dropdowns.forEach((dropdown) => {
        dropdown.addEventListener('toggle', () => {
            if (!dropdown.open) return;

            dropdowns.forEach((otherDropdown) => {
                if (
                    otherDropdown !== dropdown &&
                    otherDropdown.open
                ) {
                    otherDropdown.open = false;
                }
            });
        });
    });

    document.addEventListener('click', (event) => {
        if (!navbar.contains(event.target)) {
            dropdowns.forEach((dropdown) => {
                dropdown.open = false;
            });
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            dropdowns.forEach((dropdown) => {
                dropdown.open = false;
            });
        }
    });
});
