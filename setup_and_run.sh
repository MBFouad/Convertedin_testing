#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# Check if .env file exists
if [ ! -f .env ]; then
    echo ".env file not found. Please create and configure your .env file."
    exit 1
fi

# Load environment variables from .env file
export $(cat .env | grep -v ^# | xargs)

# Function to print usage instructions
print_usage() {
    echo "Usage: $0 [OPTIONS]"
    echo "Options:"
    echo "  --with-migrations      Run migrations"
    echo "  --with-seeds           Run seeders"
    echo "  --help                 Show this help message"
}

# Parse arguments
RUN_MIGRATIONS=false
RUN_SEEDS=false
while [ "$1" != "" ]; do
    case $1 in
        --with-migrations )    RUN_MIGRATIONS=true
                                ;;
        --with-seeds )         RUN_SEEDS=true
                                ;;
        --help )               print_usage
                                exit 0
                                ;;
        * )                    print_usage
                                exit 1
    esac
    shift
done

# Create the database if it doesn't exist
echo "Creating the database... \`$DB_DATABASE\` "
mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS \`$DB_DATABASE\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS \`Convertedin_testing\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Install PHP dependencies
echo "Installing PHP dependencies..."
composer install

# Generate application key
echo "Generating application key..."
php artisan key:generate

# Run migrations if specified
if [ "$RUN_MIGRATIONS" = true ]; then
    echo "Running migrations..."
    php artisan migrate
fi

# Run seeders if specified
if [ "$RUN_SEEDS" = true ]; then
    echo "Running seeders..."
    php artisan db:seed
    echo "Running TaskSeeder seeders..."
    php artisan db:seed --class=TaskSeeder
fi

# Install NPM dependencies and build assets
echo "Installing NPM dependencies..."
npm install

echo "Building assets..."
npm run build

# Start the Laravel development server
echo "Starting Laravel development server..."
php artisan serve
