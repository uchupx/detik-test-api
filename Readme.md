# Detik API Test - Readme

## Requirement

- composer
- php 8+
- mysql

## How to

 1. run `composer install`
 2. rename file `.env.example` to `.env` and fill the ENV
 3. create database first refers to .env
 4. for migrations for UP run `php migrate.php up --path=./migrations` and for down `php migrate.php down --path=./migrations`
 5. to run the php server `php -S locahost:3001 index.php`

## CLI

- migration `php migrate.php` refers [byjg/migration-cli](https://github.com/byjg/migration-cli)
- update transaction `php transaction-cli.php <integer refenrence_id> <string status>` status `pending | paid | expired | failed`

## Docs

- you can import Postman Col from file `collection.json`
