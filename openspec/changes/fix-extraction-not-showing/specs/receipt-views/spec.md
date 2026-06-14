## ADDED Requirements

### Requirement: Display extracted expenses in receipt views
The receipt detail and list views SHALL correctly display extracted expenses by using the `expenses` relationship defined on the `Receipt` model.

#### Scenario: Show page displays expenses
- **WHEN** a user views a processed receipt with extracted expenses
- **THEN** the expenses table shows each expense with libelle, quantite, prix_unitaire, categorie, and prix_total

#### Scenario: Index page shows expense count
- **WHEN** a user views the receipts list for a processed receipt
- **THEN** the expense count column shows the number of extracted expenses
