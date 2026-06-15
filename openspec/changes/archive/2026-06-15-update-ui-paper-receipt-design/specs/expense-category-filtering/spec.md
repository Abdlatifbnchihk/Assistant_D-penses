## MODIFIED Requirements

### Requirement: Filter expenses by category on receipt detail page
The system SHALL allow users to filter the expenses table on the receipt show page by category. Filter controls SHALL be styled as stamp-style chips.

#### Scenario: Category filter chips displayed
- **WHEN** a user views a receipt with expenses
- **THEN** category filter chips are displayed above the expenses table
- **AND** the active filter chip is fully colored, inactive chips have reduced opacity

#### Scenario: Filter shows matching expenses
- **WHEN** a user selects a category from the filter chips
- **THEN** only expenses matching that category are displayed in the table

#### Scenario: Filter via query parameter
- **WHEN** a user selects a category filter
- **THEN** the URL updates with `?categorie=<value>` and the page reloads

#### Scenario: Clear filter
- **WHEN** a user selects "Toutes" from the filter chips
- **THEN** all expenses are displayed and the `categorie` query parameter is removed

### Requirement: Filter receipts by expense category on index page
The system SHALL allow users to filter the receipts list by the categories of their expenses. Filter controls SHALL be styled as stamp-style chips.

#### Scenario: Category filter chips displayed on receipts index
- **WHEN** a user views the receipts list
- **THEN** category filter chips are displayed above the receipts list

#### Scenario: Filter shows receipts containing matching expenses
- **WHEN** a user selects a category from the filter
- **THEN** only receipts that contain at least one expense in the selected category are displayed

#### Scenario: Filter via query parameter
- **WHEN** a user selects a category filter on the index page
- **THEN** the URL updates with `?categorie=<value>` and the page reloads

#### Scenario: Clear filter on index
- **WHEN** a user selects "Toutes" from the filter
- **THEN** all receipts are displayed
