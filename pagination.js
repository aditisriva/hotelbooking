/**
 * pagination.js — bookHotel Hotel Listing
 * ─────────────────────────────────────────
 * Handles:
 *  • 6 cards per page
 *  • Prev / Next arrows
 *  • Page number buttons with active highlight
 *  • Works with existing filter + sort logic
 *  • Updates "N hotels found" count
 *  • Smooth scroll to top of grid on page change
 */

'use strict';

window.Pagination = (function () {

  /* ── Config ── */
  const PER_PAGE = 6;

  /* ── State ── */
  let currentPage    = 1;
  let filteredCards  = [];   // cards that pass current filters

  /* ── DOM refs (resolved after DOMContentLoaded) ── */
  let grid;
  let paginationList;
  let countEl;

  /* ─────────────────────────────────────────────────
     INIT — called once on DOMContentLoaded
  ───────────────────────────────────────────────── */
  function init() {
    grid           = document.getElementById('hotelGrid');
    paginationList = document.getElementById('paginationList');
    countEl        = document.querySelector('.fw-700.text-dark');

    if (!grid || !paginationList) return;

    // Start with ALL cards as the filtered set
    filteredCards = [...grid.querySelectorAll('[data-location]')];

    render();
  }

  /* ─────────────────────────────────────────────────
     PUBLIC — called by filter/sort in hotels.html
     Pass the array of cards that passed filters.
  ───────────────────────────────────────────────── */
  function setFiltered(cards) {
    filteredCards = cards;
    goToPage(1);   // always reset to page 1 when filter changes
  }

  /* ─────────────────────────────────────────────────
     CORE RENDER
  ───────────────────────────────────────────────── */
  function render() {
    const totalCards = filteredCards.length;
    const totalPages = Math.max(1, Math.ceil(totalCards / PER_PAGE));

    // Guard: if current page is beyond total (e.g. filter reduced results)
    if (currentPage > totalPages) currentPage = totalPages;

    const start = (currentPage - 1) * PER_PAGE;   // inclusive index
    const end   = start + PER_PAGE;                // exclusive index

    // Show / hide every card in the grid
    const allInGrid = [...grid.querySelectorAll('[data-location]')];
    allInGrid.forEach(card => {
      const inFiltered = filteredCards.includes(card);
      const rank       = filteredCards.indexOf(card);
      const onThisPage = rank >= start && rank < end;

      if (inFiltered && onThisPage) {
        card.style.display = '';
        card.classList.remove('card-hidden');
      } else {
        card.style.display = 'none';
        card.classList.add('card-hidden');
      }
    });

    // Update results count label
    if (countEl) {
      countEl.textContent = totalCards + ' hotel' + (totalCards !== 1 ? 's' : '');
    }

    // Rebuild pagination buttons
    buildPagination(totalPages);
  }

  /* ─────────────────────────────────────────────────
     BUILD PAGINATION BUTTONS
  ───────────────────────────────────────────────── */
  function buildPagination(totalPages) {
    paginationList.innerHTML = '';

    // ← Prev
    const prevLi = createPageItem(
      '<i class="bi bi-chevron-left"></i>',
      currentPage === 1,
      false,
      () => goToPage(currentPage - 1)
    );
    paginationList.appendChild(prevLi);

    // Page numbers
    const pages = getPageRange(currentPage, totalPages);
    pages.forEach(p => {
      if (p === '…') {
        // Ellipsis — not clickable
        const li = document.createElement('li');
        li.className = 'page-item disabled';
        li.innerHTML = '<span class="page-link">…</span>';
        paginationList.appendChild(li);
      } else {
        const li = createPageItem(
          String(p),
          false,
          p === currentPage,
          () => goToPage(p)
        );
        paginationList.appendChild(li);
      }
    });

    // Next →
    const nextLi = createPageItem(
      '<i class="bi bi-chevron-right"></i>',
      currentPage === totalPages,
      false,
      () => goToPage(currentPage + 1)
    );
    paginationList.appendChild(nextLi);
  }

  /* ─────────────────────────────────────────────────
     HELPER — build a single <li class="page-item">
  ───────────────────────────────────────────────── */
  function createPageItem(html, isDisabled, isActive, onClick) {
    const li = document.createElement('li');
    li.className = 'page-item'
      + (isDisabled ? ' disabled' : '')
      + (isActive   ? ' active'   : '');

    const a = document.createElement('a');
    a.className  = 'page-link';
    a.href       = '#';
    a.innerHTML  = html;
    a.setAttribute('aria-label', html.replace(/<[^>]+>/g, '') || 'page');

    if (!isDisabled) {
      a.addEventListener('click', e => {
        e.preventDefault();
        onClick();
      });
    }

    li.appendChild(a);
    return li;
  }

  /* ─────────────────────────────────────────────────
     PAGE RANGE — smart window: 1 … 4 5 6 … 9
  ───────────────────────────────────────────────── */
  function getPageRange(current, total) {
    if (total <= 7) {
      // Show all pages
      return Array.from({ length: total }, (_, i) => i + 1);
    }

    const pages = [];
    const delta = 1; // pages on each side of current

    pages.push(1);

    const left  = Math.max(2, current - delta);
    const right = Math.min(total - 1, current + delta);

    if (left > 2)       pages.push('…');
    for (let p = left; p <= right; p++) pages.push(p);
    if (right < total - 1) pages.push('…');

    pages.push(total);
    return pages;
  }

  /* ─────────────────────────────────────────────────
     GO TO PAGE
  ───────────────────────────────────────────────── */
  function goToPage(page) {
    const totalPages = Math.max(1, Math.ceil(filteredCards.length / PER_PAGE));
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    render();

    // Smooth scroll to top of grid
    if (grid) {
      const offset = grid.getBoundingClientRect().top + window.scrollY - 80;
      window.scrollTo({ top: offset, behavior: 'smooth' });
    }
  }

  /* ─────────────────────────────────────────────────
     BOOT
  ───────────────────────────────────────────────── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  /* ── Public API ── */
  return { setFiltered, goToPage };

})();
