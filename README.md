## About Laravel
Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

 - Simple, fast routing engine.
 - Powerful dependency injection container.
 - Multiple back-ends for session and cache storage.
 - Expressive, intuitive database ORM.
 - Database agnostic schema migrations.
 - Robust background job processing.
 - Real-time event broadcasting.
 - Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Configuration Process in Local environments
Follow the next instructions to configure the Laravel project in your local environment. Ensure that you have the necessary tools like Composer, PHP, Laravel, and Artisan, as well as a DB manager like XAMPP or MariaDB:

 - Update the .env file and set up your database info. If you don't have the .env file, you can copy .env.example and rename it. Update the database details in the .env file.
 - Open a terminal and navigate to the application folder.
 - Run composer install to install the required packages.
 - Run php artisan migrate to create the database and tables.
 - Run php artisan key:generate to generate an application key.
 - Run php artisan serve to start the Laravel development server.
 - If you experience issues trying to upload a file, you can create a symbolic link with the following command: php artisan storage:link.

## Working Application
The application can be accessed at https://blog.laprovinciacafe.com/.

The application has two main pages:

 - Home Page: Here you can find a small description of the blog and the articles.
 - Login: Here you can login or register and start creating an app.
 - Article Details Page: When you click on an article, you can see its details, including the image, title, content, creator, and publication date. At the bottom of the article details page, there is a comments section where you can leave a comment. If you are logged in and the article belongs to you, you can also delete it.
