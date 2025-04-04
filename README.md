# Books API

Build this API for a technical skill test. Followed requirements from [Assignment.md](./Assignment.md). In short, it's an api to fetch 
and filter books. The idea is that books are fetch from [openlibary](https://openlibrary.org/dev/docs/api/search) API,
and then they are stored/updated in the database. 


## Starting up the project in docker
1. Build docker container `docker-compose build --no-cache`
2. Spin up docker container `docker-compose up`
3. Install dependencies, run `composer install` inside docker container
4. Get database up to date, run `php bin/console doctrine:migrations:migrate`
5. Visit http://localhost:8040/