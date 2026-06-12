## 1. Dependencies & Configuration

- [x] 1.1 Install `openai-php/laravel` package via Composer
- [x] 1.2 Add `OPENAI_API_KEY` and `OPENAI_ORGANIZATION` to `.env` with placeholder values
- [x] 1.3 Publish the OpenAI package config (`php artisan vendor:publish --tag=openai`)

## 2. Extraction Service

- [x] 2.1 Create `app/Services/ExtractionService.php` with a `extractFromText(string $text): array` method
- [x] 2.2 Implement the system prompt that instructs the AI to return JSON with `expenses` array containing `libelle`, `quantite`, `prix_unitaire`, `categorie` fields
- [x] 2.3 Call the OpenAI API using `openai-php/laravel` with `response_format: json_object`
- [x] 2.4 Parse the JSON response and validate each expense item (map `categorie` to `CategorieExpensesEnum`, default `quantite` to 1)
- [x] 2.5 Return validated expense data array; throw exception on API or parsing errors

## 3. Fix Expenses Model

- [x] 3.1 Fix `Expenses` model: change cast key from `category` to `categorie` to match the database column
- [x] 3.2 Add `prix_total` accessor to `Expenses` model that returns `quantite * prix_unitaire`

## 4. Job Implementation

- [x] 4.1 Rewrite `app/Jobs/ExtraireDepensesDuRecu.php` to accept `Receipt` ID in constructor
- [x] 4.2 Implement `handle()` method: call `ExtractionService`, create `Expenses` records, update receipt status to `processed`, store parsed data in `payload_ia`
- [x] 4.3 Implement `failed()` method: set receipt status to `failed`, store error message and timestamp in `payload_ia`
- [x] 4.4 Set job timeout to 60 seconds, maxTries to 3, and configure retry backoff

## 5. Controller Integration

- [x] 5.1 In `ReceiptController::store()`, dispatch `ExtraireDepensesDuRecu` job after creating the `Receipt` record
- [x] 5.2 Pass the receipt ID to the job constructor: `ExtraireDepensesDuRecu::dispatch($recu->id)`

## 6. Verification

- [x] 6.1 Verify `OPENAI_API_KEY` is read from env and the service connects to the API
- [x] 6.2 Test end-to-end: submit receipt text → job dispatched → extraction runs → expenses created → receipt status = processed
- [x] 6.3 Test error handling: invalid API key → receipt status = failed, error in payload_ia
- [x] 6.4 Test category fallback: AI returns unknown category → expense uses `Autre`
