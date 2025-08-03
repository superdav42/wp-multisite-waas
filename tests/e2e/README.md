# E2E Testing Project

This project provides end-to-end (E2E) testing for a WordPress environment using **Cypress** and **wp-env**.

## Prerequisites

- Node.js
- Docker
- npm
- wp-env (automatically installed via @wordpress/env)

### Available Scripts

| Script                    | Description                                                                      |
| ------------------------- | -------------------------------------------------------------------------------- |
| `npm run env:start`       | Starts the WordPress development and testing environment using `wp-env`.         |
| `npm run env:clean`       | Cleans the WordPress test environment. Useful for ensuring a clean slate.        |
| `npm run env:stop`        | Stops the running WordPress environment.                                         |
| `npm run env:destroy`     | Deletes all docker containers, images and volumes.                               |
| `npm run cy:open:dev`     | Starts the dev environment, cleans it, and opens Cypress Test Runner UI.         |
| `npm run cy:run:dev`      | Starts the dev environment, cleans it, and runs Cypress tests in headless mode.  |
| `npm run cy:open:test`    | Starts the test environment, cleans it, and opens Cypress Test Runner UI.        |
| `npm run cy:run:test`     | Starts the test environment, cleans it, and runs Cypress tests in headless mode. |
| `npm run pma:start`       | Starts the phpMyAdmin application for easy access to the database.               |
| `npm run pma:stop`        | Stops the docker container from running phpMyAdmin.                              |
| `npm run mailpit:start`   | Starts the mailpit application for catching and testing emails.                  |
| `npm run mailpit:stop`    | Stops the docker container from running mailpit.                                 |

## Running Tests

### Open Cypress UI

```
npm run cy:open
```
This will launch the Cypress Test Runner where you can run tests interactively.

### Run Cypress Tests Headlessly

```
npm run cy:run
```
Runs all Cypress tests in the CLI, useful for testing locally and CI/CD environments.

## Cleaning Up

To stop and clean the environment manually:
```
npm run env:stop
```

## Configuration

Modify `.wp-env.json` in the root of the project to point to custom themes or plugins for testing.
