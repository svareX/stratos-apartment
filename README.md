<p align="center">
	<img src="https://raw.githubusercontent.com/PKief/vscode-material-icon-theme/ec559a9f6bfd399b82bb44393651661b08aaf7ba/icons/folder-markdown-open.svg" align="center" width="30%">
</p>
<p align="center"><h1 align="center">STRATOS-APARTMENT</h1></p>
<p align="center">
	<em><code>❯ A comprehensive web application for presenting and managing lodging in the Austrian–Czech border region. Integrates multi-channel reservation management and an AI assistant.</code></em>
</p>
<p align="center">
	<img src="https://img.shields.io/badge/license-MIT-6d3dd6" alt="license">
	<img src="https://img.shields.io/badge/php-8.4-blue" alt="php-version">
	<img src="https://img.shields.io/badge/framework-Laravel%2012-ff2d20" alt="laravel">
</p>
<br>

## Technologies used

- PHP 8.4
- Laravel 12
- Livewire
- Filament
- Tailwind CSS + Vite
- PostgreSQL / MySQL (PDO)
- Docker (Laravel Sail / Dockerfile)
- Composer & npm
- pgvector (vector search for RAG workflows)
- Llama integration (asynchronous AI assistant)
- iCal / sabre/vobject / spatie/icalendar-generator (reservation sync)

---

##  Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Project Structure](#-project-structure)
- [Getting Started](#-getting-started)
  - [Prerequisites](#-prerequisites)
  - [Installation](#-installation)
  - [Usage](#-usage)
  - [Testing](#-testing)
- [Project Roadmap](#-project-roadmap)
- [Contributing](#-contributing)
- [License](#-license)
- [Acknowledgments](#-acknowledgments)

---

# Overview
This repository contains the STRATOS-APARTMENT web application, developed as part of a bachelor thesis. The system provides a modern backend for managing a lodging facility in the Austrian–Czech border region, focusing on consolidating reservations from multiple channels and automating client support with an intelligent assistant.

Key innovations and goals:

- Multi-channel reservation aggregation and automated iCal synchronization
- Retrieval-Augmented Generation (RAG) for contextual AI assistance using `pgvector`
- Asynchronous integration of Llama models for proactive client support
- Proactive SEO and sitemap generation
- Built with Laravel, Livewire and Filament and containerized with Docker

---

# Features

- Centralized reservation management (multi-channel)
- Automated iCal synchronization
- AI assistant (RAG + Llama integration)
- Admin panel built with Filament
- Livewire-driven interactive UI components
- Proactive SEO, sitemap generation and metadata management
- Background jobs and queue processing for sync tasks

---

# Project Structure

Top-level layout (selected files/folders):

```
├── app/                # Laravel application code (Models, Services, Jobs)
├── config/             # Configuration
├── database/           # Migrations, seeders, factories
├── public/             # Web entry + built assets
├── resources/          # Views, Livewire components, frontend assets
├── routes/             # web.php, api.php
├── tests/              # Unit & Feature tests
├── Dockerfile
├── composer.json
└── README.md
```

---

# Getting Started

## Prerequisites

- PHP >= 8.4
- Composer
- Node.js >= 18 and npm
- Docker & docker-compose (recommended: Laravel Sail)
- PostgreSQL or MySQL

## Installation

Use the provided Composer scripts to bootstrap the project:

```bash
composer install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
php artisan migrate --force
npm install
npm run build
```

If you prefer Docker / Sail:

```bash
./vendor/bin/sail up -d
./vendor/bin/sail composer install
./vendor/bin/sail npm install
./vendor/bin/sail artisan migrate --force
```

## Usage

Start the local dev server:

```bash
php artisan serve
npm run dev
```

Open the app at `http://localhost:8000` (or the Sail-provided URL).

## Testing

Run the test suite:

```bash
composer test
```

---

# Project Roadmap

- Harden RAG pipeline and vector store production readiness
- Add end-to-end tests for reservation sync flows
- Improve multi-calendar conflict resolution heuristics
- Add telemetry and usage analytics for AI assistant interactions

---

# Contributing

- Please open issues or pull requests.
- Follow PSR-12 style and run `composer test` before submitting.

---

# License

This project is licensed under the MIT License.

---

# Acknowledgments

- Built on Laravel and ecosystem packages
- Inspired by best-practices in hospitality & tourism UX

---

# Files updated: see [README.md](README.md)
