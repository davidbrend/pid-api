# PID - Points of Sale API
## APITTE Project with Swagger, Docker, PHP 8.1 and Nette 3.1

This project is a REST API built with Apitte, Docker, PHP 8.1, and Nette 3.1.
## Endpoints:
- `/api/v1/pointsOfSale/find`: GET - Find by `isOpen` or `DateTime` (query string)

## Setup Instructions

*Important: If folder named 'log' does not exist, please create new.*

1. Clone the repository

2. Navigate into the cloned directory and start the project with Docker:

   `docker compose up -d`

3. Install the NPM dependencies:

   `npm install` && `npm run dev` or `npm run build`

4. Build the composer dependencies by running:

   `docker compose exec php composer install`

5. Build the database by running the migrations with this command:

   `docker compose exec php composer run run-migration`

6. Synchronize data from PID

   `Call 'http://localhost:8080/?do=updatePointsOfSale' to syncronize all data
    or on 'http://localhost:8080/' click to 'Synchronize Points of sale'`

## Swagger, Adminer, and API Endpoints
After setting up, the following services can be accessed as shown:

- Swagger UI: `http://localhost:9001`
- PHPMyAdmin (for database management): `http://localhost:8181`
- OpenAPI json: `http://localhost:8080/api/v1/openapi`

To handle CORS, the `CorsMiddleware` middleware will be used to add appropriate CORS headers to the response.