# JAM 

## Installation
- **Install a server side application. Example: Xampp, Wamp, Lampp, Laragon, etc.**
- **Clone the repository on the root. (htdocs for xampp, www for laragon and wamp, etc).**

## Requirements 

- PHP 7.4+ `required`
- Mysql 5.7+ `required`
- Node 8+
- Redis to cache, session and queued jobs  `optional`

- **Open terminal and run the following commands:**
     * -cd TIENDA12
     * -composer install
     * -npm install
     * -cp .env.example .env
     
 - **Create database JAM:**
     * -mysql -u root
     * -create database jam;
     * -exit
     * -php artisan migrate --seed
     
 - **Create database test:**
   * -mysql -u root
   * -create database testing_laravel;
   * -exit
       
 - **Open terminal and run the following commands-test:**
      * -cp .env.testing.example .env
      * -php artisan test
      
- **To finish and deploy the application, run the command:**
   * -php artisan optimize:clear
   * -php artisan key:generate
   * -npm run prod 
   * -php artisan storage:link
   * -php artisan serve
 
- **Job and commands**
   * -php artisan queue:work
   * -php artisan payment:orders
   * -php artisan report:excel

-  **Create admin user**
   * -php artisan admin:create

"# T12-V2" 
