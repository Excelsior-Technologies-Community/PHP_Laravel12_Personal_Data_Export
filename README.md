# PHP_Laravel12_Personal_Data_Export

## Project Description

PHP_Laravel12_Personal_Data_Export is a simple Laravel 12 application that demonstrates how to export a user's personal data into a downloadable file.

The project collects user information such as name, email, phone number, and address, converts the data into JSON format, and packages it into a ZIP file for download.

This functionality is useful for applications that must comply with data privacy regulations such as GDPR, where users can request a copy of their personal data.


## Features

- Export user personal data with a single click

- Convert user data into structured JSON format

- Automatically create a ZIP file for download

- Store user profile information separately

- Clean and simple Laravel MVC structure

- Simple UI with Export button and success message

- Demonstrates Laravel Eloquent relationships

- Beginner-friendly implementation for learning purposes


## Technologies Used

1. PHP 8+ – Backend programming language

2. Laravel 12 – PHP framework used to build the application

3. MySQL – Database to store user and profile data

4. Blade – Laravel templating engine for the frontend

5. JavaScript – Used to trigger the export and show messages

6. CSS – Used for basic styling of the page

7. ZipArchive – PHP library used to create ZIP files



---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Personal_Data_Export "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Personal_Data_Export

```

#### Explanation:

This command installs a fresh Laravel 12 application and creates a new project folder called PHP_Laravel12_Personal_Data_Export.




## STEP 2: Install Personal Data Export Package

### Install package:

```
composer require spatie/laravel-personal-data-export

```

#### Explanation:

This installs the Spatie Personal Data Export package, which helps export user data easily according to privacy standards.




## STEP 3: Publish Package Config

### Run:

```
php artisan vendor:publish --provider="Spatie\PersonalDataExport\PersonalDataExportServiceProvider"

```

### This will create config file:

```
config/personal-data-export.php

```

#### Explanation:

This command publishes the package configuration file so you can customize export settings if needed.





## STEP 4: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Personal_Data_Export
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Personal_Data_Export

```

### Run migration

```
php artisan migrate

```


#### Explanation:

This step connects Laravel with MySQL database and runs default migrations to create necessary tables like users.




## STEP 5:  Create Models & Migration

### Create model:

```
php artisan make:model Profile -m

```

### Migration Open : database/migrations/create_profiles_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('phone');
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};


```



### Run migration

```
php artisan migrate

```

#### Explanation:

This migration creates a profiles table that stores user profile details linked with the users table.

This command creates the profiles table in the database.




## STEP 6: Profile Model

### File: app/Models/Profile.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

```

#### Explanation:

This model represents the profiles table and defines a relationship with the User model.




## STEP 7: Update User Model

### File: app/Models/User.php

#### Add relation:

```
public function profile()
{
    return $this->hasOne(Profile::class);
}

```

#### Explanation:

This relationship allows Laravel to fetch a user's profile data easily using Eloquent ORM.




## STEP 8: Create Controller

### Run:

```
php artisan make:controller ExportController

```

### File: app/Http/Controllers/ExportController.php

```
<?php

namespace App\Http\Controllers;

use App\Models\User;
use ZipArchive;

class ExportController extends Controller
{
    public function export($id)
    {
        $user = User::with('profile')->findOrFail($id);

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->profile->phone ?? '',
            'address' => $user->profile->address ?? ''
        ];

        $fileName = "user-data-{$user->id}.zip";
        $zipPath = storage_path($fileName);

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {

            $zip->addFromString(
                'user-data.json',
                json_encode($data, JSON_PRETTY_PRINT)
            );

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}

```

#### Explanation:

#### This controller:

- Fetches user + profile data

- Converts data to JSON

- Creates a ZIP file

- Sends it as a download response.




## STEP 9: Add Route

### Open: routes/web.php

```
use App\Http\Controllers\ExportController;

Route::get('/export/{id}', [ExportController::class, 'export']);

```

#### Explanation:

This route allows users to trigger the export process by visiting /export/{id}.




## STEP 10: Seed Example Data

### Run tinker

```
php artisan tinker

```

### Create user

```
User::create([
'name' => 'Demo',
'email' => 'demo123@gmail.com',
'password' => bcrypt('123456')
]);

```

### Create profile

```
\App\Models\Profile::create([
    'user_id' => 1,
    'phone' => '9999999999',
    'address' => 'Ahmedabad'
]);

```

#### Explanation:

This step inserts sample user and profile data into the database for testing the export feature.





## STEP 11: Blade File

### Open: resources/views/welcome.blade.php

```
<!DOCTYPE html>
<html>
<head>
    <title>Personal Data Export</title>

    <style>

        body{
            font-family: Arial;
            background:#f2f2f2;
            text-align:center;
            margin-top:120px;
        }

        .box{
            background:white;
            padding:40px;
            width:420px;
            margin:auto;
            border-radius:10px;
            box-shadow:0px 0px 10px rgba(0,0,0,0.1);
        }

        .btn{
            background:#28a745;
            color:white;
            padding:12px 25px;
            border:none;
            border-radius:5px;
            font-size:16px;
            cursor:pointer;
        }

        .btn:hover{
            background:#218838;
        }

        .msg{
            margin-top:20px;
            font-weight:bold;
            display:none;
        }

        .loading{
            color:green;
        }

        .success{
            color:blue;
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

function exportData()
{
    // show loading message
    document.getElementById("loadingMsg").style.display="block";

    // start download
    window.location.href="/export/1";

    // simulate download complete
    setTimeout(function(){

        document.getElementById("loadingMsg").style.display="none";
        document.getElementById("successMsg").style.display="block";

    },3000); // 3 seconds
}

</script>

</body>
</html>

```




## STEP 12: Run Export

### Run:

```
php artisan serve

```

### Open browser:

```
http://127.0.0.1:8000

```

#### Download file will appear.

### Example JSON output:

```
{
"user": {
"name": "Demo",
"email": "demo123@gmail.com",
"created_at": "2026-03-06"
},
"profile": {
"phone": "9999999999",
"address": "Ahmedabad"
}
}

```




## Expected Output:


### Welcome Page:


<img width="1919" height="877" alt="Screenshot 2026-03-06 104331" src="https://github.com/user-attachments/assets/c4185151-7717-49a1-ba9b-24de46c55067" />


### Export Started:


<img width="1919" height="865" alt="Screenshot 2026-03-06 104357" src="https://github.com/user-attachments/assets/eabfe311-f545-421e-bf66-fd36c034e029" />


### Download Complete:


<img width="1424" height="864" alt="Screenshot 2026-03-06 104901" src="https://github.com/user-attachments/assets/2f43a7e3-ccc4-4fc8-89cc-3d67e4cd1ef6" />


### JSON Output:


<img width="1340" height="838" alt="Screenshot 2026-03-06 104045" src="https://github.com/user-attachments/assets/a3a88e78-1ca1-4740-96f4-d0efcab28e59" />



---


# Project Folder Structure:

```
PHP_Laravel12_Personal_Data_Export
│
├── app
│   │
│   ├── Http
│   │   └── Controllers
│   │       └── ExportController.php
│   │
│   └── Models
│       ├── User.php
│       └── Profile.php
│
│
├── config
│   └── personal-data-export.php
│
│
├── database
│   └── migrations
│       └── create_profiles_table.php
│
│
├── resources
│   └── views
│       └── welcome.blade.php
│
│
├── routes
│   └── web.php
│
│
├── .env
│
├── composer.json
│
└── artisan

```
