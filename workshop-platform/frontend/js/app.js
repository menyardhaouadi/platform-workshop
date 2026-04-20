
const API = {
  AUTH:     '../services/auth-service',
  WORKSHOP: '../services/workshop-service',
  MESSAGE:  '../services/message-service',
  ADMIN:    '../services/admin-service'
};

let currentUser = null;

function showSpinner() {
  document.getElementById('spinner').classList.remove('hidden');
}

function hideSpinner() {
  document.getElementById('spinner').classList.add('hidden');
}

function showToast(message, type = 'info') {
  const icons = { success: '✓', error: '✕', info: 'ℹ' };
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<span class="t-icon">${icons[type]||icons.info}</span><span>${message}</span>`;
  container.appendChild(toast);
  console.log(`[Toast][${type}] ${message}`);
  setTimeout(() => {
    toast.classList.add('removing');
    setTimeout(() => toast.remove(), 300);
  }, 3500);
}

function callService(url, data) {
  console.log('[Gateway] →', url, data);
  return fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
}

const loginUser = (userData) => {
  console.log('[Auth] Login attempt:', userData.email);
  return callService(`${API.AUTH}/login.php`, userData);
};

const registerUser = (userData) => {
  console.log('[Auth] Register:', userData.email);
  return callService(`${API.AUTH}/register.php`, userData);
};

const logoutUser = () => {
  console.log('[Auth] Logout:', currentUser?.name);
  return callService(`${API.AUTH}/logout.php`, {});
};

const getWorkshops = (userId = 0) => {
  console.log('[Workshop] Fetch all, userId:', userId);
  return callService(`${API.WORKSHOP}/get.php`, { user_id: userId });
};

const registerWorkshop = (userId, workshopId) => {
  console.log('[Workshop] Register user', userId, '→ workshop', workshopId);
  return callService(`${API.WORKSHOP}/register.php`, { user_id: userId, workshop_id: workshopId });
};

const cancelWorkshop = (userId, workshopId) => {
  console.log('[Workshop] Cancel user', userId, '→ workshop', workshopId);
  return callService(`${API.WORKSHOP}/cancel.php`, { user_id: userId, workshop_id: workshopId });
};

const getMyWorkshops = (userId) => callService(`${API.WORKSHOP}/my-workshops.php`, { user_id: userId });

const sendMessage = (data) => {
  console.log('[Message] Send from user:', data.sender_id);
  return callService(`${API.MESSAGE}/send.php`, data);
};

const getMyMessages = (userId) => callService(`${API.MESSAGE}/my-messages.php`, { user_id: userId });

const getAdminWorkshops  = ()          => callService(`${API.ADMIN}/get-workshops.php`, {});
const saveAdminWorkshop  = (data)      => callService(`${API.ADMIN}/save-workshop.php`, data);
const deleteAdminWorkshop = (id)       => callService(`${API.ADMIN}/delete-workshop.php`, { id });
const getAdminMessages   = ()          => callService(`${API.ADMIN}/get-messages.php`, {});
const replyAdminMessage  = (id, reply) => callService(`${API.ADMIN}/reply-message.php`, { id, reply });
const getAdminStats      = ()          => callService(`${API.ADMIN}/stats.php`, {});

function handleResponse(callback) {
  const uid = currentUser?.id || 0;
  callService(`${API.WORKSHOP}/get.php`, { user_id: uid })
    .then(res => res.json())
    .then(data => {
      console.log('[Gateway] Workshops received:', data.workshops?.length || 0);
      callback(data);
    })
    .catch(err => {
      console.error('[Gateway] Error:', err);
      showToast('Failed to load data', 'error');
    });
}

function saveSession(user) {
  localStorage.setItem('wp_user', JSON.stringify(user));
  currentUser = user;
}

function loadSession() {
  try { return JSON.parse(localStorage.getItem('wp_user')); } catch(e) { return null; }
}

function clearSession() {
  localStorage.removeItem('wp_user');
  currentUser = null;
}

function navigateTo() {
  const user = loadSession();
  if (!user) { window.location.href = 'index.html'; return; }
  window.location.href = user.role === 'admin' ? 'admin.html' : 'dashboard.html';
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', { weekday:'short', month:'short', day:'numeric', year:'numeric' });
}

function formatTime(t) {
  const [h,m] = t.split(':');
  const dt = new Date(); dt.setHours(h,m);
  return dt.toLocaleTimeString('en-US', { hour:'numeric', minute:'2-digit', hour12:true });
}

function getWsEmoji(title) {
  const t = title.toLowerCase();
  if (t.includes('security')||t.includes('cyber')) return '🔒';
  if (t.includes('react')||t.includes('web')||t.includes('html')) return '🌐';
  if (t.includes('machine')||t.includes('ml')||t.includes('ai')) return '🤖';
  if (t.includes('docker')||t.includes('kube')||t.includes('devops')) return '🐳';
  if (t.includes('python')||t.includes('data')) return '🐍';
  if (t.includes('cloud')||t.includes('aws')) return '☁️';
  return '💻';
}

document.addEventListener('DOMContentLoaded', () => {
  currentUser = loadSession();
  console.log('[App] Init. User:', currentUser?.name || 'none');
});
