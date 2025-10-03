# paybis-tech-task

Symfony API for cryptocurrency rates tracking with Docker support.

## Quick Start

```bash
./start.sh
```

This script will:
- Create `.env` file from `.env.dist` if it doesn't exist
- Build and start Docker containers
- Install Composer dependencies
- Run database migrations
- Start the scheduler service

## Services

After running `./start.sh`, the following services will be available:

- **API:** http://localhost:8080
- **Dozzle (Log Viewer):** http://localhost:8888
- **MySQL:** localhost:3306

## API Endpoints

- `GET /api/rates/last-24h?pair=EUR/BTC` - Get rates for the last 24 hours
- `GET /api/rates/day?pair=EUR/BTC&date=2025-10-03` - Get rates for a specific day

**Supported currency pairs:**
- EUR/BTC
- EUR/ETH
- EUR/LTC

## Monitoring with Dozzle

Dozzle is a real-time log viewer for Docker containers.

**Access Dozzle Web UI:** http://localhost:8888

## Viewing Logs

### Using Dozzle (Recommended)
Open http://localhost:8888 in your browser to view logs in a beautiful web interface.

### Using Docker CLI
```bash
# View all service logs
docker-compose logs -f

# View specific service logs
docker-compose logs -f php
docker-compose logs -f scheduler
docker-compose logs -f nginx
docker-compose logs -f mysql

## Running Tests

The project includes comprehensive unit and functional tests using PHPUnit.

```bash
# Run all tests
docker-compose exec php php bin/phpunit

# Run tests with coverage (requires xdebug)
docker-compose exec php php bin/phpunit --coverage-html coverage

# Run specific test file
docker-compose exec php php bin/phpunit tests/Unit/CurrencyRate/Domain/RateTest.php

# Run tests in a specific directory
docker-compose exec php php bin/phpunit tests/Unit
docker-compose exec php php bin/phpunit tests/Functional
```

**Test Coverage:**
- Unit tests for domain entities (Rate, PairRate, CurrencyPair)
- Unit tests for mappers and validators
- Functional tests for API endpoints

## Useful Commands

```bash
# Stop all services
docker-compose down

# Restart a specific service
docker-compose restart scheduler

# Rebuild containers
docker-compose up -d --build

# Access PHP container shell
docker-compose exec php bash

# Run Symfony console commands
docker-compose exec php php bin/console [command]

## Architecture

The application follows Domain-Driven Design (DDD) principles with the following modules:

- **CurrencyRate** - Domain logic for currency rates
- **Scheduler** - Periodic tasks using Symfony Scheduler

## Tech Stack

- PHP 8.4
- Symfony 7.1
- MySQL 9.1
- Docker & Docker Compose
- Nginx 1.27
