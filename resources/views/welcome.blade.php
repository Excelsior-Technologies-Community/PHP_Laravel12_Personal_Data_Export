<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personal Data Export</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: 'Poppins', sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .card{
            background:#fff;
            width:420px;
            padding:35px;
            border-radius:15px;
            box-shadow:0 15px 40px rgba(0,0,0,0.2);
            text-align:center;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {opacity:0; transform: translateY(20px);}
            to {opacity:1; transform: translateY(0);}
        }

        h2{
            margin-bottom:10px;
            color:#333;
        }

        p{
            font-size:14px;
            color:#777;
            margin-bottom:25px;
        }

        .fields{
            text-align:left;
            margin-bottom:20px;
        }

        .fields label{
            display:flex;
            align-items:center;
            margin-bottom:12px;
            cursor:pointer;
            padding:10px;
            border-radius:8px;
            transition:0.3s;
        }

        .fields label:hover{
            background:#f3f4f6;
        }

        input[type="checkbox"]{
            margin-right:10px;
            transform: scale(1.2);
            accent-color:#667eea;
        }

        .btn{
            width:100%;
            padding:12px;
            border:none;
            border-radius:8px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color:white;
            font-size:15px;
            cursor:pointer;
            transition:0.3s;
        }

        .btn:hover{
            opacity:0.9;
            transform: translateY(-1px);
        }

        .history{
            margin-top:18px;
            display:inline-block;
            font-size:14px;
            color:#667eea;
            text-decoration:none;
            font-weight:500;
        }

        .history:hover{
            text-decoration:underline;
        }

        .footer{
            margin-top:20px;
            font-size:12px;
            color:#aaa;
        }

    </style>
</head>

<body>

<div class="card">

    <h2>Export Your Data</h2>
    <p>Select the information you want to download</p>

    <form method="GET" action="/export/1">

        <div class="fields">

            <label>
                <input type="checkbox" name="fields[]" value="name" checked>
                Full Name
            </label>

            <label>
                <input type="checkbox" name="fields[]" value="email" checked>
                Email Address
            </label>

            <label>
                <input type="checkbox" name="fields[]" value="phone">
                Phone Number
            </label>

            <label>
                <input type="checkbox" name="fields[]" value="address">
                Address
            </label>

        </div>

        <button class="btn">Download Data</button>

    </form>

    <a href="/history" class="history">View Export History →</a>

    <div class="footer">
        Secure • GDPR Ready
    </div>

</div>

</body>
</html>