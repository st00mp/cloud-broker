# Cloud Broker

An application to compare and find the best cloud GPU offers for AI training.

> **Note**: This project is currently under development. See the [Project Status](#%EF%B8%8F-project-status) section for more details.

## 📋 Description

Cloud Broker is a web application that aggregates and displays GPU offers from different cloud providers (currently only AWS) to facilitate finding the best options for training artificial intelligence models. The application allows filtering offers by GPU type, provider, region, and price.

## 🛠️ Architecture

The project consists of:

- **Backend**: Symfony API exposing GPU offer data
- **Frontend**: Next.js user interface with advanced offer filtering
- **Database**: MySQL storing offer and provider information
- **Infrastructure**: Docker Compose configuration for easy deployment

## 🚀 Installation and Startup

### Prerequisites

- Docker and Docker Compose
- Git

### Installation Steps

1. Clone the repository:
```bash
git clone [REPO_URL]
cd cloud-broker
```

2. Launch the application with Docker Compose:
```bash
docker-compose up -d
```

3. Access the different interfaces:
   - Frontend: http://localhost:3000
   - API Backend: http://localhost:8080
   - PHPMyAdmin: http://localhost:8083 (user: symfony, password: symfony)

## 🧩 Project Structure

```
cloud-broker/
├── backend/                 # Symfony API
│   ├── src/
│   │   ├── Controller/      # API Controllers (GpuController, etc.)
│   │   ├── Entity/          # Data Models (InstanceDetail, Provider, etc.)
│   │   └── Repository/      # Database queries
│   └── ...
├── frontend/                # Next.js Application
│   ├── app/                 # React pages and components
│   └── ...
└── docker-compose.yml       # Services configuration
```

## 🔄 Features

- Display of available GPU offers in an interactive table
- Filtering by GPU model (A100, V100, T4, etc.)
- Filtering by cloud provider (currently only AWS)
- Filtering by region (us-east-1, eu-west-3, etc.)
- Maximum price filter
- Ability to export filtered data

## 🧪 Development

### Backend (Symfony)

To access the Symfony container:
```bash
docker exec -it symfony-backend bash
```

Useful commands:
```bash
# Install dependencies
composer install

# Create a migration
php bin/console make:migration

# Run migrations
php bin/console doctrine:migrations:migrate

# Create fixtures (if configured)
php bin/console doctrine:fixtures:load
```

### Frontend (Next.js)

To access the Next.js container:
```bash
docker exec -it nextjs-frontend bash
```

Useful commands:
```bash
# Install dependencies
npm install

# Run in development mode
npm run dev

# Build for production
npm run build
```

## 🔒 Environment Variables

### Backend (.env)
- `APP_ENV`: Application environment (dev, prod)
- `DATABASE_URL`: Database connection URL

### Frontend
- `NODE_ENV`: Node.js environment (development, production)

## ⚙️ Project Status

This project is currently under development with the following limitations:

- **Available Providers**: Currently, only AWS offers are integrated. Support for Google Cloud and Azure is planned for future versions.
- **Filters**: Filtering features are not yet 100% operational and are under development.
- **User Interface**: Interface improvements are planned for future versions.

We appreciate your contributions and suggestions to improve this project!

## 📝 License

MIT — Open source, feel free to fork and improve 🤝
