// Minimal client-side notifications UI logic
(function(){
  const bell = document.getElementById('notif-bell');
  const badge = document.getElementById('notif-badge');
  const panel = document.getElementById('notif-panel');
  const list = document.getElementById('notif-list');
  const empty = document.getElementById('notif-empty');
  const markAllBtn = document.getElementById('notif-mark-all');
  if (!bell || !badge || !panel) return;

  let open = false;

  let lastCount = null;

  function setBadge(n){
    if (!badge) return;
    if (n > 0) { badge.style.display = 'inline-block'; badge.textContent = String(n); }
    else { badge.style.display = 'none'; }
  }

  function pulseBadge(){
    if (!badge) return;
    badge.classList.remove('pulse');
    // force reflow to restart animation
    // eslint-disable-next-line no-unused-expressions
    void badge.offsetWidth;
    badge.classList.add('pulse');
    setTimeout(() => badge.classList.remove('pulse'), 650);
  }

  async function fetchCount(){
    try {
      const res = await fetch('/api/notifications/count');
      const data = await res.json();
      const count = data.count || 0;
      if (lastCount !== null && count > lastCount) {
        pulseBadge();
      }
      lastCount = count;
      setBadge(count);
    } catch(e) { /* noop */ }
  }

  function renderList(items){
    list.innerHTML = '';
    if (!items || !items.length){ empty.style.display = 'block'; return; }
    empty.style.display = 'none';
    items.forEach(n => {
      const row = document.createElement('div');
      row.className = 'notif-row' + (n.is_read ? '' : ' notif-row-unread');
      row.innerHTML = `
        <div class="notif-row-title">${escapeHtml(n.title || '')}</div>
        <div class="notif-row-message">${escapeHtml(n.message || '')}</div>
      `;
      row.addEventListener('click', async () => {
        try { await fetch(`/api/notifications/mark-read/${n.id_notification}`); } catch(e){}
        if (n.link) window.location.href = n.link;
        else { row.style.background = 'transparent'; fetchCount(); }
      });
      list.appendChild(row);
    });
  }

  async function fetchList(){
    try {
      const res = await fetch('/api/notifications?limit=20');
      const data = await res.json();
      renderList(data);
    } catch(e){ /* noop */ }
  }

  function toggle(){
    open = !open;
    panel.style.display = open ? 'block' : 'none';
    if (open) { fetchList(); }
  }

  function outsideClick(e){
    if (!open) return;
    if (!panel.contains(e.target) && !bell.contains(e.target)) {
      open = false; panel.style.display = 'none';
    }
  }

  function escapeHtml(s){
    return String(s).replace(/[&<>"]+/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
  }

  bell.addEventListener('click', toggle);
  document.addEventListener('click', outsideClick);
  if (markAllBtn){
    markAllBtn.addEventListener('click', async () => {
      try { await fetch('/api/notifications/mark-all-read'); } catch(e){}
      fetchCount(); fetchList();
    });
  }

  // poll count every 30s
  fetchCount();
  setInterval(fetchCount, 30000);
})();
