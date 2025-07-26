import './commands/index';

Cypress.on('uncaught:exception', (err) => {
  if (err.message.includes('Vue is not defined')) {
    return false;
  }
});
