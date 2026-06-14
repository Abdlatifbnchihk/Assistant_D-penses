# Expense Category Filtering

## Purpose

Enables users to filter expenses and receipts by category, making it easier to review spending patterns and focus on specific types of expenses.

## Requirements

### Requirement: Filter expenses by category on receipt detail page
The system SHALL allow users to filter the expenses table on the receipt show page by category.

#### Scenario: Category filter dropdown displayed
- **WHEN** a user views a receipt with expenses
- **THEN** a category filter dropdown is displayed above the expenses table with options for all categories plus "Toutes" (all)

#### Scenario: Filter shows matching expenses
- **WHEN** a user selects a category from the filter dropdown
- **THEN** only expenses matching that category are displayed in the table

#### Scenario: Filter via query parameter
- **WHEN** a user selects a category filter
- **THEN** the URL updates with `?categorie=<value>` and the page reloads with filtered results

#### Scenario: Clear filter
- **WHEN** a user selects "Toutes" from the filter dropdown
- **THEN** all expenses are displayed and the `categorie` query parameter is removed

### Requirement: Filter receipts by expense category on index page
The system SHALL allow users to filter the receipts list by the categories of their expenses.

#### Scenario: Category filter displayed on receipts index
- **WHEN** a user views the receipts list
- **THEN** a category filter dropdown is displayed above the receipts table

#### Scenario: Filter shows receipts containing matching expenses
- **WHEN** a user selects a category from the filter
- **THEN** only receipts that contain at least one expense in the selected category are displayed

#### Scenario: Filter via query parameter
- **WHEN** a user selects a category filter on the index page
- **THEN** the URL updates with `?categorie=<value>` and the page reloads with filtered results

#### Scenario: Clear filter on index
- **WHEN** a user selects "Toutes" from the filter
- **THEN** all receipts are displayed
