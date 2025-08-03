const { defineConfig } = require('cypress');
const baseConfig = require('./cypress.config');

const e2eOverride = {
    baseUrl: 'http://localhost:8889',
}

module.exports = defineConfig({
    ...baseConfig,
    e2e: {
        ...baseConfig.e2e,
        ...e2eOverride,
    }
});
