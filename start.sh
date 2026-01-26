#!/bin/bash

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
  echo "Error: Docker is not running."
  echo "Please start Docker Desktop and try again."
  exit 1
fi

echo "Starting Database..."
docker compose up -d

echo "Waiting for Database to be ready..."
sleep 5

echo "Initializing Database Schema..."
php database/setup_db.php

echo "Starting PHP Server..."
echo "Game is running at http://localhost:8000"
php -S localhost:8000
