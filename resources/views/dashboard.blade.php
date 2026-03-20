<div id="page-dashboard" class="page active">
      <div class="page-header">
        <div>
          <div class="page-title">Good <span id="greeting-time">day</span> ✦</div>
          <div class="page-subtitle" id="today-date"></div>
          <div class="dash-motivation" id="daily-motivation"></div>
        </div>
        <button class="btn btn-primary" onclick="openEntryTypeModal()">+ New Entry</button>
      </div>

      <div class="stats-grid">
        <div class="stat-card" style="border-top:2px solid var(--accent)">
          <div class="stat-label">Journal Entries</div>
          <div class="stat-value" id="stat-entries" style="color:var(--accent)">0</div>
          <div class="stat-change">All time</div>
        </div>
        <div class="stat-card" style="border-top:2px solid var(--green)">
          <div class="stat-label">Tasks Done</div>
          <div class="stat-value" id="stat-tasks" style="color:var(--green)">0</div>
          <div class="stat-change">This week</div>
        </div>
        <div class="stat-card" style="border-top:2px solid var(--lavender)">
          <div class="stat-label">Active Goals</div>
          <div class="stat-value" id="stat-goals" style="color:var(--lavender)">0</div>
          <div class="stat-change">In progress</div>
        </div>
        <div class="stat-card" style="border-top:2px solid var(--amber)">
          <div class="stat-label">Streak</div>
          <div class="stat-value" id="stat-streak" style="color:var(--amber)">0</div>
          <div class="stat-change">days 🔥</div>
        </div>
        <div class="stat-card" style="border-top:2px solid var(--teal)">
          <div class="stat-label">Fav Mood</div>
          <div class="stat-value" id="stat-mood" style="font-size:2rem">—</div>
          <div class="stat-change">Most common</div>
        </div>
      </div>

      <div style="margin-bottom:18px;display:flex;gap:10px;flex-wrap:wrap">
        <button class="btn" onclick="exportAsJSON()">💾 Export Backup</button>
        <button class="btn" onclick="navigateTo('community')" style="border-color:rgba(45,212,191,.25);color:var(--teal)">🌐 Community Feed</button>
      </div>

      <div class="grid-2">
        <div class="card">
          <div class="card-title" style="display:flex;align-items:center;justify-content:space-between">
            <span>📓 Recent Entries</span>
            <span style="font-family:'JetBrains Mono',monospace;font-size:.56rem;font-weight:400;opacity:.4;letter-spacing:.04em">↗ click to expand</span>
          </div>
          <div id="dash-journal-list"></div>
        </div>
        <div class="card">
          <div class="card-title">✅ Today's Tasks</div>
          <div id="dash-task-list"></div>
        </div>
      </div>

      <div class="card">
        <div class="card-title" style="display:flex;align-items:center;justify-content:space-between">
          <span>🎯 Goals Progress</span>
          <button class="btn-sm" onclick="navigateTo('goals')">View all →</button>
        </div>
        <div id="dash-goals-list"></div>
      </div>
    </div>

    <style>
    .dash-motivation{
      font-family:'Newsreader',serif;font-style:italic;
      font-size:.88rem;color:var(--muted);margin-top:6px;font-weight:300;opacity:.8;
    }
    .stats-grid .stat-card{border-top-left-radius:18px;border-top-right-radius:18px}
    </style>