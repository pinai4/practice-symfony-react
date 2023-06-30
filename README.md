# Domains Manager Demo Application
This project is my playground where I check some DDD and hexagonal architecture concepts with the usage of Symfony (backend API) and ReactJS (frontend SPA).

## How to install and use this application
This instruction supposes that you already have installed Docker on your machine.

Navigate to the repository root directory

Setup and first-time run application:
```bash
make init
```
Run application:
```bash
make up
```
Stop application:
```bash
make down
```

Web Services URLs:

Backend API: http://localhost:8081/api/ <br/>
Backend API documentation: http://localhost:8081/api/doc <br/>
Swagger UI Auth info:<br/>
username: user@test.com<br/>
password: secret<br/>
client_id: frontend<br/>
client_secret should be left empty

Frontend: http://localhost:8080 (user account access: user@test.com/secret)