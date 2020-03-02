# Twitter scraper tool
### Implemented with Laravel and Angular

#### Pre requirements: 
1. [git](https://git-scm.com/downloads)
2. [composer](https://getcomposer.org/download/)
3. [node and npm](https://www.npmjs.com/get-npm)
4. [angular cli](https://angular.io/cli)
5. php 7.2 or higher
6. mysql 5.7

#### Installation

- clone project or download zip file and extract
- cd api
- composer install 
- cp .env.example .env
- open .env file and change these values
```
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_pass
```
```
TWITTER_CONSUMER_KEY=
TWITTER_CONSUMER_SECRET=
TWITTER_ACCESS_TOKEN=
TWITTER_ACCESS_TOKEN_SECRET=
```
- php artisan key:generate
- php artisan migrate --seed
- php artisan serve --port=8000 (keep this terminal running)
- open another one terminal window, cd to project path /api
- php artisan tweets:scrap (scraper service started, keep this terminal running)
- open another one terminal window, cd to project path /front
- npm install
- ng serve(open ip address provided in terminal)