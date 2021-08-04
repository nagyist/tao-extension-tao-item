/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2021 (original work) Open Assessment Technologies SA ;
 */

/**
 * Commands
 */
Cypress.Commands.add('addClass', formSelector => {
    cy.log('COMMAND: addClass');
    cy.get('[data-context=resource][data-action=subClass]').click();
    cy.get(formSelector).should('exist');
});

Cypress.Commands.add('addClassToRoot', (rootSelector, formSelector, name) => {
    cy.log('COMMAND: addClassToRoot', name);

    cy.get(`${rootSelector} a`)
        .first()
        .click();

    cy.addClass(formSelector).then(() => {
        cy.renameSelected(formSelector, name);
    });
});

Cypress.Commands.add('moveClass', (formSelector, moveSelector, moveConfirmSelector, name, nameWhereMove) => {
    cy.log('COMMAND: moveClass', name);

    cy.get(`li[title="${name}"] a`).first().click().then(() => {
        cy.get(moveSelector).click();
        cy.get('.destination-selector')
            .find(`a[title="${nameWhereMove}"]`).then(() => {
            cy.get(`a[title="${nameWhereMove}"]`).click();
            cy.get('.actions button').click();
            cy.get(moveConfirmSelector).click();
            cy.get(`li[title="${name}"] a`).should('not.exist');
        });
    });
});

Cypress.Commands.add('moveClassFromRoot', (rootSelector, formSelector, moveSelector, moveConfirmSelector, name, nameWhereMove) => {
    cy.log('COMMAND: moveClassFromRoot', name);

    cy.addClassToRoot(rootSelector, formSelector, name);
    cy.addClassToRoot(rootSelector, formSelector, nameWhereMove);

    cy.get(`${rootSelector} a`)
        .first()
        .click();

    cy.get(rootSelector)
        .find(`li[title="${name}"] a`).then(() => {
        cy.moveClass(formSelector, moveSelector, moveConfirmSelector, name, nameWhereMove);
    });
});

Cypress.Commands.add('deleteClass', (formSelector, deleteSelector, confirmSelector, name) => {
    cy.log('COMMAND: deleteClass', name);

    // cy.contains('a', name).click();
    cy.get(formSelector)
        .should('exist')
        .get(deleteSelector)
        .click()
        .get('.modal-body label[for=confirm]')
        .click()
        .get(confirmSelector)
        .click();
});

Cypress.Commands.add('deleteClassFromRoot', (rootSelector, formSelector, deleteSelector, confirmSelector, name) => {
    cy.log('COMMAND: deleteClassFromRoot', name);

    cy.get(`${rootSelector} a`)
        .first()
        .click()
        .get(`li[title="${name}"] a`).then(() => {
        cy.deleteClass(formSelector, deleteSelector, confirmSelector, name);
    });
});

Cypress.Commands.add('deleteEmptyClassFromRoot', (rootSelector, formSelector, deleteSelector, confirmSelector, name) => {
    cy.log('COMMAND: deleteEmptyClassFromRoot', name);

    cy.addClassToRoot(rootSelector, formSelector, name);
    cy.deleteClassFromRoot(rootSelector, formSelector, deleteSelector, confirmSelector, name);
});

Cypress.Commands.add('addNode', (formSelector, addSelector) => {
    cy.log('COMMAND: addNode');

    cy.get(addSelector).click();
    cy.get(formSelector).should('exist');
});

Cypress.Commands.add('selectNode', (rootSelector, formSelector, name) => {
    cy.log('COMMAND: selectNode', name);

    cy.get(`${rootSelector} a`).first().click();
    cy.contains('a', name).click();
    cy.get(formSelector).should('exist');
});

Cypress.Commands.add('deleteNode', (deleteSelector, name) => {
    cy.log('COMMAND: deleteNode', name);

    cy.contains('a', name).click();

    cy.get(deleteSelector).click();
    cy.get('[data-control="ok"]').click();

    cy.contains('a', name).should('not.exist');
});

Cypress.Commands.add('renameSelected', (formSelector, newName) => {
    cy.log('COMMAND: renameSelectedClass', newName);

    // TODO: update selector when data-testid attributes will be added
    cy.get(`${ formSelector } input[name*=label]`)
        .clear()
        .type(newName)
        .get('button[id="Save"]')
        .click();

    cy.get(formSelector).should('exist');

    cy.contains('a', newName).should('exist');
});
