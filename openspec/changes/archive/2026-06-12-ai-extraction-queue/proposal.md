## Why

The receipt extraction workflow is currently a skeleton: users paste raw receipt text, a `Receipt` record is created with `status=pending`, but no AI processing occurs. The `ExtraireDepensesDuRecu` job is a stub (`sleep(20)` + log), the job is never dispatched from the controller, and the `payload_ia` JSON column is never populated. Users cannot get automated expense extraction from their receipt text.

## What Changes

- Implement AI-powered receipt text extraction using a structured output approach (call an LLM API, parse the response into typed expense items)
- Wire the `ExtraireDepensesDuRecu` job to accept a `Receipt` model, call the AI service, parse the structured JSON response, and create `Expenses` records
- Dispatch the job from `ReceiptController::store()` so extraction happens automatically after receipt creation
- Add a service layer (`App\Services\ExtractionService`) to encapsulate the AI API call and response parsing
- Add proper error handling: update receipt status to `failed` on extraction errors, record error details in `payload_ia`
- Add a `prix_total` accessor on the `Expenses` model (the Blade view already references it but the accessor doesn't exist)
- Fix the cast key mismatch in `Expenses` model (`category` vs `categorie` column name)

## Capabilities

### New Capabilities
- `ai-extraction-service`: Core AI extraction logic — calls an LLM API with the receipt text, receives structured JSON output with expense items (label, quantity, unit price, category), validates and persists them as `Expenses` records.

### Modified Capabilities
<!-- None — no existing spec-level behavior changes. -->

## Impact

- **Code**: `app/Jobs/ExtraireDepensesDuRecu.php` (full rewrite), new `app/Services/ExtractionService.php`, `app/Http/Controllers/ReceiptController.php` (dispatch job), `app/Models/Expenses.php` (fix cast + add accessor)
- **Dependencies**: Will require an AI API client (e.g., `openai-php/client` or HTTP client for API calls). No breaking API changes.
- **Database**: No schema changes — existing `recus.payload_ia` and `expenses` tables are sufficient.
- **Queue**: Existing database queue driver and `jobs` table are used as-is.
