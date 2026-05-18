@extends('layouts.app')

@section('title', 'Dashboard')

@section('extra-styles')
<style>
    .page-header {
        margin-bottom: 32px;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .page-subtitle {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-family: var(--font-mono);
    }

    /* Security Principles Banner */
    .principles-bar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 32px;
    }
    .principle {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 14px 16px;
        position: relative;
        overflow: hidden;
    }
    .principle::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
    }
    .principle.confidentiality::before { background: var(--accent); }
    .principle.integrity::before       { background: var(--green); }
    .principle.authentication::before  { background: var(--yellow); }

    .principle-icon { font-size: 1.2rem; margin-bottom: 6px; }
    .principle-name { font-size: 0.7rem; font-family: var(--font-mono); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; }
    .principle.confidentiality .principle-name { color: var(--accent); }
    .principle.integrity       .principle-name { color: var(--green); }
    .principle.authentication  .principle-name { color: var(--yellow); }
    .principle-desc { font-size: 0.78rem; color: var(--text-muted); }

    /* Upload Section */
    .upload-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 24px;
        margin-bottom: 28px;
    }
    .section-title {
        font-size: 0.8rem;
        font-family: var(--font-mono);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--text-muted);
        margin-bottom: 16px;
    }
    .upload-zone {
        border: 2px dashed var(--border);
        border-radius: 8px;
        padding: 28px;
        text-align: center;
        transition: border-color 0.15s, background 0.15s;
        cursor: pointer;
        position: relative;
    }
    .upload-zone:hover, .upload-zone.drag-over {
        border-color: var(--accent);
        background: rgba(0,212,255,0.03);
    }
    .upload-zone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }
    .upload-icon { font-size: 2rem; margin-bottom: 8px; }
    .upload-text { font-size: 0.9rem; color: var(--text-muted); }
    .upload-text strong { color: var(--accent); }
    .upload-hint { font-size: 0.72rem; font-family: var(--font-mono); color: var(--text-muted); margin-top: 4px; }
    .selected-file {
        display: none;
        align-items: center;
        gap: 10px;
        margin-top: 12px;
        background: rgba(0,212,255,0.06);
        border: 1px solid rgba(0,212,255,0.2);
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 0.82rem;
        font-family: var(--font-mono);
        color: var(--accent);
    }
    .btn-upload {
        margin-top: 14px;
        background: var(--accent);
        color: #0a0e1a;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        font-family: var(--font-main);
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-upload:hover { background: var(--accent-dim); }

    /* Files Table */
    .files-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
    }
    .files-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
    }
    .files-count {
        font-size: 0.75rem;
        font-family: var(--font-mono);
        color: var(--text-muted);
        background: var(--bg-input);
        border: 1px solid var(--border);
        padding: 3px 10px;
        border-radius: 20px;
    }

    .file-table { width: 100%; border-collapse: collapse; }
    .file-table th {
        text-align: left;
        padding: 10px 20px;
        font-size: 0.68rem;
        font-family: var(--font-mono);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--text-muted);
        background: rgba(0,0,0,0.2);
        font-weight: 500;
    }
    .file-table td {
        padding: 14px 20px;
        font-size: 0.85rem;
        border-top: 1px solid var(--border);
        vertical-align: middle;
    }
    .file-table tr:hover td { background: rgba(255,255,255,0.015); }

    .file-name {
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .file-ext {
        font-size: 0.68rem;
        font-family: var(--font-mono);
        background: var(--bg-input);
        border: 1px solid var(--border);
        padding: 2px 6px;
        border-radius: 3px;
        color: var(--text-muted);
        text-transform: uppercase;
    }
    .file-meta { font-size: 0.75rem; color: var(--text-muted); font-family: var(--font-mono); }
    .file-hash {
        font-size: 0.68rem;
        font-family: var(--font-mono);
        color: var(--text-muted);
        max-width: 160px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: help;
    }

    .integrity-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.7rem;
        font-family: var(--font-mono);
        color: var(--green);
        background: rgba(0,255,136,0.06);
        border: 1px solid rgba(0,255,136,0.2);
        padding: 3px 8px;
        border-radius: 20px;
    }

    .file-actions { display: flex; gap: 8px; align-items: center; }
    .btn-download {
        font-size: 0.75rem;
        font-family: var(--font-mono);
        color: var(--accent);
        background: rgba(0,212,255,0.06);
        border: 1px solid rgba(0,212,255,0.2);
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .btn-download:hover { background: rgba(0,212,255,0.12); border-color: var(--accent); }
    .btn-delete {
        font-size: 0.75rem;
        font-family: var(--font-mono);
        color: var(--red);
        background: transparent;
        border: 1px solid rgba(255,77,109,0.2);
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-delete:hover { background: rgba(255,77,109,0.08); border-color: var(--red); }

    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: var(--text-muted);
    }
    .empty-icon { font-size: 2.5rem; margin-bottom: 12px; }
    .empty-title { font-size: 1rem; margin-bottom: 4px; color: var(--text); }
    .empty-hint { font-size: 0.8rem; font-family: var(--font-mono); }

    @media (max-width: 640px) {
        .principles-bar { grid-template-columns: 1fr; }
        .file-table th:nth-child(3),
        .file-table td:nth-child(3),
        .file-table th:nth-child(4),
        .file-table td:nth-child(4) { display: none; }
    }
</style>
@endsection

@section('content')
<div class="container">

    <div class="page-header">
        <h1 class="page-title">File Dashboard</h1>
        <p class="page-subtitle">Your encrypted files · {{ Auth::user()->name }}</p>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    {{-- Security Principles Banner --}}
    <div class="principles-bar">
        <div class="principle confidentiality">
            <div class="principle-icon">🔐</div>
            <div class="principle-name">Confidentiality</div>
            <div class="principle-desc">AES-256-CBC encryption before storage</div>
        </div>
        <div class="principle integrity">
            <div class="principle-icon">🛡️</div>
            <div class="principle-name">Integrity</div>
            <div class="principle-desc">SHA-256 hash verified on every download</div>
        </div>
        <div class="principle authentication">
            <div class="principle-icon">🔑</div>
            <div class="principle-name">Authentication</div>
            <div class="principle-desc">bcrypt passwords · session-based access</div>
        </div>
    </div>

    {{-- Upload Zone --}}
    <div class="upload-card">
        <div class="section-title">⬆ Upload Encrypted File</div>
        <form method="POST" action="{{ route('files.upload') }}" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="upload-zone" id="dropzone">
                <input type="file" name="file" id="fileInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.gif,.txt,.csv,.zip">
                <div class="upload-icon">📂</div>
                <div class="upload-text"><strong>Click to choose</strong> or drag and drop</div>
                <div class="upload-hint">PDF, DOC, XLS, PNG, JPG, TXT, CSV, ZIP · Max 10 MB</div>
            </div>
            <div class="selected-file" id="selectedFile">
                <span>📄</span>
                <span id="selectedName"></span>
            </div>
            <button type="submit" class="btn-upload" id="uploadBtn" style="display:none;">
                🔒 Encrypt &amp; Upload
            </button>
        </form>
    </div>

    {{-- Files List --}}
    <div class="files-card">
        <div class="files-header">
            <div class="section-title" style="margin:0;">Your Encrypted Files</div>
            <span class="files-count">{{ $files->count() }} file{{ $files->count() !== 1 ? 's' : '' }}</span>
        </div>

        @if ($files->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">🗂️</div>
                <div class="empty-title">No files uploaded yet</div>
                <div class="empty-hint">Upload a file above — it will be AES-256 encrypted before storage</div>
            </div>
        @else
            <table class="file-table">
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Size</th>
                        <th>SHA-256 Hash</th>
                        <th>Integrity</th>
                        <th>Uploaded</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($files as $file)
                    <tr>
                        <td>
                            <div class="file-name">
                                <span>{{ $file->original_name }}</span>
                                <span class="file-ext">{{ pathinfo($file->original_name, PATHINFO_EXTENSION) }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="file-meta">{{ $file->readable_size }}</span>
                        </td>
                        <td>
                            <span class="file-hash" title="{{ $file->integrity_hash }}">
                                {{ substr($file->integrity_hash, 0, 16) }}…
                            </span>
                        </td>
                        <td>
                            <span class="integrity-badge">✓ verified</span>
                        </td>
                        <td>
                            <span class="file-meta">{{ $file->created_at->format('M d, Y H:i') }}</span>
                        </td>
                        <td>
                            <div class="file-actions">
                                <a href="{{ route('files.download', $file) }}" class="btn-download">⬇ Download</a>
                                <form method="POST" action="{{ route('files.delete', $file) }}" onsubmit="return confirm('Delete {{ addslashes($file->original_name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">🗑</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

<script>
    const fileInput  = document.getElementById('fileInput');
    const selectedEl = document.getElementById('selectedFile');
    const selectedName = document.getElementById('selectedName');
    const uploadBtn  = document.getElementById('uploadBtn');
    const dropzone   = document.getElementById('dropzone');

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            selectedName.textContent = fileInput.files[0].name + ' (' + formatBytes(fileInput.files[0].size) + ')';
            selectedEl.style.display = 'flex';
            uploadBtn.style.display  = 'inline-block';
        }
    });

    dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('drag-over'); });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('drag-over'));
    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.classList.remove('drag-over');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes/1024).toFixed(1) + ' KB';
        return (bytes/1048576).toFixed(1) + ' MB';
    }
</script>
@endsection