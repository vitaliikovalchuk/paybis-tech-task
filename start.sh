#!/bin/bash

set -e

echo "🚀 Starting Paybis Tech Task application..."

# Copy .env.dist to .env if .env doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file from .env.dist..."
    cp .env.dist .env
    echo "✅ .env file created. Please review and update values if needed."
else
    echo "ℹ️  .env file already exists, skipping creation."
fi

# Build and start Docker containers
echo "🐳 Building and starting Docker containers..."
docker-compose up -d --build

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
docker-compose exec -T mysql sh -c 'until mysqladmin ping -h localhost --silent; do sleep 1; done'

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
docker-compose exec -T php composer install

# Run database migrations
echo "🗄️  Running database migrations..."
docker-compose exec -T php php bin/console doctrine:migrations:migrate --no-interaction

# Start the scheduler
echo "⏰ Starting scheduler service..."
docker-compose up -d scheduler

echo ""
echo "✅ Application started successfully!"
echo ""
echo "📍 Available services:"
echo "   - API: http://localhost:8080"
echo "   - Dozzle (Log Viewer): http://localhost:8888"
echo "   - MySQL: localhost:${MYSQL_PORT:-3306}"
echo ""
echo "📊 Monitoring:"
echo "   - Open Dozzle at http://localhost:8888 for real-time log viewing"
echo "   - Monitor scheduler, PHP, Nginx, and MySQL logs in one place"
echo ""
echo "📋 Useful commands:"
echo "   - View logs in browser: Open http://localhost:8888"
echo "   - View logs in terminal: docker-compose logs -f"
echo "   - View scheduler logs: docker-compose logs -f scheduler"
echo "   - Stop services: docker-compose down"
echo ""
