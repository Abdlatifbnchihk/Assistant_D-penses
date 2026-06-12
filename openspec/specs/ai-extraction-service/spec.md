# AI Extraction Service

## Purpose

Provides AI-powered extraction of structured expense items from raw receipt text. The service calls an LLM API with the receipt content, receives parsed expense data, validates it against business rules, and returns structured results for persistence.

## Requirements

### Requirement: Extract expenses from receipt text via AI
The system SHALL accept a `Receipt` model with `source_text` and use an LLM API to extract structured expense items from the text.

#### Scenario: Successful extraction
- **WHEN** a `Receipt` with `source_text` is submitted for extraction
- **THEN** the system calls the AI API with the receipt text and receives a JSON response containing an array of expense items, each with `libelle` (string), `quantite` (integer, default 1), `prix_unitaire` (decimal), and `categorie` (string matching a `CategorieExpensesEnum` value)

#### Scenario: AI returns valid expenses
- **WHEN** the AI API returns a JSON response with one or more expense items
- **THEN** the system creates `Expenses` records for each item linked to the receipt via `recu_id`

#### Scenario: AI returns empty expenses
- **WHEN** the AI API returns a JSON response with an empty expenses array
- **THEN** the system creates no `Expenses` records and marks the receipt as `processed` with an empty `payload_ia`

### Requirement: Dispatch extraction job automatically on receipt creation
The system SHALL automatically dispatch the `ExtraireDepensesDuRecu` job when a new `Receipt` is created via `ReceiptController::store()`.

#### Scenario: Job dispatched after receipt creation
- **WHEN** a user submits receipt text via the create form
- **THEN** a `Receipt` record is created with `status=pending` and the `ExtraireDepensesDuRecu` job is dispatched with the receipt ID

#### Scenario: Job processes in queue
- **WHEN** the `ExtraireDepensesDuRecu` job is dispatched
- **THEN** it is placed on the database queue and processed by a queue worker

### Requirement: Update receipt status during extraction lifecycle
The system SHALL update the `Receipt.status` field to reflect the extraction progress.

#### Scenario: Status transitions to processed on success
- **WHEN** the extraction job completes successfully and expenses are created
- **THEN** the receipt `status` is set to `processed`

#### Scenario: Status transitions to failed on error
- **WHEN** the extraction job encounters an error (API failure, malformed response, parsing error)
- **THEN** the receipt `status` is set to `failed`

### Requirement: Store AI response payload on receipt
The system SHALL store the raw AI API response in the `payload_ia` JSON column of the `Receipt` model.

#### Scenario: Payload stored on success
- **WHEN** extraction completes successfully
- **THEN** `payload_ia` contains the parsed expense data used to create `Expenses` records

#### Scenario: Error details stored on failure
- **WHEN** extraction fails with an exception
- **THEN** `payload_ia` contains an error object with `message` and `failed_at` timestamp

### Requirement: Validate and map expense categories
The system SHALL validate that AI-returned category values match the `CategorieExpensesEnum` and fall back to `autre` for unrecognized values.

#### Scenario: Valid category returned by AI
- **WHEN** the AI returns `categorie: "alimentaire"`
- **THEN** the expense is created with `categorie = CategorieExpensesEnum::Alimentaire`

#### Scenario: Invalid category returned by AI
- **WHEN** the AI returns `categorie: "unknown_category"`
- **THEN** the expense is created with `categorie = CategorieExpensesEnum::Autre`

### Requirement: Handle missing quantity defaults
The system SHALL default `quantite` to 1 when the AI does not provide a quantity value.

#### Scenario: Quantity not provided
- **WHEN** the AI returns an expense item without a `quantite` field
- **THEN** the expense is created with `quantite = 1`

### Requirement: Fix Expenses model cast and add accessor
The system SHALL correctly cast the `categorie` column to `CategorieExpensesEnum` and provide a `prix_total` computed attribute.

#### Scenario: Cast uses correct column name
- **WHEN** an `Expenses` record is loaded
- **THEN** the `categorie` attribute is an instance of `CategorieExpensesEnum`

#### Scenario: prix_total accessor returns calculated value
- **WHEN** `$expense->prix_total` is accessed
- **THEN** it returns `quantite * prix_unitaire` as a decimal
