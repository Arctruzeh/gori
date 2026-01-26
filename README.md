# Genius or Idiot

Live demo: https://ajlato-gori.up.railway.app/

This project is a demonstration of OOP (Object-Oriented Programming) in PHP. It is a simple quiz game called "Genius or Idiot" where the player must correctly identify the number of body parts (legs, arms, eyes, wings, tails) for various creatures.

## Game Elements

The game is built using the following OOP concepts and elements:

- **Classes & Inheritance**: An abstract `Creature` base class defines the blueprint for all animals. Specific creatures (`Dog`, `Human`, `Spider`, `Bird`, `Centipede`) extend this class.
- **Polymorphism**: The `Game` class treats all creatures uniformly, calling `getPartCount()` on any creature object to retrieve the correct answer dynamically.
- **Encapsulation**: Game logic and score management are encapsulated in the `Game` and `ScoreManager` classes respectively.

## How to Play

The game will ask a question like "How many legs does a spider have?". Select the correct number from the options provided.
- **Correct Answer**: Displays a genius-themed message (e.g., "You are a genius!") and increases your score.
- **Wrong Answer**: Displays an idiot-themed message (e.g., "You are an idiot!") and records a failure.
