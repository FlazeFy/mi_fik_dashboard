# MI-FIK Dashboard Documentation
https://mifik.id/

========================= Command =========================
# First Run
> composer install
> composer update
> php artisan key:generate
> php artisan storage:link
> php artisan serve

# Run Application
> php artisan serve

# Run Application On Custom Pors
> php artisan serve # port=****
ex : php artisan serve # port=9000

# Run Migrations
> php artisan migrate

# Run Seeder
> php artisan db:seed # class=DatabaseSeeder

# Make Controller
> php artisan make:controller <NAMA-Controller>Controller --resource

# Make Model
> php artisan make:model <NAMA-Model>

# Make Seeder
> php artisan make:seeder <NAMA-TABEL>Seeder

# Make Factories
> php artisan make:factory <NAMA-TABEL>Factory

# Make Migrations
> php artisan make:migration create_<NAMA-TABEL>_table

# Make Migrations on Specific File
> php artisan migrate # path=/database/migrations/<NAMA-FILE>.php

# Make Middleware
> php artisan make:middleware <NAMA-MIDDLEWARE>

# Make Deploy
> php artisan route:cache
> php artisan cache:clear
> php artisan route:clear

========================= File Directory =========================
# Assets
CSS
Directory               : public/css
Access Local Path       : http://127.0.0.1:8000/css/<< CSS_FILENAME >>.css
Access Global Path      : http://mifik.id/css/<< CSS_FILENAME >>.css

# API Controller
Directory               : app/Http/Controllers/Api

# Normal Controller
Directory               : app/Http/Controllers/<< MENU_NAME/SUBMENU_NAME >>

# Model
Directory               : app/Http/Models/<< DB_TABLE_NAME >>

# View
Directory               : app/Http/Controllers/<< MENU_NAME/SUBMENU_NAME >>

============================ Rules ===============================
# Penamaan CSS 
> << CONTEXT_NAME >>_v1.0.css                       <= Iterate

# Penamaan JS
> << CONTEXT_NAME >>_v1.0.js                        <= Iterate

# Secret JSON
> << RANDOM_STRING >>_v1.0.js                       <= Iterate

==================================================================
Last Updated : 14 Apr 2023