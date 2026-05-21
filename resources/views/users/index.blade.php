<!DOCTYPE html>
<html>
<head>
    <title>Users Management</title>
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
            max-width: 1200px;
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
        
        .back-btn {
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
        
        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .user-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .user-card:hover {
            transform: translateY(-5px);
        }
        
        .user-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 20px;
            color: white;
            text-align: center;
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 36px;
        }
        
        .user-name {
            font-size: 20px;
            font-weight: 600;
        }
        
        .user-email {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .user-body {
            padding: 20px;
        }
        
        .user-detail {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            color: #555;
            font-size: 14px;
        }
        
        .user-detail i {
            width: 20px;
            color: #667eea;
        }
        
        .card-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
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
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="/" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Export
        </a>
        
        <div class="header">
            <h1><i class="fas fa-users"></i> Users Management</h1>
            <p>View and manage all users in the system</p>
        </div>
        
        <div class="users-grid">
            @foreach($users as $user)
            <div class="user-card">
                <div class="user-header">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-email">{{ $user->email }}</div>
                </div>
                <div class="user-body">
                    @if($user->profile)
                        <div class="user-detail">
                            <i class="fas fa-phone"></i>
                            <span>{{ $user->profile->phone ?? 'Not provided' }}</span>
                        </div>
                        <div class="user-detail">
                            <i class="fas fa-home"></i>
                            <span>{{ $user->profile->address ?? 'Not provided' }}</span>
                        </div>
                    @endif
                    <div class="user-detail">
                        <i class="fas fa-calendar"></i>
                        <span>Joined: {{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="card-actions">
                        <a href="/export/{{ $user->id }}" class="btn btn-primary">
                            <i class="fas fa-download"></i> Export
                        </a>
                        <a href="/users/{{ $user->id }}" class="btn btn-secondary">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="pagination">
            {{ $users->links() }}
        </div>
    </div>
</body>
</html>