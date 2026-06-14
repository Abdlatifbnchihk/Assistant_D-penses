## ADDED Requirements

### Requirement: User can edit receipt source text
The system SHALL allow users to edit the `source_text` of a processed receipt via an edit form.

#### Scenario: Edit link visible for processed receipts
- **WHEN** a user views a receipt with `status=processed`
- **THEN** an "Modifier" link is displayed on the receipt show page

#### Scenario: Edit form displays current source text
- **WHEN** a user navigates to the receipt edit page
- **THEN** the form pre-fills the textarea with the current `source_text`

#### Scenario: Successful update
- **WHEN** a user submits the edit form with valid `source_text` (10-5000 characters)
- **THEN** the receipt `source_text` is updated and the user is redirected to the receipt show page with a success message

#### Scenario: Validation failure
- **WHEN** a user submits the edit form with `source_text` shorter than 10 characters or longer than 5000 characters
- **THEN** the form re-displays with an error message and the entered text preserved

### Requirement: User can add expenses manually to a processed receipt
The system SHALL allow users to manually add a new expense to a processed receipt.

#### Scenario: Add expense form visible on processed receipts
- **WHEN** a user views a receipt with `status=processed`
- **THEN** an "Ajouter une dépense" form is displayed with fields for libelle, quantite, prix_unitaire, and categorie

#### Scenario: Successful expense creation
- **WHEN** a user submits the add expense form with valid data (libelle required, quantite >= 1, prix_unitaire > 0, categorie from CategorieExpensesEnum)
- **THEN** a new `Expenses` record is created linked to the receipt and the page refreshes showing the new expense

#### Scenario: Validation failure on add
- **WHEN** a user submits the add expense form with invalid data
- **THEN** the form re-displays with error messages and the entered values preserved

### Requirement: User can edit an existing expense
The system SHALL allow users to edit the details of an existing expense on a processed receipt.

#### Scenario: Edit action visible for each expense
- **WHEN** a user views a receipt with `status=processed`
- **THEN** each expense row has an "Modifier" action

#### Scenario: Edit form pre-fills expense data
- **WHEN** a user clicks edit on an expense
- **THEN** the form pre-fills with the current expense values (libelle, quantite, prix_unitaire, categorie)

#### Scenario: Successful expense update
- **WHEN** a user submits the edit expense form with valid data
- **THEN** the expense record is updated and the page refreshes showing the updated expense

### Requirement: User can delete an expense
The system SHALL allow users to delete an individual expense from a processed receipt.

#### Scenario: Delete action visible for each expense
- **WHEN** a user views a receipt with `status=processed`
- **THEN** each expense row has a "Supprimer" action with confirmation

#### Scenario: Successful expense deletion
- **WHEN** a user confirms deletion of an expense
- **THEN** the expense record is removed and the page refreshes without that expense

### Requirement: Ownership check on expense operations
The system SHALL verify that the user owns the receipt before allowing expense modifications.

#### Scenario: Non-owner cannot modify expenses
- **WHEN** a user attempts to add, edit, or delete an expense on a receipt they do not own
- **THEN** the system returns a 403 forbidden response
