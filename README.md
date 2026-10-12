# PHP Doctrine MVC Mini CRM

A small CRM written in plain PHP with a custom MVC structure, built without a full-stack framework.
It uses Doctrine ORM for data access, FastRoute for routing and JWT cookies for authentication.

## Features

- Login and registration with JWT stored in a cookie
- Dashboard with totals for leads, active leads, pending tasks and documents
- Leads management (create, edit, delete, activate/deactivate)
- For each lead: calls, tasks, notes, documents (file upload) and quotations with line items
- Activity history for each lead
- Lead sources and statuses management
- English and Italian interface (`?lang=en` / `?lang=it`)
- Optional demo mode that blocks write operations

## Tech stack

- PHP 8.2+
- [Doctrine ORM 3](https://www.doctrine-project.org/projects/orm.html) and Doctrine Migrations
- [FastRoute](https://github.com/nikic/FastRoute)
- [firebase/php-jwt](https://github.com/firebase/php-jwt)
- [Symfony HttpFoundation](https://symfony.com/doc/current/components/http_foundation.html), Cache and Mime
- [phpdotenv](https://github.com/vlucas/phpdotenv)
- MySQL / MariaDB

## Getting started

### Requirements

- PHP 8.2 or later with the `pdo_mysql` extension
- Composer
- MySQL or MariaDB

### Installation

```bash
git clone https://github.com/davidebalice/php-doctrine-mvc-mini-crm.git
cd php-doctrine-mvc-mini-crm
composer install
```

### Database

1. Create a database named `crm-doctrine`.
2. Import the dump `db/crm-doctrine.sql`.
3. Adjust the connection parameters (database name, user, password, host) in `config/Bootstrap.php`.

The dump contains a demo user:

| Email            | Password   |
| ---------------- | ---------- |
| `mario@rossi.it` | `12345678` |

### Configuration

The `.env` file in the project root contains:

| Variable    | Description                                         |
| ----------- | --------------------------------------------------- |
| `DEMO_MODE` | `true` to block create/update/delete actions        |

### Run

```bash
php -S localhost:8000 -t public
```

Then open <http://localhost:8000>. On Apache, point the document root to `public/` (an `.htaccess` file is included).

## Routes

| Method   | Route                                  | Description                     |
| -------- | -------------------------------------- | ------------------------------- |
| GET/POST | `/login`, `/register`                  | Authentication                  |
| GET      | `/logout`                              | Logout                          |
| GET      | `/dashboard`                           | Dashboard with statistics       |
| GET/POST | `/leads/...`                           | Leads CRUD and history          |
| GET/POST | `/leads/calls/...`                     | Calls of a lead                 |
| GET/POST | `/leads/tasks/...`                     | Tasks of a lead                 |
| GET/POST | `/leads/notes/...`                     | Notes of a lead                 |
| GET/POST | `/leads/documents/...`                 | Documents of a lead             |
| GET/POST | `/leads/quotations/...`                | Quotations of a lead            |
| GET/POST | `/sources/...`                         | Lead sources                    |
| GET/POST | `/statuses/...`                        | Lead statuses                   |

The full list is in `src/Routes/routes.php`.

## Project structure

```
config/Bootstrap.php    # Doctrine EntityManager and database connection
public/                 # Front controller (index.php), CSS, images, uploads
src/
├── Controllers/        # Request handlers
├── Entity/             # Doctrine entities (User, Lead, Call, Task, Note, Quotation, ...)
├── Middlewares/        # Authentication and demo mode
├── Repository/         # Custom repositories
├── Routes/routes.php   # FastRoute route definitions
├── Services/           # History service
└── Views/              # PHP templates
lang/                   # English and Italian translations
db/crm-doctrine.sql     # Database dump
```

## Author

**Davide Balice** – [davidebalice.dev](https://www.davidebalice.dev)
