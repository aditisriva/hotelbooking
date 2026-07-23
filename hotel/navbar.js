/**
 * navbar.js — bookHotel
 * Reads localStorage to show either:
 *   - "Login / Sign Up" button   (logged out)
 *   - User avatar + name + dropdown  (logged in)
 *
 * localStorage key: "bh_user"  (JSON: { name, email, avatar })
 */

(function () {
  'use strict';

  /* ── helpers ── */
  function getUser() {
    try {
      // Check if PHP has populated user details
      if (typeof window.PHP_USER !== 'undefined' && window.PHP_USER) {
        localStorage.setItem('bh_user', JSON.stringify(window.PHP_USER));
        return window.PHP_USER;
      }
      // Check if PHP indicates the user is logged out
      if (typeof window.PHP_LOGGED_OUT !== 'undefined' && window.PHP_LOGGED_OUT) {
        localStorage.removeItem('bh_user');
        return null;
      }
      const raw = localStorage.getItem('bh_user');
      return raw ? JSON.parse(raw) : null;
    } catch (_) { return null; }
  }

  function syncUserFromDOM(slot) {
    try {
      const nameEl = slot.querySelector('.bh-user-name, .dropdown-toggle span:last-child');
      const emailEl = slot.querySelector('.bh-dropdown__email');
      if (nameEl && nameEl.textContent.trim()) {
        const user = {
          name: nameEl.textContent.trim(),
          email: emailEl ? emailEl.textContent.trim() : ''
        };
        localStorage.setItem('bh_user', JSON.stringify(user));
      }
    } catch (_) {}
  }

  function logout() {
    localStorage.removeItem('bh_user');
    renderSlot();
    const overlay = document.createElement('div');
    overlay.style.cssText =
      'position:fixed;inset:0;background:#1a56db;z-index:9999;opacity:0;transition:opacity .35s ease;pointer-events:none';
    document.body.appendChild(overlay);
    requestAnimationFrame(() => {
      overlay.style.opacity = '1';
      setTimeout(() => window.location.href = 'do-logout.php', 400);
    });
  }

  /* ── build dropdown HTML ── */
  function buildDropdown(user) {
    // Initials: up to 2 chars from name words
    const initials = user.name
      .split(' ')
      .map(w => w[0])
      .slice(0, 2)
      .join('')
      .toUpperCase();

    return `
      <div class="bh-user-menu" id="bhUserMenu">
        <!-- trigger -->
        <button class="bh-user-trigger" id="bhUserTrigger" aria-haspopup="true" aria-expanded="false" aria-label="User menu">
          <span class="bh-avatar" aria-hidden="true">${initials}</span>
          <span class="bh-user-name">${user.name}</span>
          <i class="bi bi-chevron-down bh-chevron" aria-hidden="true"></i>
        </button>

        <!-- dropdown panel -->
        <div class="bh-dropdown" id="bhDropdown" role="menu">
          <!-- user info header -->
          <div class="bh-dropdown__header">
            <span class="bh-dropdown__avatar" aria-hidden="true">${initials}</span>
            <div class="bh-dropdown__info">
              <span class="bh-dropdown__name">${user.name}</span>
              <span class="bh-dropdown__email">${user.email || ''}</span>
            </div>
          </div>

          <div class="bh-dropdown__divider"></div>

          <a href="profile.php" class="bh-dropdown__item" role="menuitem">
            <i class="bi bi-person-circle"></i>My Profile
          </a>
          <a href="my-bookings.php" class="bh-dropdown__item" role="menuitem">
            <i class="bi bi-receipt-cutoff"></i>My Bookings
          </a>
          <a href="wishlist.php" class="bh-dropdown__item" role="menuitem">
            <i class="bi bi-heart-fill"></i>Wishlist
          </a>

          <div class="bh-dropdown__divider"></div>

          <button class="bh-dropdown__item bh-dropdown__item--logout" id="bhLogoutBtn" role="menuitem">
            <i class="bi bi-box-arrow-right"></i>Logout
          </button>
        </div>
      </div>`;
  }

  /* ── render slot ── */
  function renderSlot() {
    const slot = document.getElementById('navAuthSlot');
    if (!slot) return;

    const user = getUser();

    if (!user) {
      const hasPHPdDropdown = slot.querySelector('.dropdown-menu');
      const hasPHPdLoginBtn = slot.querySelector('a[href="login.php"]');

      if (hasPHPdDropdown) {
        syncUserFromDOM(slot);
        return;
      }

      if (hasPHPdLoginBtn) {
        localStorage.removeItem('bh_user');
        return;
      }

      slot.innerHTML = `<a class="btn btn-outline-warning btn-sm px-3" href="login.php">Login / Sign Up</a>`;
      return;
    }

    const hasPHPdDropdown = slot.querySelector('.dropdown-menu');
    const hasPHPdLoginBtn = slot.querySelector('a[href="login.php"]');

    if (hasPHPdDropdown) {
      return;
    }

    if (hasPHPdLoginBtn) {
      localStorage.removeItem('bh_user');
      return;
    }

    slot.innerHTML = buildDropdown(user);
    attachDropdownEvents();
  }

  /* ── dropdown open / close logic ── */
  function attachDropdownEvents() {
    const trigger  = document.getElementById('bhUserTrigger');
    const dropdown = document.getElementById('bhDropdown');
    const chevron  = trigger?.querySelector('.bh-chevron');
    const logout_btn = document.getElementById('bhLogoutBtn');

    if (!trigger || !dropdown) return;

    function open() {
      dropdown.classList.add('bh-dropdown--open');
      trigger.setAttribute('aria-expanded', 'true');
      if (chevron) chevron.style.transform = 'rotate(180deg)';
    }
    function close() {
      dropdown.classList.remove('bh-dropdown--open');
      trigger.setAttribute('aria-expanded', 'false');
      if (chevron) chevron.style.transform = 'rotate(0deg)';
    }
    function toggle() {
      dropdown.classList.contains('bh-dropdown--open') ? close() : open();
    }

    // Toggle on trigger click
    trigger.addEventListener('click', (e) => {
      e.stopPropagation();
      toggle();
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
      if (!document.getElementById('bhUserMenu')?.contains(e.target)) {
        close();
      }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') close();
    });

    // Logout
    if (logout_btn) {
      logout_btn.addEventListener('click', (e) => {
        e.preventDefault();
        close();
        logout();
      });
    }
  }

  /* ── init on DOM ready ── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', renderSlot);
  } else {
    renderSlot();
  }

})();

