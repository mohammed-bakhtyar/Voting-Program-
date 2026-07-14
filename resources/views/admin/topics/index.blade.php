@extends('layouts.main')

@section('content')
<style>
    .admin-table-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 20px;
        overflow: hidden;
    }
    .admin-table-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 24px 28px; border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .admin-table-title { font-size: 16px; font-weight: 700; color: #f8fafc; }
    .admin-table-sub { font-size: 12px; color: #64748b; margin-top: 2px; }

    .topics-table { width: 100%; border-collapse: collapse; }
    .topics-table th {
        padding: 12px 20px; text-align: left;
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.07em; color: #475569;
        background: rgba(255,255,255,0.02);
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .topics-table td {
        padding: 16px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        vertical-align: middle;
        font-size: 13px;
    }
    .topics-table tr:last-child td { border-bottom: none; }
    .topics-table tr { transition: background 0.15s; }
    .topics-table tr:hover td { background: rgba(255,255,255,0.02); }

    .topic-link { color: #c7d2fe; font-weight: 600; text-decoration: none; transition: color 0.2s; }
    .topic-link:hover { color: #818cf8; }

    .action-btn {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 12px; border-radius: 7px;
        font-size: 12px; font-weight: 600;
        border: 1px solid transparent; cursor: pointer;
        text-decoration: none; transition: all 0.15s;
        background: none;
    }
    .action-btn svg { width: 13px; height: 13px; }
    .action-btn-view { color: #818cf8; border-color: rgba(99,102,241,0.25); background: rgba(99,102,241,0.08); }
    .action-btn-view:hover { background: rgba(99,102,241,0.18); }
    .action-btn-edit { color: #fbbf24; border-color: rgba(251,191,36,0.25); background: rgba(251,191,36,0.08); }
    .action-btn-edit:hover { background: rgba(251,191,36,0.18); }
    .action-btn-delete { color: #f87171; border-color: rgba(239,68,68,0.2); background: rgba(239,68,68,0.08); }
    .action-btn-delete:hover { background: rgba(239,68,68,0.18); }
    .action-btn-close { color: #fb923c; border-color: rgba(251,146,60,0.22); background: rgba(251,146,60,0.08); }
    .action-btn-close:hover { background: rgba(251,146,60,0.18); }

    .votes-chip {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 12px; font-weight: 600; color: #64748b;
    }
    .votes-chip svg { width: 12px; height: 12px; }

    .empty-table { padding: 60px 28px; text-align: center; color: #475569; }
    .empty-table svg { width: 36px; height: 36px; margin: 0 auto 12px; display: block; }
</style>

<div>
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:16px;">
        <div>
            <h1 class="vp-section-title">Manage Polls</h1>
            <p class="vp-section-sub">Create, edit, and monitor all voting topics.</p>
        </div>
        <a href="{{ route('admin.topics.create') }}" class="vp-btn vp-btn-primary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Poll
        </a>
    </div>

    <div class="admin-table-card">
        @if($topics->isEmpty())
            <div class="empty-table">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="color:#334155;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p>No topics found. <a href="{{ route('admin.topics.create') }}" style="color:#6366f1;">Create one →</a></p>
            </div>
        @else
            <table class="topics-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Votes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topics as $topic)
                        <tr>
                            <td>
                                <a href="{{ route('admin.topics.show', $topic) }}" class="topic-link">{{ $topic->title }}</a>
                            </td>
                            <td>
                                @if($topic->closed_at || now()->isAfter($topic->closes_at))
                                    <span class="vp-badge vp-badge-danger">Closed</span>
                                @elseif(now()->isBefore($topic->opens_at))
                                    <span class="vp-badge vp-badge-warning">Upcoming</span>
                                @else
                                    <span class="vp-badge vp-badge-success">
                                        <span class="vp-pulse-dot"></span>
                                        Open
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="votes-chip">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $topic->votes_count }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                    <a href="{{ route('admin.topics.show', $topic) }}" class="action-btn action-btn-view">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        View
                                    </a>
                                    @if($topic->votes_count === 0)
                                        <a href="{{ route('admin.topics.edit', $topic) }}" class="action-btn action-btn-edit">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST" style="display:inline;margin:0;" onsubmit="return confirm('Delete this poll? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn action-btn-delete">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                    @if(!$topic->closed_at && now()->isBefore($topic->closes_at))
                                        <form action="{{ route('admin.topics.close', $topic) }}" method="POST" style="display:inline;margin:0;" onsubmit="return confirm('Close this poll early?');">
                                            @csrf
                                            <button type="submit" class="action-btn action-btn-close">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                Close
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
