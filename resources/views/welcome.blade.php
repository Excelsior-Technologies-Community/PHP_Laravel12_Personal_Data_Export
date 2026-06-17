<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personal Data Export</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Poppins', sans-serif; }
        body { min-height:100vh; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #667eea, #764ba2); padding: 20px; }
        .card { background:#fff; width:500px; padding:40px; border-radius:20px; box-shadow:0 20px 60px rgba(0,0,0,0.3); animation: fadeIn 0.6s ease; }
        @keyframes fadeIn { from {opacity:0; transform: translateY(20px);} to {opacity:1; transform: translateY(0);} }
        h2 { margin-bottom:10px; color:#333; font-size:28px; }
        .subtitle { color:#777; margin-bottom:30px; font-size:14px; }
        .user-selector { margin-bottom:25px; padding:15px; background:#f8f9fa; border-radius:12px; }
        .user-selector select { width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:10px; }
        .format-selector { margin-bottom:25px; display:flex; gap:15px; }
        .format-option { flex:1; padding:10px; border:2px solid #e0e0e0; border-radius:10px; text-align:center; cursor:pointer; }
        .format-option.active { border-color:#667eea; background:#f0f4ff; }
        .btn { width:100%; padding:14px; border:none; border-radius:12px; background: linear-gradient(135deg, #667eea, #764ba2); color:white; font-size:16px; cursor:pointer; margin-bottom:15px; }
        .password-field { margin-bottom:20px; }
        .password-field input { width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:10px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 10px; margin-bottom: 20px; text-align: center; border: 1px solid #f5c6cb; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h2><i class="fas fa-download"></i> Export Your Data</h2>
        <div class="subtitle">Select the information you want to download</div>

        @if($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/export/1" id="exportForm">
            @csrf
            <div class="user-selector">
                <label>Select User:</label>
                <select name="user_id" id="user_id" required>
                    <option value="1">User #1 - John Doe</option>
                    <option value="2">User #2 - Jane Smith</option>
                    <option value="3">User #3 - Bob Johnson</option>
                </select>
            </div>

            <div class="password-field">
                <label>Confirm Password:</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="format-selector">
                <label class="format-option active">
                    <input type="radio" name="format" value="json" checked style="display:none;">
                    <i class="fab fa-js"></i> JSON
                </label>
                <label class="format-option">
                    <input type="radio" name="format" value="csv" style="display:none;">
                    <i class="fas fa-file-csv"></i> CSV
                </label>
                <label class="format-option">
                    <input type="radio" name="format" value="xml" style="display:none;">
                    <i class="fab fa-xml"></i> XML
                </label>
            </div>

            <button type="submit" class="btn"><i class="fas fa-download"></i> Download Data</button>
        </form>

        <form method="POST" action="/export/bulk" id="bulkForm">
            @csrf
            <input type="hidden" name="user_ids" id="bulk_user_ids">
            <input type="hidden" name="format" id="bulk_format">
            <input type="hidden" name="password" id="bulk_password">
            <button type="button" class="btn" style="background: linear-gradient(135deg, #6c757d, #495057);" onclick="showBulkExport()">
                <i class="fas fa-layer-group"></i> Bulk Export
            </button>
        </form>

        <div style="text-align: center;">
            <a href="/history" style="color:#667eea; text-decoration:none;">View Export History →</a>
        </div>
    </div>

    <script>
        document.querySelectorAll('.format-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.format-option').forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                this.querySelector('input').checked = true;
            });
        });

        function showBulkExport() {
            const userIds = prompt('Enter user IDs (e.g., 1,2,3):');
            const password = prompt('Enter Admin Password to confirm:');
            if (userIds && password) {
                document.getElementById('bulk_user_ids').value = JSON.stringify(userIds.split(',').map(id => parseInt(id.trim())));
                document.getElementById('bulk_format').value = document.querySelector('input[name="format"]:checked').value;
                document.getElementById('bulk_password').value = password;
                document.getElementById('bulkForm').submit();
            }
        }

        document.getElementById('user_id').addEventListener('change', function() {
            document.getElementById('exportForm').action = `/export/${this.value}`;
        });
    </script>
</body>
</html>