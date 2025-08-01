const { defineConfig } = require("cypress");

module.exports = defineConfig({
  viewportWidth: 1000,
  viewportHeight: 600,
  fileServerFolder: "tests/e2e/cypress",
  fixturesFolder: "tests/e2e/cypress/fixtures",
  downloadsFolder: "tests/e2e/cypress/downloads",
  screenshotsFolder: "tests/e2e/cypress/screenshots",
  videosFolder: "tests/e2e/cypress/videos",
  retries: {
    runMode: 1,
    openMode: 0
  },
  video: true,
  e2e: {
    baseUrl: "http://localhost:8889",
    defaultCommandTimeout: 20000,
    requestTimeout: 30000,
    responseTimeout: 30000,
    pageLoadTimeout: 60000,
    specPattern: "tests/e2e/cypress/integration/**/*.{js,jsx,ts,tsx}",
    supportFile: "tests/e2e/cypress/support/e2e.js",
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
  },
});
