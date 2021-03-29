# Q/A app made with Laravel and the Artisan Console

#### Run the App
Clone the project to your local, run `composer install`.  
To use it, run `docker-compose up -d` in the project folder. To enter the PHP container use `docker exec -it <name_of_php_container> sh`
Run `php artisan qanda:interactive` to start the console App.  
Run `php artisan qanda:reset` to remove all previous progresses.  
Run `vendor/bin/phpunit` to run the tests.

### Laravel Functionalities Used in this App
- Commands, Events, Migrations, Facade, Factories, Seeders, Observer.
- To allow the user to practice the questions immediately, without having to add questions, by running `docker-compose up -d` 
  for the first time, `docker-compose.yml` will run `php artisan migrate --seed` which will create the tables in the database and generate them 
  with 3 questions/answers.
- It is possible to reset the database by running `php artisan migrate:refresh`. After that, run `php artisan qanda:interactive` 
  and use the app again, but now, without questions/answers.  

### Database
- MySQL
- Eloquent ORM to work with a database, where the tables have a corresponding "Model" that is used to interact with that table.
- The Relationship is defined in the Models. In this case, is a Many to Many relationship between User and Question. 
  A user may answer many questions, where the questions are also answered by other users.

### Design Pattern
- Repository Design Pattern to separate data access from business logic.
- Facade Design Pattern to have the benefit of a concise syntax. In this case, `ConsoleOutput` Facade was created
to simple use the writeln function without need to import any classes in order to use it.
  
### App Features
The User can do:
- Add a question and the answer to that question that will be stored in the database.
- View previously entered answers.
- Choose from the previously given questions which one he wants to practice.
- Practice a question which will be checked against the previously given answer.
- See a list of all questions, and sees his progress for each question.
- See overview final progress when completing all questions.
- Go back or exit the interactive console through specific options in the menu at every point.
- Remove all previous progresses