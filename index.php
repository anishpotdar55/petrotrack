<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>PetroTrack · Oil & Gas Inventory</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#f5f2ec;--bg2:#ede9e1;--surface:#fff;--border:#d8d2c8;
  --text:#1a1714;--text2:#5a5550;--text3:#9a948e;
  --amber:#c4720a;--amber-light:#fdf3e3;--amber-mid:#f0a832;
  --red:#c0392b;--red-light:#fdf0ee;
  --green:#1e7b5e;--green-light:#eaf4f0;
  --blue:#1d4ed8;--blue-light:#eff6ff;
  --shadow:0 1px 3px rgba(0,0,0,.08),0 4px 16px rgba(0,0,0,.04);
  --shadow-lg:0 8px 32px rgba(0,0,0,.1);
}
body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;font-size:14px}
.sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:var(--text);color:#fff;display:flex;flex-direction:column;z-index:100}
.logo{padding:24px 20px 20px;border-bottom:1px solid rgba(255,255,255,.08)}
.logo-mark{display:flex;align-items:center;gap:10px;margin-bottom:4px}
.logo-icon{width:36px;height:36px;background:var(--amber-mid);border-radius:8px;display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-size:13px;font-weight:800;color:#000}
.logo-name{font-family:'Syne',sans-serif;font-size:17px;font-weight:800;color:#fff}
.logo-tagline{font-size:10px;color:rgba(255,255,255,.35);font-family:'DM Mono',monospace;letter-spacing:.5px}
.nav{padding:16px 12px;flex:1}
.nav-section{font-size:9px;font-family:'DM Mono',monospace;color:rgba(255,255,255,.25);letter-spacing:1.5px;text-transform:uppercase;padding:12px 8px 6px;margin-top:8px}
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:8px;cursor:pointer;color:rgba(255,255,255,.55);font-size:13px;font-weight:500;transition:all .18s;margin-bottom:2px;border:none;background:none;width:100%;text-align:left}
.nav-item:hover{background:rgba(255,255,255,.07);color:#fff}
.nav-item.active{background:var(--amber-mid);color:#000;font-weight:600}
.nav-badge{margin-left:auto;background:var(--red);color:#fff;font-size:9px;font-family:'DM Mono',monospace;padding:2px 6px;border-radius:10px}
.sidebar-footer{padding:16px;border-top:1px solid rgba(255,255,255,.08)}
.user-card{display:flex;align-items:center;gap:10px}
.user-avatar{width:32px;height:32px;border-radius:50%;background:var(--amber-mid);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#000}
.user-name{font-size:12px;font-weight:500;color:#fff}
.user-role{font-size:10px;color:rgba(255,255,255,.35);font-family:'DM Mono',monospace}

.main{margin-left:220px;min-height:100vh}
.topbar{background:var(--surface);border-bottom:1px solid var(--border);padding:0 28px;height:56px;display:flex;align-items:center;gap:16px;position:sticky;top:0;z-index:50}
.topbar-title{font-family:'Syne',sans-serif;font-size:15px;font-weight:700}
.topbar-breadcrumb{font-size:12px;color:var(--text3);font-family:'DM Mono',monospace}
.topbar-right{margin-left:auto;display:flex;align-items:center;gap:12px}
.live-badge{display:flex;align-items:center;gap:6px;background:var(--green-light);border:1px solid rgba(30,123,94,.2);border-radius:20px;padding:4px 10px}
.live-dot{width:6px;height:6px;border-radius:50%;background:var(--green);animation:blink 1.5s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
.live-text{font-size:11px;font-weight:500;color:var(--green);font-family:'DM Mono',monospace}
.topbar-btn{padding:7px 14px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .15s}
.btn-primary{background:var(--text);color:#fff;border:none}
.btn-primary:hover{background:#2d2926}
.btn-outline{background:none;border:1px solid var(--border);color:var(--text2)}
.btn-outline:hover{border-color:var(--text2)}
.content{padding:28px}
.page{display:none}.page.active{display:block}

.kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px}
.kpi-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:18px 20px;box-shadow:var(--shadow);position:relative;overflow:hidden}
.kpi-accent{position:absolute;top:0;left:0;right:0;height:3px;border-radius:12px 12px 0 0}
.kpi-label{font-size:11px;color:var(--text3);font-family:'DM Mono',monospace;text-transform:uppercase;letter-spacing:.8px;margin-bottom:10px}
.kpi-value{font-family:'Syne',sans-serif;font-size:28px;font-weight:800;color:var(--text);line-height:1}
.kpi-sub{font-size:11px;color:var(--text3);margin-top:6px}
.kpi-tag{position:absolute;top:16px;right:16px;font-size:10px;font-weight:600;padding:3px 8px;border-radius:20px;font-family:'DM Mono',monospace}
.tag-ok{background:var(--green-light);color:var(--green)}
.tag-warn{background:var(--amber-light);color:var(--amber)}
.tag-crit{background:var(--red-light);color:var(--red)}

.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
.panel{background:var(--surface);border:1px solid var(--border);border-radius:12px;box-shadow:var(--shadow);overflow:hidden;margin-bottom:16px}
.panel-header{padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;background:rgba(245,242,236,.4)}
.ph-title{font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:var(--text);flex:1}
.ph-action{font-size:11px;font-weight:500;color:var(--amber);cursor:pointer;font-family:'DM Mono',monospace;border:none;background:none;padding:4px 8px;border-radius:5px}
.ph-action:hover{background:var(--amber-light)}

.data-table{width:100%;border-collapse:collapse}
.data-table th{font-size:10px;font-family:'DM Mono',monospace;text-transform:uppercase;letter-spacing:1px;color:var(--text3);padding:10px 16px;text-align:left;border-bottom:1px solid var(--border)}
.data-table td{padding:11px 16px;border-bottom:1px solid rgba(216,210,200,.4);font-size:13px}
.data-table tr:last-child td{border-bottom:none}
.data-table tr:hover td{background:rgba(244,241,235,.5)}
.td-bold{font-weight:600;color:var(--text)}
.td-mono{font-family:'DM Mono',monospace;font-size:11px;color:var(--text3)}

.stock-wrap{display:flex;align-items:center;gap:8px}
.stock-track{flex:1;height:6px;background:var(--bg2);border-radius:3px;overflow:hidden;max-width:80px}
.stock-fill{height:100%;border-radius:3px}
.stock-pct{font-family:'DM Mono',monospace;font-size:10px;color:var(--text3);width:28px;text-align:right}

.pill{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:20px;font-size:10px;font-weight:600;font-family:'DM Mono',monospace;white-space:nowrap}
.pill-green{background:var(--green-light);color:var(--green)}
.pill-amber{background:var(--amber-light);color:var(--amber)}
.pill-red{background:var(--red-light);color:var(--red)}
.pill-blue{background:var(--blue-light);color:var(--blue)}
.pill-dot{width:5px;height:5px;border-radius:50%;background:currentColor}

.activity-item{display:flex;align-items:flex-start;gap:12px;padding:12px 20px;border-bottom:1px solid rgba(216,210,200,.4)}
.activity-item:last-child{border-bottom:none}
.act-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px}
.act-in{background:var(--green-light)}
.act-out{background:var(--red-light)}
.act-transfer{background:var(--blue-light)}
.act-body{flex:1}
.act-title{font-size:13px;font-weight:500;color:var(--text);margin-bottom:2px}
.act-meta{font-size:11px;color:var(--text3);font-family:'DM Mono',monospace}
.act-qty{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;text-align:right;flex-shrink:0;padding-top:2px}
.qty-pos{color:var(--green)}.qty-neg{color:var(--red)}

.tank-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;padding:20px}
.tank-card{border:1px solid var(--border);border-radius:10px;padding:14px;cursor:pointer;transition:all .2s}
.tank-card:hover{border-color:var(--amber-mid);box-shadow:0 4px 16px rgba(196,114,10,.1)}
.tank-name{font-family:'Syne',sans-serif;font-size:13px;font-weight:700;margin-bottom:2px}
.tank-loc{font-size:10px;color:var(--text3);font-family:'DM Mono',monospace;margin-bottom:12px}
.tank-visual{width:100%;height:60px;background:var(--bg2);border-radius:6px;overflow:hidden;position:relative;margin-bottom:10px}
.tank-fill-bar{position:absolute;bottom:0;left:0;right:0;transition:height 1s cubic-bezier(.34,1.56,.64,1)}
.tank-fill-text{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-family:'Syne',sans-serif;font-size:16px;font-weight:800;color:var(--text)}
.tank-stats{display:flex;justify-content:space-between;font-size:10px;font-family:'DM Mono',monospace;color:var(--text3)}

.form-group{margin-bottom:16px}
.form-label{font-size:11px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.6px;font-family:'DM Mono',monospace;margin-bottom:6px;display:block}
.form-input,.form-select{width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;font-family:'DM Sans',sans-serif;font-size:13px;color:var(--text);background:var(--surface);outline:none;transition:border .15s}
.form-input:focus,.form-select:focus{border-color:var(--amber-mid)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.submit-btn{width:100%;padding:11px;background:var(--text);color:#fff;border:none;border-radius:9px;font-family:'Syne',sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:all .2s;margin-top:4px}
.submit-btn:hover{background:var(--amber);transform:translateY(-1px);box-shadow:0 4px 12px rgba(196,114,10,.3)}
.submit-btn:disabled{opacity:.5;cursor:not-allowed;transform:none}

.modal-overlay{position:fixed;inset:0;background:rgba(26,23,20,.5);z-index:200;display:none;align-items:center;justify-content:center;backdrop-filter:blur(4px)}
.modal-overlay.open{display:flex}
.modal{background:var(--surface);border-radius:14px;width:420px;box-shadow:var(--shadow-lg);overflow:hidden;animation:modalIn .25s ease}
@keyframes modalIn{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}
.modal-header{padding:18px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.modal-title{font-family:'Syne',sans-serif;font-size:15px;font-weight:700}
.modal-close{background:none;border:none;cursor:pointer;font-size:20px;color:var(--text3);line-height:1;padding:2px 6px;border-radius:4px}
.modal-body{padding:20px}
.modal-footer{padding:14px 20px;border-top:1px solid var(--border);display:flex;gap:8px;justify-content:flex-end}
.cancel-btn{padding:8px 16px;border:1px solid var(--border);border-radius:7px;background:none;color:var(--text2);cursor:pointer;font-family:'DM Sans',sans-serif;font-size:13px}

.toast-container{position:fixed;bottom:24px;right:24px;z-index:999;display:flex;flex-direction:column;gap:8px}
.toast{background:var(--text);color:#fff;padding:12px 16px;border-radius:10px;font-size:13px;box-shadow:var(--shadow-lg);display:flex;align-items:center;gap:10px;animation:slideIn .3s ease;max-width:300px;border-left:3px solid var(--amber-mid)}
.toast-success{border-left-color:var(--green)}.toast-error{border-left-color:var(--red)}
@keyframes slideIn{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:translateX(0)}}

.loading{text-align:center;padding:40px;color:var(--text3);font-family:'DM Mono',monospace;font-size:12px}
::-webkit-scrollbar{width:4px}::-webkit-scrollbar-thumb{background:var(--border);border-radius:4px}
</style>
</head>
<body>

<nav class="sidebar">
  <div class="logo">
    <div class="logo-mark"><div class="logo-icon">PT</div><div class="logo-name">PetroTrack</div></div>
    <div class="logo-tagline">OIL & GAS IMS v2.4</div>
  </div>
  <div class="nav">
    <div class="nav-section">Operations</div>
    <button class="nav-item active" onclick="showPage('dashboard',this)">
      <svg width="15" height="15" fill="none" viewBox="0 0 16 16"><rect x="1" y="1" width="6" height="6" rx="1.5" fill="currentColor"/><rect x="9" y="1" width="6" height="6" rx="1.5" fill="currentColor" opacity=".5"/><rect x="1" y="9" width="6" height="6" rx="1.5" fill="currentColor" opacity=".5"/><rect x="9" y="9" width="6" height="6" rx="1.5" fill="currentColor" opacity=".5"/></svg>
      Dashboard
    </button>
    <button class="nav-item" onclick="showPage('inventory',this)">
      <svg width="15" height="15" fill="none" viewBox="0 0 16 16"><rect x="2" y="3" width="12" height="10" rx="1.5" stroke="currentColor" stroke-width="1.2"/><path d="M5 7h6M5 9.5h4" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
      Inventory
    </button>
    <button class="nav-item" onclick="showPage('tanks',this)">
      <svg width="15" height="15" fill="none" viewBox="0 0 16 16"><rect x="3" y="2" width="10" height="12" rx="2" stroke="currentColor" stroke-width="1.2"/><path d="M3 6h10" stroke="currentColor" stroke-width="1.2"/></svg>
      Storage Tanks
    </button>
    <button class="nav-item" onclick="showPage('transactions',this)">
      <svg width="15" height="15" fill="none" viewBox="0 0 16 16"><path d="M2 4h12M2 8h8M2 12h5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
      Transactions
    </button>
    <div class="nav-section">Management</div>
    <button class="nav-item" onclick="showPage('alerts',this)">
      <svg width="15" height="15" fill="none" viewBox="0 0 16 16"><path d="M8 2a5 5 0 015 5v2l1 2H2l1-2V7a5 5 0 015-5z" stroke="currentColor" stroke-width="1.2"/><path d="M6.5 13a1.5 1.5 0 003 0" stroke="currentColor" stroke-width="1.2"/></svg>
      Alerts <span class="nav-badge" id="alert-count">0</span>
    </button>
  </div>
  <div class="sidebar-footer">
    <div class="user-card">
      <div class="user-avatar">RA</div>
      <div><div class="user-name">Rahul Admin</div><div class="user-role">ops.manager</div></div>
    </div>
  </div>
</nav>

<div class="main">
  <div class="topbar">
    <div>
      <div class="topbar-title" id="page-title">Operations Dashboard</div>
      <div class="topbar-breadcrumb" id="page-bread">Home / Dashboard</div>
    </div>
    <div class="topbar-right">
      <div class="live-badge"><div class="live-dot"></div><span class="live-text">CONNECTED</span></div>
      <button class="topbar-btn btn-outline" onclick="openModal('receiveModal')">+ Receive Stock</button>
      <button class="topbar-btn btn-primary" onclick="openModal('dispatchModal')">↑ Dispatch</button>
    </div>
  </div>

  <div class="content">

    <!-- DASHBOARD -->
    <div class="page active" id="page-dashboard">
      <div class="kpi-grid">
        <div class="kpi-card">
          <div class="kpi-accent" style="background:linear-gradient(90deg,var(--amber-mid),#f5c842)"></div>
          <div class="kpi-label">Total Crude Stock</div>
          <div class="kpi-value" id="kpi-crude">—</div>
          <div class="kpi-sub">barrels · Tank Farm</div>
          <div class="kpi-tag tag-ok">LIVE</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-accent" style="background:linear-gradient(90deg,var(--green),#2ec4b6)"></div>
          <div class="kpi-label">Refined Products</div>
          <div class="kpi-value" id="kpi-refined">—</div>
          <div class="kpi-sub">metric tons · storage</div>
          <div class="kpi-tag tag-ok">LIVE</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-accent" style="background:linear-gradient(90deg,#3b82f6,#60a5fa)"></div>
          <div class="kpi-label">Total Transactions</div>
          <div class="kpi-value" id="kpi-txn">—</div>
          <div class="kpi-sub">operations logged today</div>
          <div class="kpi-tag tag-warn">DB</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-accent" style="background:linear-gradient(90deg,var(--red),#e05c6c)"></div>
          <div class="kpi-label">Stock Alerts</div>
          <div class="kpi-value" id="kpi-alerts">—</div>
          <div class="kpi-sub">products below reorder</div>
          <div class="kpi-tag tag-crit" id="kpi-alert-tag">LIVE</div>
        </div>
      </div>
      <div class="grid-2">
        <div class="panel">
          <div class="panel-header"><span class="ph-title">Recent Activity</span><button class="ph-action" onclick="showPage('transactions',document.querySelectorAll('.nav-item')[3])">View All →</button></div>
          <div id="activity-feed"><div class="loading">Loading from database...</div></div>
        </div>
        <div class="panel">
          <div class="panel-header"><span class="ph-title">Inventory Snapshot</span></div>
          <div id="snap-list"><div class="loading">Loading...</div></div>
        </div>
      </div>
    </div>

    <!-- INVENTORY -->
    <div class="page" id="page-inventory">
      <div class="panel">
        <div class="panel-header">
          <span class="ph-title">Full Inventory Register</span>
          <button class="ph-action" onclick="openModal('receiveModal')">+ Receive Stock</button>
        </div>
        <div id="inv-table-wrap"><div class="loading">Loading from database...</div></div>
      </div>
    </div>

    <!-- TANKS -->
    <div class="page" id="page-tanks">
      <div class="panel">
        <div class="panel-header"><span class="ph-title">Storage Tank Farm</span></div>
        <div class="tank-grid" id="tank-grid"><div class="loading" style="grid-column:1/-1">Loading tanks...</div></div>
      </div>
    </div>

    <!-- TRANSACTIONS -->
    <div class="page" id="page-transactions">
      <div class="panel">
        <div class="panel-header"><span class="ph-title">Transaction Log</span><button class="ph-action" onclick="loadTransactions()">↻ Refresh</button></div>
        <div id="txn-table-wrap"><div class="loading">Loading from database...</div></div>
      </div>
      <div class="panel">
        <div class="panel-header"><span class="ph-title">Audit Log <span style="font-size:10px;font-family:'DM Mono',monospace;color:var(--text3);font-weight:400">(auto-generated by DB trigger)</span></span><button class="ph-action" onclick="loadAudit()">↻ Refresh</button></div>
        <div id="audit-table-wrap"><div class="loading">Loading audit log...</div></div>
      </div>
    </div>

    <!-- ALERTS -->
    <div class="page" id="page-alerts">
      <div class="panel">
        <div class="panel-header"><span class="ph-title">Stock Alerts</span></div>
        <div id="alerts-list"><div class="loading">Checking inventory levels...</div></div>
      </div>
    </div>

  </div>
</div>

<!-- RECEIVE MODAL -->
<div class="modal-overlay" id="receiveModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Receive Stock Inbound</div>
      <button class="modal-close" onclick="closeModal('receiveModal')">×</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Product</label>
        <select class="form-select" id="recv-product">
          <option value="CRUDE-001">Crude Oil (Brent)</option>
          <option value="CRUDE-002">Crude Oil (WTI)</option>
          <option value="DIST-001">Diesel EN590</option>
          <option value="DIST-002">Aviation Fuel JA1</option>
          <option value="GAS-001">LPG Propane Mix</option>
          <option value="OIL-001">Lubricant Base Oil</option>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Quantity</label>
          <input class="form-input" type="number" id="recv-qty" placeholder="0" min="1"/>
        </div>
        <div class="form-group">
          <label class="form-label">Source</label>
          <select class="form-select" id="recv-src">
            <option>PIPELINE-A</option><option>TANKER-B7</option>
            <option>REFINERY-1</option><option>PLANT-3</option>
          </select>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="cancel-btn" onclick="closeModal('receiveModal')">Cancel</button>
      <button class="submit-btn" id="recv-btn" style="width:auto;padding:8px 20px;margin:0" onclick="receiveStock()">Confirm Receipt</button>
    </div>
  </div>
</div>

<!-- DISPATCH MODAL -->
<div class="modal-overlay" id="dispatchModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Create Dispatch</div>
      <button class="modal-close" onclick="closeModal('dispatchModal')">×</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Product</label>
        <select class="form-select" id="disp-product">
          <option value="CRUDE-001">Crude Oil (Brent)</option>
          <option value="CRUDE-002">Crude Oil (WTI)</option>
          <option value="DIST-001">Diesel EN590</option>
          <option value="DIST-002">Aviation Fuel JA1</option>
          <option value="GAS-001">LPG Propane Mix</option>
          <option value="OIL-001">Lubricant Base Oil</option>
        </select>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Quantity</label>
          <input class="form-input" type="number" id="disp-qty" placeholder="0" min="1"/>
        </div>
        <div class="form-group">
          <label class="form-label">Destination</label>
          <input class="form-input" type="text" id="disp-dest" placeholder="e.g. Depot South"/>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="cancel-btn" onclick="closeModal('dispatchModal')">Cancel</button>
      <button class="submit-btn" id="disp-btn" style="width:auto;padding:8px 20px;margin:0;background:var(--red)" onclick="dispatchStock()">Confirm Dispatch</button>
    </div>
  </div>
</div>

<div class="toast-container" id="toasts"></div>

<script>
const API = 'api.php';
const barColors = { 'Crude':'#f0a832', 'Refined':'#1e7b5e', 'Gas':'#1d4ed8' };
let allInventory = [];

// ── FETCH HELPERS ──────────────────────────────────────────────────────────────
async function api(action, postData=null) {
  const url = `${API}?action=${action}`;
  const opts = postData
    ? { method:'POST', body: new URLSearchParams({action, ...postData}) }
    : { method:'GET' };
  const res = await fetch(url, opts);
  return res.json();
}

// ── LOAD INVENTORY ─────────────────────────────────────────────────────────────
async function loadInventory() {
  allInventory = await api('get_inventory');

  // KPIs
  const crude   = allInventory.filter(i=>i.product_type==='Crude').reduce((s,i)=>s+parseFloat(i.quantity),0);
  const refined = allInventory.filter(i=>i.product_type==='Refined').reduce((s,i)=>s+parseFloat(i.quantity),0);
  const alerts  = allInventory.filter(i=>parseFloat(i.quantity)<parseFloat(i.reorder_level)).length;

  document.getElementById('kpi-crude').textContent    = Math.round(crude).toLocaleString();
  document.getElementById('kpi-refined').textContent  = Math.round(refined).toLocaleString();
  document.getElementById('kpi-alerts').textContent   = alerts;
  document.getElementById('alert-count').textContent  = alerts;
  if (alerts>0) document.getElementById('kpi-alert-tag').textContent = 'ACTION';

  renderSnapList();
  renderFullInvTable();
  renderAlerts();
}

// ── SNAPSHOT LIST ──────────────────────────────────────────────────────────────
function renderSnapList() {
  document.getElementById('snap-list').innerHTML = allInventory.map(item => {
    const pct  = Math.min(100, Math.round(item.quantity / item.capacity * 100));
    const low  = parseFloat(item.quantity) < parseFloat(item.reorder_level);
    const col  = low ? 'var(--red)' : barColors[item.product_type]||'#888';
    const pill = low
      ? '<span class="pill pill-red"><span class="pill-dot"></span>Low</span>'
      : '<span class="pill pill-green"><span class="pill-dot"></span>OK</span>';
    return `<div class="activity-item">
      <div>
        <div style="font-size:13px;font-weight:500">${item.product_name}</div>
        <div style="font-size:10px;font-family:'DM Mono',monospace;color:var(--text3)">${item.product_id} · ${item.tank_id}</div>
      </div>
      <div style="margin-left:auto;display:flex;align-items:center;gap:10px">
        <div class="stock-wrap">
          <div class="stock-track"><div class="stock-fill" style="width:${pct}%;background:${col}"></div></div>
          <span class="stock-pct">${pct}%</span>
        </div>
        ${pill}
        <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;min-width:70px;text-align:right">${parseFloat(item.quantity).toLocaleString()} ${item.unit}</span>
      </div>
    </div>`;
  }).join('');
}

// ── FULL INVENTORY TABLE ───────────────────────────────────────────────────────
function renderFullInvTable() {
  document.getElementById('inv-table-wrap').innerHTML = `
    <table class="data-table">
      <thead><tr><th>ID</th><th>Product</th><th>Type</th><th>Quantity</th><th>Unit</th><th>Tank</th><th>Source</th><th>Reorder Level</th><th>Status</th><th>Last Updated</th></tr></thead>
      <tbody>${allInventory.map(item => {
        const low = parseFloat(item.quantity) < parseFloat(item.reorder_level);
        const pill = low
          ? '<span class="pill pill-red"><span class="pill-dot"></span>Low Stock</span>'
          : '<span class="pill pill-green"><span class="pill-dot"></span>OK</span>';
        const typeClass = item.product_type==='Crude'?'pill-amber':item.product_type==='Gas'?'pill-blue':'pill-green';
        return `<tr>
          <td class="td-mono">${item.product_id}</td>
          <td class="td-bold">${item.product_name}</td>
          <td><span class="pill ${typeClass}">${item.product_type}</span></td>
          <td class="td-mono">${parseFloat(item.quantity).toLocaleString()}</td>
          <td class="td-mono">${item.unit}</td>
          <td class="td-mono">${item.tank_id}</td>
          <td class="td-mono">${item.source||'—'}</td>
          <td class="td-mono">${parseFloat(item.reorder_level).toLocaleString()}</td>
          <td>${pill}</td>
          <td class="td-mono">${new Date(item.last_updated).toLocaleTimeString()}</td>
        </tr>`;
      }).join('')}</tbody>
    </table>`;
}

// ── LOAD TRANSACTIONS ──────────────────────────────────────────────────────────
async function loadTransactions() {
  const txns = await api('get_transactions');
  document.getElementById('kpi-txn').textContent = txns.length;

  // Activity feed
  document.getElementById('activity-feed').innerHTML = txns.slice(0,8).map(t => {
    const isIn = t.operation==='INBOUND';
    return `<div class="activity-item">
      <div class="act-icon ${isIn?'act-in':'act-out'}">${isIn?'⬇':'⬆'}</div>
      <div class="act-body">
        <div class="act-title">${parseFloat(t.quantity_delta).toLocaleString()} units · ${t.product_name}</div>
        <div class="act-meta">${t.source_system||t.destination||'—'} · ${new Date(t.txn_timestamp).toLocaleTimeString()}</div>
      </div>
      <div class="act-qty ${isIn?'qty-pos':'qty-neg'}">${isIn?'+':'−'}${parseFloat(t.quantity_delta).toLocaleString()}</div>
    </div>`;
  }).join('') || '<div class="loading">No transactions yet</div>';

  // Full table
  document.getElementById('txn-table-wrap').innerHTML = `
    <table class="data-table">
      <thead><tr><th>#</th><th>Operation</th><th>Product</th><th>Quantity</th><th>Source / Destination</th><th>Time</th><th>Status</th></tr></thead>
      <tbody>${txns.map(t => {
        const isIn = t.operation==='INBOUND';
        const pill = t.status==='Completed'
          ? '<span class="pill pill-green"><span class="pill-dot"></span>Completed</span>'
          : '<span class="pill pill-amber"><span class="pill-dot"></span>Pending</span>';
        return `<tr>
          <td class="td-mono">${t.txn_id}</td>
          <td><span class="pill ${isIn?'pill-green':'pill-red'}">${t.operation}</span></td>
          <td class="td-bold">${t.product_name}</td>
          <td class="td-mono">${isIn?'+':'−'}${parseFloat(t.quantity_delta).toLocaleString()}</td>
          <td class="td-mono">${t.source_system||t.destination||'—'}</td>
          <td class="td-mono">${new Date(t.txn_timestamp).toLocaleString()}</td>
          <td>${pill}</td>
        </tr>`;
      }).join('')}</tbody>
    </table>`;
}

// ── LOAD AUDIT LOG ─────────────────────────────────────────────────────────────
async function loadAudit() {
  const logs = await api('get_audit');
  document.getElementById('audit-table-wrap').innerHTML = `
    <table class="data-table">
      <thead><tr><th>Log #</th><th>Product</th><th>Old Qty</th><th>New Qty</th><th>Change</th><th>Operation</th><th>Timestamp</th></tr></thead>
      <tbody>${logs.map(l => {
        const delta = parseFloat(l.delta);
        const col = delta > 0 ? 'var(--green)' : 'var(--red)';
        return `<tr>
          <td class="td-mono">${l.log_id}</td>
          <td class="td-bold">${l.product_name}</td>
          <td class="td-mono">${parseFloat(l.old_quantity).toLocaleString()}</td>
          <td class="td-mono">${parseFloat(l.new_quantity).toLocaleString()}</td>
          <td style="font-family:'Syne',sans-serif;font-weight:700;color:${col}">${delta>0?'+':''}${delta.toLocaleString()}</td>
          <td><span class="pill ${delta>0?'pill-green':'pill-red'}">${l.operation}</span></td>
          <td class="td-mono">${new Date(l.changed_at).toLocaleString()}</td>
        </tr>`;
      }).join('')}</tbody>
    </table>`;
}

// ── LOAD TANKS ─────────────────────────────────────────────────────────────────
async function loadTanks() {
  const tanks = await api('get_tanks');
  const tColors = { 'TK-01':'#f0a832','TK-02':'#f0a832','TK-03':'#1e7b5e','TK-04':'#c4720a','TK-05':'#1d4ed8','TK-06':'#7c3aed' };
  document.getElementById('tank-grid').innerHTML = tanks.map(t => {
    const qty = parseFloat(t.quantity||0);
    const cap = parseFloat(t.capacity);
    const pct = Math.round(qty / cap * 100);
    const col = tColors[t.tank_id]||'#888';
    return `<div class="tank-card">
      <div class="tank-name">${t.tank_id}</div>
      <div class="tank-loc">${t.location}</div>
      <div class="tank-visual">
        <div class="tank-fill-bar" style="height:${pct}%;background:${col}33;border-top:2px solid ${col}"></div>
        <div class="tank-fill-text">${pct}%</div>
      </div>
      <div class="tank-stats">
        <span>${t.product_name||'Empty'}</span>
        <span style="color:var(--text2)">${qty.toLocaleString()} / ${(cap/1000).toFixed(0)}k</span>
      </div>
    </div>`;
  }).join('');
}

// ── ALERTS ─────────────────────────────────────────────────────────────────────
function renderAlerts() {
  const low = allInventory.filter(i => parseFloat(i.quantity) < parseFloat(i.reorder_level));
  document.getElementById('alerts-list').innerHTML = low.length
    ? low.map(item => {
        const shortage = parseFloat(item.reorder_level) - parseFloat(item.quantity);
        return `<div class="activity-item">
          <div class="act-icon" style="background:var(--red-light);font-size:16px">⚠️</div>
          <div class="act-body">
            <div class="act-title">${item.product_name} below reorder threshold</div>
            <div class="act-meta">Current: ${parseFloat(item.quantity).toLocaleString()} ${item.unit} · Reorder: ${parseFloat(item.reorder_level).toLocaleString()} · Shortage: ${shortage.toLocaleString()}</div>
          </div>
          <button class="ph-action" onclick="openModal('receiveModal')">Order Now</button>
        </div>`;
      }).join('')
    : '<div class="loading" style="color:var(--green)">✓ All stock levels are healthy</div>';
}

// ── RECEIVE STOCK ──────────────────────────────────────────────────────────────
async function receiveStock() {
  const qty = parseInt(document.getElementById('recv-qty').value);
  if (!qty || qty <= 0) { toast('Enter a valid quantity','error'); return; }
  const btn = document.getElementById('recv-btn');
  btn.disabled = true; btn.textContent = 'Saving...';
  const res = await api('receive_stock', {
    product_id: document.getElementById('recv-product').value,
    quantity:   qty,
    source:     document.getElementById('recv-src').value
  });
  btn.disabled = false; btn.textContent = 'Confirm Receipt';
  if (res.success) {
    closeModal('receiveModal');
    document.getElementById('recv-qty').value = '';
    toast('✓ Stock received & saved to database', 'success');
    await loadAll();
  } else {
    toast(res.message || 'Error', 'error');
  }
}

// ── DISPATCH STOCK ─────────────────────────────────────────────────────────────
async function dispatchStock() {
  const qty  = parseInt(document.getElementById('disp-qty').value);
  const dest = document.getElementById('disp-dest').value || 'Unspecified';
  if (!qty || qty <= 0) { toast('Enter a valid quantity','error'); return; }
  const btn = document.getElementById('disp-btn');
  btn.disabled = true; btn.textContent = 'Processing...';
  const res = await api('dispatch_stock', {
    product_id:  document.getElementById('disp-product').value,
    quantity:    qty,
    destination: dest
  });
  btn.disabled = false; btn.textContent = 'Confirm Dispatch';
  if (res.success) {
    closeModal('dispatchModal');
    document.getElementById('disp-qty').value = '';
    document.getElementById('disp-dest').value = '';
    toast('✓ Dispatch created & inventory updated in DB', 'success');
    await loadAll();
  } else {
    toast(res.message || 'Error', 'error');
  }
}

// ── UTILS ──────────────────────────────────────────────────────────────────────
function toast(msg, type='info') {
  const el = document.createElement('div');
  el.className = `toast ${type==='success'?'toast-success':type==='error'?'toast-error':''}`;
  el.textContent = msg;
  document.getElementById('toasts').appendChild(el);
  setTimeout(()=>el.remove(), 3500);
}
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('open')}));

const pageMeta = {
  dashboard:    ['Operations Dashboard',  'Home / Dashboard'],
  inventory:    ['Inventory Register',    'Home / Inventory'],
  tanks:        ['Storage Tanks',         'Home / Tanks'],
  transactions: ['Transaction Log',       'Home / Transactions'],
  alerts:       ['Alerts',               'Home / Alerts'],
};
function showPage(name, btn) {
  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(b=>b.classList.remove('active'));
  document.getElementById('page-'+name).classList.add('active');
  if (btn) btn.classList.add('active');
  const [title,bread] = pageMeta[name]||['—','—'];
  document.getElementById('page-title').textContent = title;
  document.getElementById('page-bread').textContent = bread;
}

async function loadAll() {
  await loadInventory();
  await loadTransactions();
  await loadTanks();
}

// Boot
loadAll();
</script>
</body>
</html>
