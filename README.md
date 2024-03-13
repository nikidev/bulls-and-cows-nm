# The Bulls And Cows Game (with modifications) 

## Table of Contents
 - [Screenshots](#screenshots)
    - [Login or Register](#login-or-register)
    - [Login](#login)
    - [Main screen with playground and top 10 leaderboard](#main-screen-with-playground-and-top-10-leaderboard)
    - [Playground: (Game started)](#playground-game-started)
    - [Playground: (Game In progress)](#playground-game-in-progress)
    - [Playground: (Game Won) (Automatic reload)](#playground-game-won-automatic-reload)
    - [Playground: (Game Lost) (Automatic reload)](#playground-game-lost-automatic-reload)
    - [Playground: (Client side validation)](#playground-client-side-validation)
    - [Playground: (Server side validation)](#playground-server-side-validation)
 - [Important files and directories where to look](#important-files-and-directories-where-to-look)
 - [Implemented Modifications (number input and number generation)](#implemented-modifications-number-input-and-number-generation)
 - [Used technologies](#used-technologies)
 - [Installation instructions](#installation-instructions)
 - [Additional planned features](#additional-planned-features)

## Screenshots:
### Login or Register
![image](https://github.com/nikidev/bulls-and-cows-nm/assets/6606146/aafbae3f-4795-48a3-a50a-e13d1eb5cf3f)

### Login
![image](https://github.com/nikidev/bulls-and-cows-nm/assets/6606146/a5daa263-38ad-4452-b06c-539aa3002456)

### Main screen with playground and top 10 leaderboard
![image](https://github.com/nikidev/bulls-and-cows-nm/assets/6606146/e60bac56-5cb7-443a-9491-dd35b98fc403)

### Playground: (Game started)
![image](https://github.com/nikidev/bulls-and-cows-nm/assets/6606146/d5f87054-3b76-43d5-84aa-72f0f16cc708)

### Playground: (Game In progress)
![image](https://github.com/nikidev/bulls-and-cows-nm/assets/6606146/e7c35d89-6977-4704-a132-7077ebcc3fb0)

### Playground: (Game Won) (Automatic reload)
![image](https://github.com/nikidev/bulls-and-cows-nm/assets/6606146/19534905-609f-4f70-abba-d6aa463c291b)

### Playground: (Game Lost) (Automatic reload)
![image](https://github.com/nikidev/bulls-and-cows-nm/assets/6606146/fed0be6e-ef8c-4a49-8544-df55ae6d19e6)

### Playground: (Client side validation)
![image](https://github.com/nikidev/bulls-and-cows-nm/assets/6606146/15752c8c-9b25-411a-b18a-75406770fbed)

### Playground: (Server side validation)
![image](https://github.com/nikidev/bulls-and-cows-nm/assets/6606146/581c6a82-2314-4e87-8bca-73ead7a01e32)


## Important files and directories where to look:
- [app/Http/Controllers/CoreGameLogicController.php](https://github.com/nikidev/bulls-and-cows-nm/blob/main/app/Http/Controllers/CoreGameLogicController.php)
- [app/Http/Controllers/GameSessionController.php](https://github.com/nikidev/bulls-and-cows-nm/blob/main/app/Http/Controllers/GameSessionController.php)
- [app/Http/Requests/GuessNumberRequest.php](https://github.com/nikidev/bulls-and-cows-nm/blob/main/app/Http/Requests/GuessNumberRequest.php)
- [app/Enums/GameStatus.php](https://github.com/nikidev/bulls-and-cows-nm/blob/main/app/Enums/GameStatus.php)
- [resources/js/components/forms.js](https://github.com/nikidev/bulls-and-cows-nm/blob/main/resources/js/components/forms.js)
- [resources/views/dashboard.blade.php](https://github.com/nikidev/bulls-and-cows-nm/blob/main/resources/views/dashboard.blade.php)
- [resources/views/welcome.blade.php](https://github.com/nikidev/bulls-and-cows-nm/blob/main/resources/views/welcome.blade.php)
- [resources/views/layouts/navigation.blade.php](https://github.com/nikidev/bulls-and-cows-nm/blob/main/resources/views/layouts/navigation.blade.php)
- [database/migrations/2022_10_18_150530_create_game_sessions_table.php](https://github.com/nikidev/bulls-and-cows-nm/blob/main/database/migrations/2022_10_18_150530_create_game_sessions_table.php)
- [app/Models/GameSession.php](https://github.com/nikidev/bulls-and-cows-nm/blob/main/app/Models/GameSession.php)
- [app/Models/User.php](https://github.com/nikidev/bulls-and-cows-nm/blob/main/app/Models/User.php)
- [routes/web.php](https://github.com/nikidev/bulls-and-cows-nm/blob/main/routes/web.php)

## Implemented Modifications (number input and number generation)
- [x] User should find out randomly generated 4 unique digit number
- [x] The digits in use should have the following limitation:
- [x] if in use, digits 1 and 8 should be right next to each other
- [x] if in use, digits 4 and 5 shouldn't be on even index / position

## Used technologies:
 - PHP 8.1
 - Laravel 9
 - Laravel Breeze (and Authentication)
 - jQuery (Ajax)
 - Vite (build tool)
 - TailWind CSS
 - Laravel Sail (Docker and Docker-compose)
 - MySQL
 - Eloquent ORM
 - Composer
 - NPM

## Installation instructions:
 1. Prerequisites - Install Docker and Docker-compose
 2. Clone the current repository.
 3. [Install the composer dependencies](https://laravel.com/docs/9.x/sail#installing-composer-dependencies-for-existing-projects)
 4. Change the .env configuration for DB (mysql)
 5. Run the migrations (sail artisan migrate)
 6. sail npm install
 7. sail up
 8. sail npm run dev (in a separate terminal)

## Additional planned features:
 - Add a reload button to request the Top 10 leaderboard results via AJAX (not by page refresh)
 - Deploy the application on VPS

## Made by: Nikolay Mikov
