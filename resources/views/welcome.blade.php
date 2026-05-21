<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personal Data Export</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: 'Poppins', sans-serif;
        }

        body{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 20px;
        }

        .card{
            background:#fff;
            width:500px;
            padding:40px;
            border-radius:20px;
            box-shadow:0 20px 60px rgba(0,0,0,0.3);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {opacity:0; transform: translateY(20px);}
            to {opacity:1; transform: translateY(0);}
        }

        h2{
            margin-bottom:10px;
            color:#333;
            font-size:28px;
        }

        .subtitle{
            color:#777;
            margin-bottom:30px;
            font-size:14px;
        }

        .user-selector{
            margin-bottom:25px;
            padding:15px;
            background:#f8f9fa;
            border-radius:12px;
        }

        .user-selector label{
            display:block;
            margin-bottom:8px;
            font-weight:500;
            color:#555;
        }

        .user-selector select{
            width:100%;
            padding:12px;
            border:2px solid #e0e0e0;
            border-radius:10px;
            font-size:14px;
            background:white;
            cursor:pointer;
        }

        .format-selector{
            margin-bottom:25px;
            display:flex;
            gap:15px;
        }

        .format-option{
            flex:1;
            padding:10px;
            border:2px solid #e0e0e0;
            border-radius:10px;
            text-align:center;
            cursor:pointer;
            transition:all 0.3s;
        }

        .format-option input{
            display:none;
        }

        .format-option.active{
            border-color:#667eea;
            background:#f0f4ff;
        }

        .format-option i{
            font-size:24px;
            margin-bottom:5px;
            display:block;
        }

        .fields{
            margin-bottom:25px;
        }

        .fields label{
            display:flex;
            align-items:center;
            margin-bottom:12px;
            cursor:pointer;
            padding:10px;
            border-radius:10px;
            transition:0.3s;
        }

        .fields label:hover{
            background:#f3f4f6;
        }

        input[type="checkbox"]{
            margin-right:12px;
            transform: scale(1.2);
            accent-color:#667eea;
        }

        .btn{
            width:100%;
            padding:14px;
            border:none;
            border-radius:12px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color:white;
            font-size:16px;
            font-weight:500;
            cursor:pointer;
            transition:0.3s;
            margin-bottom:15px;
        }

        .btn:hover{
            opacity:0.9;
            transform: translateY(-2px);
        }

        .btn-secondary{
            background: linear-gradient(135deg, #6c757d, #495057);
        }

        .history-link{
            display:inline-block;
            font-size:14px;
            color:#667eea;
            text-decoration:none;
            font-weight:500;
            margin-top:15px;
        }

        .history-link:hover{
            text-decoration:underline;
        }

        .footer{
            margin-top:25px;
            font-size:12px;
            color:#aaa;
            text-align:center;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2><i class="fas fa-download"></i> Export Your Data</h2>
        <div class="subtitle">Select the information you want to download</div>

        <form method="GET" action="/export/1" id="exportForm">
            <div class="user-selector">
                <label><i class="fas fa-user"></i> Select User:</label>
                <select name="user_id" id="user_id" required>
                    <option value="1">User #1 - John Doe</option>
                    <option value="2">User #2 - Jane Smith</option>
                    <option value="3">User #3 - Bob Johnson</option>
                </select>
            </div>

            <div class="format-selector">
                <label class="format-option active">
                    <input type="radio" name="format" value="json" checked>
                    <i class="fab fa-js"></i>
                    JSON
                </label>
                <label class="format-option">
                    <input type="radio" name="format" value="csv">
                    <i class="fas fa-file-csv"></i>
                    CSV
                </label>
                <label class="format-option">
                    <input type="radio" name="format" value="xml">
                    <i class="fab fa-xml"></i>
                    XML
                </label>
            </div>

            <div class="fields">
                <label>
                    <input type="checkbox" name="fields[]" value="name" checked>
                    <i class="fas fa-user"></i> Full Name
                </label>

                <label>
                    <input type="checkbox" name="fields[]" value="email" checked>
                    <i class="fas fa-envelope"></i> Email Address
                </label>

                <label>
                    <input type="checkbox" name="fields[]" value="phone">
                    <i class="fas fa-phone"></i> Phone Number
                </label>

                <label>
                    <input type="checkbox" name="fields[]" value="address">
                    <i class="fas fa-home"></i> Address
                </label>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-download"></i> Download Data
            </button>
        </form>

        <form method="POST" action="/export/bulk" id="bulkForm">
            @csrf
            <input type="hidden" name="user_ids" id="bulk_user_ids">
            <input type="hidden" name="format" id="bulk_format">
            <button type="button" class="btn btn-secondary" onclick="showBulkExport()">
                <i class="fas fa-layer-group"></i> Bulk Export (Select Users)
            </button>
        </form>

        <div style="text-align: center;">
            <a href="/history" class="history-link">
                <i class="fas fa-history"></i> View Export History →
            </a>
        </div>

        <div class="footer">
            <i class="fas fa-shield-alt"></i> Secure • GDPR Ready
        </div>
    </div>

    <script>
        // Format selector styling
        document.querySelectorAll('.format-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.format-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                this.classList.add('active');
                this.querySelector('input').checked = true;
            });
        });

        function showBulkExport() {
            const userIds = prompt('Enter user IDs separated by commas (e.g., 1,2,3):');
            if (userIds) {
                const ids = userIds.split(',').map(id => parseInt(id.trim()));
                const format = document.querySelector('input[name="format"]:checked').value;
                document.getElementById('bulk_user_ids').value = JSON.stringify(ids);
                document.getElementById('bulk_format').value = format;
                document.getElementById('bulkForm').submit();
            }
        }

        // Update form action when user changes
        document.getElementById('user_id').addEventListener('change', function() {
            const userId = this.value;
            const format = document.querySelector('input[name="format"]:checked').value;
            document.getElementById('exportForm').action = `/export/${userId}`;
        });

        // Trigger initial setup
        document.getElementById('exportForm').action = `/export/${document.getElementById('user_id').value}`;
    </script>
</body>
</html>