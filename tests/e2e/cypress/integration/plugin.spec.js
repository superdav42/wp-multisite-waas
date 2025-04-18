describe("Plugin", () => {
  beforeEach(() => {
    cy.loginByForm(
      Cypress.env("admin").username,
      Cypress.env("admin").password
    );
  });

  it("Should show an error message that the plugin needs to be network activated", () => {
    cy.visit("/wp-admin/");
    cy.get(".notice.notice-error")
      .contains(
        'WP Multisite WaaS needs to be network active to run properly. You can "Network Activate" it here'
      )
      .should("be.visible");
  });

  it("Should be able to activate the plugin", () => {
    cy.visit("/wp-admin/network/plugins.php");
    cy.location("pathname").should("equal", "/wp-admin/network/plugins.php");
    cy.get("#activate-wp-multisite-waas").should("be.visible").click();
    cy.location("pathname").should("eq", "/wp-admin/network/admin.php");
    cy.location("search").should("include", "page=wp-ultimo-setup");
  });

  it("Should be able to deactivate the plugin", () => {
    cy.visit("/wp-admin/network/plugins.php");
    cy.location("pathname").should("equal", "/wp-admin/network/plugins.php");
    cy.get("#deactivate-wp-multisite-waas").should("be.visible").click();
    cy.get("#activate-wp-multisite-waas").should("be.visible");
  });
});
