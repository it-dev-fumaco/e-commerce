FUMACO E-Commerce Website

Instructions using GUI and Commands - For Development Site

    Clone Project https://github.com/it-dev-fumaco/e-commerce.git
    Locate Project Folder on your local PC
    Open CMD and change directory to your Local project folder
    Type copy .env.example .env
    Setup database connections in .env file
        DB_CONNECTION=mysql
        DB_HOST=10.0.48.125
        DB_PORT=3306
        DB_DATABASE=fumacoco_db
        DB_USERNAME=web
        DB_PASSWORD=fumaco
    Type php artisan key:generate
    Type php artisan optimize:clear
    Type php artisan route:clear
    Type php artisan serve
    Access it via URL using your IP or localhost with default port = 8000
    Open VSCode

Note: Please specify the Summary and Description on your every commit﻿ Finalize and Review Your Code before Pushing to Dev Branch
