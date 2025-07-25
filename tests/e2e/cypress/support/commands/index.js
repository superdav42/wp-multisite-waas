Cypress.Commands.add("loginByApi", (username, password) => {
  cy.request({
    url: "/wp-login.php",
    method: "POST",
    form: true,
    body: {
      log: username,
      pwd: password,
      rememberme: "forever",
      testcookie: 1,
    },
  }).then((response) => {
    expect(response.status).to.eq(200);
    window.localStorage.setItem(
      "WP_DATA_USER_1", // Investigate why WP_DATA_USER_1.
      JSON.stringify({
        "core/edit-post": {
          preferences: {
            features: {
              welcomeGuide: false,
            },
          },
        },
      })
    );
  });
});

Cypress.Commands.add("loginByForm", (username, password) => {
  cy.session(['loginByForm', username, password], () => {
    cy.visit("/wp-admin/");
    cy.location("pathname").should("contain", "/wp-login.php");
    cy.get("#rememberme").should("be.visible").and("not.be.checked").click();
    cy.get("#user_login").should("be.visible").setValue(username);
    cy.get("#user_pass").should("be.visible").setValue(password).type("{enter}");
    cy.location("pathname")
      .should("not.contain", "/wp-login.php")
      .and("equal", "/wp-admin/");
  });
});

Cypress.Commands.add("wpCli", (command, options = {}) => {
  cy.exec(`npm run env run tests-cli wp ${command}`, options);
});

Cypress.Commands.overwrite("type", (originalFn, subject, string, options) =>
  originalFn(subject, string, Object.assign({ delay: 0 }, options))
);

Cypress.Commands.add("setValue", { prevSubject: true }, (subject, value) => {
  subject[0].setAttribute("value", value);
  return subject;
});

Cypress.Commands.add("saveDraft", () => {
  cy.window().then((w) => (w.stillOnCurrentPage = true));
  cy.get("#save-post").should("not.have.class", "disabled").click();
});

Cypress.Commands.add("publishPost", () => {
  cy.window().then((w) => (w.stillOnCurrentPage = true));
  cy.get("#publish").should("not.have.class", "disabled").click();
});

Cypress.Commands.add("waitForPageLoad", () => {
  cy.window().its("stillOnCurrentPage").should("be.undefined");
  cy.get("#message .notice-dismiss").click();
});

Cypress.Commands.add("blockAutosaves", () => {
  cy.intercept("/wp-admin/admin-ajax.php", (req) => {
    if (req.body.includes("wp_autosave")) {
      req.reply({
        status: 400,
      });
    }
  }).as("adminAjax");
});
