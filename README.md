
# ProCook Invoice Management System

## Introduction

This project is designed to demonstrate a robust invoice management system using Laravel, which handles invoice creation, updating, listing, and deletion securely and efficiently. It includes authentication for API access, a Vue.js frontend for interacting with invoices, and utilizes Docker for easy setup and deployment.

## Technologies Used

- **Docker Environment**: Simplifies the setup and deployment by containerizing the application and its environment.
- **Laravel Passport**: Secures API endpoints using Access Tokens, ensuring that only authenticated users can access certain operations.
- **Vue.js**: A progressive JavaScript framework used for building the frontend user interface.
- **Redis**: Used for caching data to improve application performance by reducing database load and speeding up response times.

## Project Requirements

- Composer >= 2.6.6
- PHP >= 8.2
- MySQL >= 8.0
- Node >= 20.11.0
- Npm >= 10.2.4
- Laravel >= 10.10
- Redis (Latest Version)

## Installation Steps Using Docker

1. **Clone the repo**: `git clone https://github.com/akashmanujaya/ProCook-Invoice-Management.git`
2. **Navigate to the Project Directory**: `cd ProCook-Invoice-Management`
3. **Build and Start Docker Containers**: `docker-compose up -d --build`

After running this command, your containers will be built. Please wait a few seconds until the container runs all the commands inside the terminal. Check your container terminal for logs.

4. **Access the Container's Shell**: `docker exec -it aml-procook-invoice-management /bin/bash`
5. **Generate the Passport keys and migrations**: `php artisan passport:install` (This might ask some questions when you are installing. please type `yes` for that to install migrations)

After successful installation of all packages, the application will be available at http://0.0.0.0:8000.

4. **Access the Application**: Open your web browser and visit http://0.0.0.0:8000.

5. **Sample User Details**
    - **Email:** `user@procook.com`
    - **Password:** `12345678`

## Installation Steps Without Docker

1. **Clone the Repository**
2. **Navigate to the Project Directory**
3. **Install Composer Dependencies**: `composer install`
4. **Install NPM Packages**: `npm install`
5. **Set Up Environment**
    - **Copy the example environment file**: `cp .env.example .env`
    - **Check the .env file and update the database credentials**
    - **Generate an application key**: `php artisan key:generate`
6. **Build Assets**: `npm run build`
7. **Run the database migration**: `php artisan migrate:refresh --seed` (make sure seeds have been run)
8. **Generate the Passport keys**: `php artisan passport:install` (This might ask some questions when you are installing. please type `yes` for that)
8. **Serve the Application**: `php artisan serve`. Then, access the application at http://127.0.0.1:8000/
9. **Sample User Details**
    - **Email:** `user@procook.com`
    - **Password:** `12345678`

## Why Authentication even it's not required?

Authentication and authorization have been integrated into the application to ensure secure access to the API endpoints. This is crucial to prevent unauthorized access and to protect sensitive data.

## Application Program Interface (API)

To run and test the API functionalities, use Postman, a popular tool for API testing. Follow these steps to set up and execute the API calls:

1. **Import the Postman Collection and Environment**:
    - Navigate to the `API` folder located in the project root.
    - Import the `ProCook_Invoice_Management.postman_collection.json` into Postman, which contains all the API requests you need to interact with the application.
    - Depending on your setup, import either the `ProCook_Invoice_Management_Docker.postman_environment.json` or `ProCook_Invoice_Management_Localhost.postman_environment.json` into Postman. These environment files configure Postman to point to the correct base URL and manage environmental variables.

2. **Authentication**:
    - Under the **Auth** folder in Postman, you will find the registration and login APIs.
    - **Register**: If you do not have an account, use the registration request to create one. Fill in the required details such as name, email, and password.
    - **Login**: If you already have an account, use the login request to authenticate. Upon successful authentication, the API will return an **Access Token**, which Postman is configured to automatically save to an environment variable called `Token`.

3. **Using the Token and API Key**:
    - This token and API Key are essential for making requests that require authentication. The Postman environment is set up to use this token automatically for all requests that need it, so you don't have to manually insert the token for each request.
    - Ensure that the `Token` variable in your selected Postman environment is updated with the token received from the login response. This setup allows a seamless transition between logging in and using secured routes.

4. **Accessing Secured Endpoints**:
    - Navigate to the **Invoices** folder within the Postman collection.
    - You have endpoints for all the CRUD operations and changing the status as well.

5. **Testing**:
    - Execute the requests in the order of registration (if needed), login, and then CRUD operations of invoices.
    - Monitor the responses in Postman to ensure each step is successful. Check for HTTP status codes and response messages to diagnose any issues.

6. **Environment Setup**:
    - Make sure the environment in Postman correctly points to your local or Docker-based application instance.
    - Double-check that the base URL in the Postman environment settings matches your running application’s URL (either `http://0.0.0.0:8000` for Docker or `http://127.0.0.1:8000` for a local setup).

## Running Tests

### Note for Testing Routes

When running tests, you need to uncomment a section inside the `routes/api.php` and comment another section inside the same file. This is because there are routes imported using modules, and these routes are sometimes not recognized when you are running tests since those routes are inside the corresponding module.

**You can see what to comment and what to uncomment inside the `routes/api.php` file.**

### Using Docker Environment

Docker environment testing is **not suitable** since the SQLite database and :memory sometimes do not work, causing test cases to fail due to the database connection being lost.

1. **Access the Container's Shell**: `docker exec -it aml-procook-invoice-management /bin/bash`
2. **Run the Tests**: `php artisan test`

### In Non-Docker Environment

Simply run the tests with the following command in your project root:

`php artisan test`
