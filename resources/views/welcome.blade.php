<!DOCTYPE html>
<html>

<head>
    <title>Personal Data Export</title>

    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            text-align: center;
            margin-top: 120px;
        }

        .box {
            background: white;
            padding: 40px;
            width: 420px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            background: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background: #218838;
        }

        .msg {
            margin-top: 20px;
            font-weight: bold;
            display: none;
        }

        .loading {
            color: green;
        }

        .success {
            color: blue;
        }
    </style>

</head>

<body>

    <div class="box">

        <h2>Export Personal Data</h2>

        <button class="btn" onclick="exportData()">Export Data</button>

        <div class="msg loading" id="loadingMsg">
            Export started! File downloading...
        </div>

        <div class="msg success" id="successMsg">
            Download completed successfully!
        </div>

    </div>

    <script>

        function exportData() {
            // show loading message
            document.getElementById("loadingMsg").style.display = "block";

            // start download
            window.location.href = "/export/1";

            // simulate download complete
            setTimeout(function () {

                document.getElementById("loadingMsg").style.display = "none";
                document.getElementById("successMsg").style.display = "block";

            }, 3000); // 3 seconds
        }

    </script>

</body>

</html>