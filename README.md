### Installtion

- **php version => 8.3 && node version => 21**
- cd {{download_path}}
- composer install && npm install
- php artisan migrate  => Create a database using artisan command.
- php artisan db:seed  => Create seed for 10000 users, 100 admins
- php artisan db:seed --class=TaskSeeder (optional)


## one command
- chmod +x setup_and_run.sh
- ./setup_and_run.sh --with-migrations --with-seeds
