<!DOCTYPE html>
<html>
<head>
    <title>Export History</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        
        .stat-info h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-info p {
            color: #666;
            font-size: 14px;
        }
        
        .controls {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }
        
        .search-box {
            flex: 1;
            position: relative;
        }
        
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        
        .search-box input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .search-box input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .filter-select {
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            background: white;
            cursor: pointer;
        }
        
        .delete-all-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .delete-all-btn:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .table-container {
            background: white;
            border-radius: 20px;
            overflow-x: auto;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-json { background: #28a745; color: white; }
        .badge-csv { background: #17a2b8; color: white; }
        .badge-xml { background: #ffc107; color: #333; }
        
        .file-size {
            color: #666;
            font-size: 13px;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-icon {
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            border: none;
        }
        
        .btn-download {
            background: #28a745;
            color: white;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px;
            color: #999;
        }
        
        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }
        
        .pagination a, .pagination span {
            padding: 10px 15px;
            background: white;
            border-radius: 8px;
            text-decoration: none;
            color: #667eea;
            transition: all 0.3s;
        }
        
        .pagination a:hover {
            background: #667eea;
            color: white;
        }
        
        @media (max-width: 768px) {
            body { padding: 20px; }
            .stats-grid { grid-template-columns: 1fr; }
            .controls { flex-direction: column; }
            .action-buttons { flex-direction: column; }
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="/" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Export
        </a>
        
        <div class="header">
            <h1><i class="fas fa-history"></i> Export History</h1>
            <p>Track and manage all your data exports</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        
       
        
        <div class="controls">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <form method="GET" action="{{ route('history') }}" id="search-form">
                    <input type="text" name="search" placeholder="Search by user ID or file name..." 
                           value="{{ request('search') }}" onchange="this.form.submit()">
                </form>
            </div>
            
            <select class="filter-select" onchange="window.location.href='?format='+this.value">
                <option value="">All Formats</option>
                <option value="json" {{ request('format') == 'json' ? 'selected' : '' }}>JSON</option>
                <option value="csv" {{ request('format') == 'csv' ? 'selected' : '' }}>CSV</option>
                <option value="xml" {{ request('format') == 'xml' ? 'selected' : '' }}>XML</option>
            </select>
            
            <form method="POST" action="{{ route('export.delete-all') }}" 
                  onsubmit="return confirm('Are you sure you want to delete ALL exports? This action cannot be undone!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-all-btn">
                    <i class="fas fa-trash-alt"></i> Delete All
                </button>
            </form>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>File Name</th>
                        <th>Format</th>
                        <th>Size</th>
                        <th>Date & Time</th>
                        <th>IP Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exports as $exp)
                    <tr>
                        <td>{{ $exp->id }}</td>
                        <td>{{ $exp->user_id }}</td>
                        <td>{{ $exp->file_name }}</td>
                        <td>
                            <span class="badge badge-{{ $exp->format ?? 'json' }}">
                                {{ strtoupper($exp->format ?? 'JSON') }}
                            </span>
                        </td>
                        <td class="file-size">
                            @if($exp->file_size)
                                {{ number_format($exp->file_size / 1024, 2) }} KB
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $exp->exported_at ? $exp->exported_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                        <td>{{ $exp->ip_address ?? 'N/A' }}</td>
                        <td class="action-buttons">
                            <a href="{{ route('export', $exp->user_id) }}?format={{ $exp->format ?? 'json' }}" 
                               class="btn-icon btn-download">
                                <i class="fas fa-download"></i> Download
                            </a>
                            
                            <form method="POST" action="{{ route('export.delete', $exp->id) }}" 
                                  onsubmit="return confirm('Delete this export?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>No export history found</p>
                                <a href="/" style="color: #667eea;">Start exporting now →</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="pagination">
            {{ $exports->links() }}
        </div>
    </div>
</body>
</html>