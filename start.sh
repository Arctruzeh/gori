#!/bin/bash

if ! docker info > /dev/null 2>&1; then
  echo "Error: Docker is not running."
  echo "Please start Docker Desktop and try again."
  exit 1
fi

echo "Starting Gori (PHP + PostgreSQL) in Docker..."
echo "Game will be available at http://localhost:8000"
docker compose up --build
