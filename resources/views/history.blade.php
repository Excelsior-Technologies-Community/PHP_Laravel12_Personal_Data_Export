<!DOCTYPE html>
<html>
<head>
    <title>Export History</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; border-radius: 15px; padding: 20px; display: flex; align-items: center; gap: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .stat-icon { width: 50px; height: 50px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; }
        .controls { background: white; border-radius: 15px; padding: 20px; margin-bottom: 30px; display: flex; gap: 15px; flex-wrap: wrap; align-items: center; justify-content: space-between; }
        .table-container { background: white; border-radius: 20px; overflow-x: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #f0f0f0; }
        .badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .badge-json { background: #28a745; color: white; }
        .badge-csv { background: #17a2b8; color: white; }
        .badge-xml { background: #ffc107; color: #333; }
        .btn-icon { padding: 8px 12px; border-radius: 8px; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; font-size: 14px; }
        .btn-download { background: #28a745; color: white; }
        .btn-delete { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <a href="/" style="background:#6c757d; color:white; padding:10px 20px; border-radius:10px; text-decoration:none; display:inline-block; margin-bottom:20px;">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        <div class="header">
            <h1>Export History</h1>
            <p>Statistics and usage logs</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-file-export"></i></div>
                <div><h3>{{ $stats['total_exports'] }}</h3><p>Total Exports</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-database"></i></div>
                <div><h3>{{ number_format($stats['total_size'] / 1024, 2) }} KB</h3><p>Total Size</p></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div><h3>{{ $stats['unique_users'] }}</h3><p>Unique Users</p></div>
            </div>
        </div>

        <div class="controls">
            <form method="GET" action="{{ route('history') }}" class="flex gap-2 w-full md:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID/Name..." class="border p-2 rounded-md">
                <select name="format" onchange="this.form.submit()" class="border p-2 rounded-md">
                    <option value="">All Formats</option>
                    <option value="json" {{ request('format') == 'json' ? 'selected' : '' }}>JSON</option>
                    <option value="csv" {{ request('format') == 'csv' ? 'selected' : '' }}>CSV</option>
                    <option value="xml" {{ request('format') == 'xml' ? 'selected' : '' }}>XML</option>
                </select>
            </form>
            <form method="POST" action="{{ route('export.delete-all') }}" onsubmit="return confirm('Delete all history?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-icon" style="background:#dc3545; color:white;">Delete All</button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>User ID</th><th>File Name</th><th>Format</th><th>Size</th><th>Date</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exports as $exp)
                    <tr>
                        <td>{{ $exp->id }}</td>
                        <td>{{ $exp->user_id }}</td>
                        <td>{{ $exp->file_name }}</td>
                        <td><span class="badge badge-{{ $exp->format }}">{{ strtoupper($exp->format) }}</span></td>
                        <td>{{ number_format(($exp->file_size ?? 0) / 1024, 2) }} KB</td>
                        <td>{{ $exp->exported_at->format('M d, Y H:i') }}</td>
                        <td class="action-buttons">
                            <a href="{{ route('export', $exp->user_id) }}?format={{ $exp->format }}" class="btn-icon btn-download"><i class="fas fa-download"></i></a>
                            <form method="POST" action="{{ route('export.delete', $exp->id) }}" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7">No history found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5">{{ $exports->links() }}</div>
    </div>
</body>
</html>