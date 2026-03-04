{{-- ══ JOURNAL PAGE ══ --}}
<div id="page-journal" class="page">
    <div class="page-header">
        <div>
            <h1 class="page-title">📓 Journal</h1>
            <p class="page-subtitle">Your private thoughts &amp; reflections</p>
        </div>
        <button class="btn btn-primary" onclick="openJournalModal()">+ New Entry</button>
    </div>

    <div style="margin-bottom:20px">
        <input class="form-input" id="journal-search" placeholder="🔍 Search entries by title, content or tag…" oninput="filterJournals()">
    </div>

    <div id="journal-list"></div>
</div>

{{-- ══ JOURNAL EXPAND OVERLAY ══ --}}
<div class="journal-expand-overlay" id="journal-expand-overlay">
    <div class="journal-expanded-card">
        <div class="expanded-card-header">
            <div>
                <h2 id="exp-title" class="expanded-card-title"></h2>
                <div class="expanded-card-meta">
                    <span id="exp-date" class="expanded-card-date"></span>
                    <span id="exp-mood" class="expanded-mood-badge"></span>
                </div>
            </div>
            <button class="expanded-close-btn" onclick="closeExpandedJournal()">✕</button>
        </div>
        <div class="expanded-card-body">
            <div id="exp-content" class="expanded-card-content"></div>
            <div id="exp-photos" class="expanded-photos" style="display:none;flex-wrap:wrap;gap:8px;margin-top:12px"></div>
            <div id="exp-tags" class="expanded-tags" style="display:none;flex-wrap:wrap;gap:6px;margin-top:12px"></div>
        </div>
        <div class="expanded-card-footer">
            <button class="btn" onclick="editFromExpanded()">✎ Edit</button>
            <button class="btn" onclick="shareJournalFromExpanded()">↗ Share</button>
        </div>
    </div>
</div>