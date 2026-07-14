@extends('layouts.main')

@section('content')
<style>
    .edit-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 20px;
        overflow: hidden;
        max-width: 680px;
        margin: 0 auto;
    }
    .edit-card-header {
        padding: 28px 36px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
        position: relative;
    }
    .edit-card-header::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
    }
    .edit-card-title { font-size: 18px; font-weight: 800; color: #f8fafc; letter-spacing: -0.3px; }
    .edit-card-sub { font-size: 13px; color: #64748b; margin-top: 4px; }

    .edit-card-body { padding: 32px 36px; }

    .form-group { margin-bottom: 22px; }
    .form-label { display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #64748b; margin-bottom: 8px; }
    .form-input, .form-textarea, .form-select {
        width: 100%;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 11px 14px;
        color: #f8fafc;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-input:focus, .form-textarea:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
    }
    .form-input::placeholder, .form-textarea::placeholder { color: #475569; }
    .form-textarea { resize: vertical; min-height: 100px; }
    .form-select option { background: #1e1e2e; }

    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media (max-width: 560px) { .form-grid-2 { grid-template-columns: 1fr; } }

    .form-divider { height: 1px; background: rgba(255,255,255,0.06); margin: 24px 0; }

    .toggle-group {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 12px;
        padding: 16px 20px;
        display: flex; flex-direction: column; gap: 14px;
    }
    .toggle-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
    .toggle-info {}
    .toggle-title { font-size: 13px; font-weight: 600; color: #cbd5e1; }
    .toggle-desc { font-size: 12px; color: #475569; margin-top: 2px; }
    .toggle-check { display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .toggle-check input[type=checkbox] { width: 16px; height: 16px; accent-color: #6366f1; cursor: pointer; }

    .options-section { margin-top: 0; }
    .option-input-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
    .option-input {
        flex: 1;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 10px 14px;
        color: #f8fafc;
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .option-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
    .option-input::placeholder { color: #475569; }

    .option-num { width: 28px; height: 28px; border-radius: 7px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: #475569; flex-shrink: 0; }

    .add-option-btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 16px; border-radius: 10px;
        background: rgba(99,102,241,0.08); color: #818cf8;
        border: 1px dashed rgba(99,102,241,0.3);
        font-size: 13px; font-weight: 600; cursor: pointer;
        transition: all 0.2s; margin-top: 4px; width: 100%;
        justify-content: center; font-family: 'Inter', sans-serif;
    }
    .add-option-btn:hover { background: rgba(99,102,241,0.15); border-color: rgba(99,102,241,0.5); }
    .add-option-btn svg { width: 15px; height: 15px; }

    .form-actions { display: flex; align-items: center; gap: 12px; margin-top: 28px; }
    .save-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 28px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white; font-size: 14px; font-weight: 700;
        border: none; border-radius: 10px; cursor: pointer;
        transition: all 0.2s; font-family: 'Inter', sans-serif;
        box-shadow: 0 4px 14px rgba(99,102,241,0.35);
    }
    .save-btn:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.45); }
    .save-btn svg { width: 15px; height: 15px; }
    .cancel-link { font-size: 13px; color: #64748b; text-decoration: none; font-weight: 500; transition: color 0.2s; }
    .cancel-link:hover { color: #94a3b8; }
</style>

<div style="max-width:680px; margin:0 auto;">
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.topics.index') }}" style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:500; color:#64748b; text-decoration:none; transition:color 0.2s;">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to Topics
        </a>
    </div>

    <div class="edit-card">
        <div class="edit-card-header">
            <div class="edit-card-title">Edit Poll</div>
            <div class="edit-card-sub">{{ $topic->title }}</div>
        </div>

        <div class="edit-card-body">
            <form action="{{ route('admin.topics.update', $topic) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div class="form-group">
                    <label class="form-label">Title <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $topic->title) }}" class="form-input" required placeholder="Poll question...">
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" placeholder="Optional context or details...">{{ old('description', $topic->description) }}</textarea>
                </div>

                {{-- Dates --}}
                <div class="form-grid-2 form-group">
                    <div>
                        <label class="form-label">Opens At <span style="color:#ef4444;">*</span></label>
                        <input type="datetime-local" name="opens_at" value="{{ old('opens_at', $topic->opens_at->format('Y-m-d\TH:i')) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Closes At <span style="color:#ef4444;">*</span></label>
                        <input type="datetime-local" name="closes_at" value="{{ old('closes_at', $topic->closes_at->format('Y-m-d\TH:i')) }}" class="form-input" required>
                    </div>
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-input">
                        <option value="draft"   {{ old('status', $topic->status) === 'draft'   ? 'selected' : '' }}>📝 Draft</option>
                        <option value="active"  {{ old('status', $topic->status) === 'active'  ? 'selected' : '' }}>✅ Active</option>
                        <option value="closed"  {{ old('status', $topic->status) === 'closed'  ? 'selected' : '' }}>🔒 Closed</option>
                    </select>
                </div>

                <div class="form-divider"></div>

                {{-- Toggles --}}
                <div class="form-group">
                    <label class="form-label">Settings</label>
                    <div class="toggle-group">
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <div class="toggle-title">Allow Multiple Votes</div>
                                <div class="toggle-desc">Users can select more than one option.</div>
                            </div>
                            <label class="toggle-check">
                                <input type="hidden" name="allow_multiple_votes" value="0">
                                <input type="checkbox" name="allow_multiple_votes" value="1" {{ old('allow_multiple_votes', $topic->allow_multiple_votes) ? 'checked' : '' }}>
                            </label>
                        </div>
                        <div class="toggle-row">
                            <div class="toggle-info">
                                <div class="toggle-title">Show Results Upfront</div>
                                <div class="toggle-desc">Show percentages before users vote.</div>
                            </div>
                            <label class="toggle-check">
                                <input type="hidden" name="show_results_before_voting" value="0">
                                <input type="checkbox" name="show_results_before_voting" value="1" {{ old('show_results_before_voting', $topic->show_results_before_voting) ? 'checked' : '' }}>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-divider"></div>

                {{-- Options --}}
                <div class="form-group options-section">
                    <label class="form-label">Voting Options (2–10)</label>
                    <div id="options-container">
                        @php $oldOptions = old('options', $topic->options->pluck('label')->toArray()); @endphp
                        @foreach($oldOptions as $index => $option)
                            <div class="option-input-row">
                                <span class="option-num">{{ $index + 1 }}</span>
                                <input type="text" name="options[]" value="{{ $option }}" class="option-input" placeholder="Option {{ $index + 1 }}" required>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-option" class="add-option-btn">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Add Option
                    </button>
                </div>

                <div class="form-actions">
                    <button type="submit" class="save-btn">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </button>
                    <a href="{{ route('admin.topics.index') }}" class="cancel-link">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('options-container');
        const addBtn = document.getElementById('add-option');

        addBtn.addEventListener('click', function() {
            if (container.children.length < 10) {
                const idx = container.children.length + 1;
                const row = document.createElement('div');
                row.className = 'option-input-row';
                row.innerHTML = `<span class="option-num">${idx}</span><input type="text" name="options[]" class="option-input" placeholder="Option ${idx}" required>`;
                container.appendChild(row);
            }
            if (container.children.length >= 10) { addBtn.style.display = 'none'; }
        });
    });
</script>
@endsection
