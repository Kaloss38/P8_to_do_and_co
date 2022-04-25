ToDoList
========

## Context

ToDoList is an improved version of an existing project.
As indicated by his name, you create, edit or delete a task ! many differents roles are accessible, particulary admin role who can create users with many different roles.

Unit and functionnal tests are implemented with phpUnit during this iteration.

## List of improvements

- Update Symfony 3.4 to 6.0
- One user can have one or many tasks
- Set authentication and roles
- User can delete his own tasks
- Admin can delete his own tasks and that of anonymous user too
- Add Functionnal and Unit test

## Getting started

### requirements

- PHP 8.1.1 (with xdebug extensions enabled)
- Composer
- Symfony @CLI
- mySQL

### Setup / Installation

1. Clone or download this repository in a new folder "ToDoList".

2. Then run command line `composer install`

3. Create a new database : change the value of DATABASE_URL in the file .env to match with your database parameters.

4. Run symfony `console doctrine:database:create` in command to create your database.

5. Run symfony `console doctrine:schema:update` in command line to apply application schema in your database.

6. Run symfony `console doctrine:migrations:migrate` to apply all migrations files.

7. Generate user and tasks fixtures for feeding database : `symfony console doctrine:fixtures:load`

8. Repeat points 3 to 7 for the test environment (the file to modify is .env.test and add the option --env=test to the commands).

## Packages

- phpUnit
- php-cs-fixer
- phpstan
- grumPhp

## Original Repository

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1
