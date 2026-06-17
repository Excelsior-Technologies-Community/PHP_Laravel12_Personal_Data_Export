<!DOCTYPE html>
<html>
<head>
    <title>Users Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px; min-height: 100vh; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        .users-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px; }
        .user-card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .user-card:hover { transform: translateY(-5px); }
        .user-header { background: linear-gradient(135deg, #667eea, #764ba2); padding: 20px; color: white; text-align: center; }
        .user-avatar { width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 36px; }
        .user-body { padding: 20px; }
        .user-detail { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; color: #555; font-size: 14px; }
        .user-detail i { width: 20px; color: #667eea; }
        .card-actions { display: flex; gap: 10px; margin-top: 20px; }
        .btn { flex: 1; padding: 10px; border-radius: 8px; text-decoration: none; text-align: center; font-size: 14px; font-weight: 500; transition: all 0.3s; }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .pagination { display: flex; justify-content: center; gap: 10px; margin-top: 30px; }
        .pagination a, .pagination span { padding: 10px 15px; background: white; border-radius: 8px; text-decoration: none; color: #667eea; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('history') }}" class="inline-flex items-center gap-2 bg-gray-600 text-white px-5 py-2 rounded-lg mb-5">
            <i class="fas fa-history"></i> Export History
        </a>
        
        <div class="header">
            <h1><i class="fas fa-users"></i> Users Management</h1>
            <form method="GET" action="{{ route('users.index') }}" class="flex gap-2 mt-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." class="border p-2 rounded w-full">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Search</button>
            </form>
        </div>
        
        <div class="users-grid">
            @foreach($users as $user)
            <div class="user-card">
                <div class="user-header">
                    <div class="user-avatar"><i class="fas fa-user-circle"></i></div>
                    <div class="text-xl font-bold">{{ $user->name }}</div>
                    <div class="text-sm opacity-90">{{ $user->email }}</div>
                </div>
                <div class="user-body">
                    @if($user->profile)
                        <div class="user-detail"><i class="fas fa-phone"></i> <span>{{ $user->profile->phone ?? 'N/A' }}</span></div>
                        <div class="user-detail"><i class="fas fa-home"></i> <span>{{ $user->profile->address ?? 'N/A' }}</span></div>
                    @endif
                    <div class="user-detail"><i class="fas fa-calendar"></i> <span>Joined: {{ $user->created_at->format('M d, Y') }}</span></div>
                    
                    <form method="GET" action="{{ route('export', $user->id) }}" class="flex flex-col gap-2">
                        <select name="format" class="border p-2 rounded">
                            <option value="json">JSON</option>
                            <option value="csv">CSV</option>
                            <option value="xml">XML</option>
                        </select>
                        <div class="card-actions">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i> Export</button>
                            <a href="{{ route('users.show', $user->id) }}" class="btn bg-gray-500 text-white"><i class="fas fa-eye"></i> View</a>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="pagination">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</body>
</html>