# Genius or Idiot

Live demo: https://ajlato-gori.up.railway.app/

This project is a demonstration of OOP (Object-Oriented Programming) in PHP. It is a simple quiz game called "Genius or Idiot" where the player must correctly identify the number of body parts (legs, arms, eyes, wings, tails, etc.) for various creatures.

## Game Elements & Architecture

```text
gori/
├── src/
│   ├── Config/            # Environment-driven DatabaseConfig
│   ├── Database/          # PDO Connection & SchemaInitializer
│   ├── Domain/            # Creature hierarchy, CreatureRegistry, Game, ScoreManager, Rank, RandomizerInterface
│   ├── Http/              # Request, Response, Router, SessionInterface/Session, Controllers
│   ├── Model/             # User & Score repositories (PDO PostgreSQL)
│   ├── View/              # Layout-aware PHP template Renderer
│   └── Kernel.php         # Dependency injection & routing composition root
├── views/                 # Game, auth, profile, leaderboard templates
├── database/              # PostgreSQL schema & setup_db.php
├── tests/                 # PHPUnit Fakes, Unit, and Feature tests
└── index.php              # Entrypoint
```

- **Classes & Inheritance**: An abstract `Creature` base class defines the blueprint for 15 animal subclasses (`Dog`, `Human`, `Spider`, `Bird`, `Centipede`, etc.).
- **Polymorphism**: The `Game` class treats all creatures uniformly via `getPartCount(BodyPart)`.
- **Testability & Encapsulation**: `SessionInterface`, `UserRepositoryInterface`, `ScoreRepositoryInterface`, and `RandomizerInterface` decouple domain and controller logic from global PHP state and PostgreSQL.

## How to Play

The game asks questions like `"How many legs does a Spider have?"`. Select the correct number from the choices provided:
- **Correct Answer**: Displays a genius-themed message and increases your streak and score (`10 + streak * 2`).
- **Wrong Answer**: Displays an idiot-themed message, shows the correct answer, and resets your streak.

## Local Development & Tests

### Docker (PHP + PostgreSQL)
```bash
./start.sh
# or:
docker compose up --build
```
Open [http://localhost:8000](http://localhost:8000).

### Running Tests
```bash
composer install
composer test
```
