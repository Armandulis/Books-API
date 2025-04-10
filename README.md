# Books API

Build this API for a technical skill test. Followed requirements from [Assignment.md](./Assignment.md). In short, it's an api to fetch 
and filter books. The idea is that books are fetch from [openlibary](https://openlibrary.org/dev/docs/api/search) API,
and then they are stored/updated in the database. Results are returned from the database, and books are fetched 
asynchronously from open library api.

## Starting up the project in docker

1. Build docker container `docker-compose build --no-cache`
2. Spin up docker container `docker-compose up`
3. Install dependencies `composer install` *this and rest of the commands inside docker container*
4. Get database up to date `php bin/console doctrine:migrations:migrate`
5. Set up Messenger `php bin/console messenger:setup-transports`
6. Handle Async messages `php bin/console messenger:consume async`
7. Server is on http://localhost:8041 & phpMyAdmin is on http://localhost:8043 (login with librarian:librarian)
8. Run tests with `composer test` or `composer test:coverage` (coverage doc is under `./var/coverage`, current coverage is at `93.65%` )
9. There is JWT authentication, before you can access book search endpoint, you need to register and login via `/api/register`, `/api/login`

## How it works

There are 2 controllers, representing 2 versions of api. I marked BookController v1 as deprecated, 
it uses non-english endpoints. V2 controller uses English endpoints, v2 controller is the one that should be used by new 
users, but v1 is still supported for users that have implementation with it.

Once user sends a request to one of the endpoints, we will search the database for book by either title, author, isbn 
(These fields are indexed in the database). At the same time a message is published with user's search. This message is 
handled asynchronously - it tries to fetch books from open library API. Then we will either save or update books, authors,
and isbn. In case we reach rate limit to the api we will send another message, which should be consumed only in 5 minutes 
(I assumed that there's 100 requests per 5 minutes rate limit). However, i could not reproduce rate limit exceptions from
OpenLibraryAPI, so instead you could manually throw an exception in message handler, so that try/catch block sends another message with a delay.

You can test the project by checking OpenAPI documentation.
1. Create a user by sending a POST request to `/api/register` - pass email and password in body
2. Login by sending a POST request to `/api/register` - pass email, password and username (username same as email)
3. From the login, you should receive a JWT bearer token, you need to use this token in header for next requests
4. Send a request to `/api/v2/books/search?searchType=title&searchValue=throne+of+glass`, Try all 3 searchable fields:
   - Title: `?searchType=title&searchValue=throne+of+glass`
   - Author: `?searchType=author&searchValue=tolkien`
   - ISBN: `?searchType=isbn&searchValue=isbn_9780786296651`
   - You can also paginate by passing optional parameters `page=2` and `limit=25`


## Future improvements

1. Use ElasticSearch for searching for books - searching for words in the database text fields is expensive and slow.
2. Reduce the amount of request to the database when we fetch books from open library API. 
   - Currently, we overwrite book's values and save it to database, every time we fetch it from the database, even if
nothing changed. Instead, we should check if there are any changes.
   - Similar thing for authors and ISBN. Instead of removing all authors from the book and adding new authors, we 
should only remove missing authors, and add new authors.