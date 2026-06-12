## Context

The assistant-depenses project is a Laravel 13.8 expense tracking app where users paste raw receipt text and the system should automatically extract structured expense items (label, quantity, unit price, category). The infrastructure is in place: a `Receipt` model with `status` and `payload_ia` columns, an `Expenses` model with the correct schema, a queue job skeleton (`ExtraireDepensesDuRecu`), database queue tables, and Blade views ready to display results. However, no AI extraction logic exists — the job is a stub, the controller never dispatches it, and no service layer exists.

## Goals / Non-Goals

**Goals:**
- Implement an `ExtractionService` that calls an LLM API with receipt text and returns structured JSON expense items
- Wire the job to accept a `Receipt`, call the service, parse results, and create `Expenses` records
- Dispatch the job automatically from `ReceiptController::store()`
- Handle errors gracefully: mark receipts as `failed` and log error details in `payload_ia`
- Fix the `Expenses` model cast bug and add `prix_total` accessor

**Non-Goals:**
- Image/OCR support (current input is text-only paste)
- Multi-language AI prompts (French-only is fine for now)
- Real-time progress updates or websockets
- Payment or billing integration
- File upload or drag-and-drop receipt submission

## Decisions

### 1. Use `openai-php/client` for AI API calls
**Choice**: Install the `openai-php/laravel` package to call OpenAI's API.
**Why**: First-class Laravel integration, maintained, widely used, supports JSON mode for structured output. Alternatives considered: raw HTTP calls (more boilerplate, no retries), Laravel HTTP client alone (less typed), local LLM (requires infrastructure the user likely doesn't have).
**Trade-off**: Requires an OpenAI API key. Mitigated by using `.env` config and the package's publishable config.

### 2. JSON mode for structured output
**Choice**: Use OpenAI's `response_format: json_object` with a carefully crafted system prompt that defines the exact JSON schema for expenses.
**Why**: Guarantees parseable output, reduces hallucination on field names, simplifies parsing logic. The prompt will instruct the model to return `{"expenses": [{"libelle": "...", "quantite": N, "prix_unitaire": N, "categorie": "..."}]}`.
**Trade-off**: JSON mode requires compatible models (gpt-4o, gpt-4o-mini). Prompt engineering needed to handle edge cases (unknown categories, missing quantities).

### 3. Service layer pattern
**Choice**: Create `App\Services\ExtractionService` as a dedicated class, resolved via Laravel's container.
**Why**: Separates AI API concerns from job logic, makes testing easier (mock the service), follows existing Laravel conventions (no services dir yet, but this establishes the pattern).
**Alternatives considered**: Putting extraction logic directly in the job (violates SRP, harder to test), using a facade (unnecessary indirection for a single service).

### 4. Category mapping with fallback
**Choice**: The AI prompt will output category names matching `CategorieExpensesEnum` values. The service will validate and map to the enum, falling back to `autre` for unrecognized values.
**Why**: Resilient to AI output variations. The enum already defines the valid categories.

### 5. Job timeout and retry strategy
**Choice**: Set job timeout to 60 seconds (AI API calls can be slow), max tries = 3 with exponential backoff.
**Why**: Network calls are inherently unreliable. 60s covers typical OpenAI response times. 3 retries handle transient failures without infinite loops.

## Risks / Trade-offs

- **[Risk] AI API downtime or rate limits** → Receipt stays in `pending` state. Mitigated by retry logic and failed job tracking. Could add user notification in a future iteration.
- **[Risk] AI returns malformed JSON despite JSON mode** → Job marks receipt as `failed` with error in `payload_ia`. User can retry by re-submitting.
- **[Risk] AI misclassifies categories** → Fallback to `autre`. Acceptable for v1; can refine prompt or add a correction UI later.
- **[Trade-off] Synchronous vs async extraction** → Chose async (queue) for better UX (user sees "processing" immediately) and to avoid blocking the request. Trade-off: user must refresh to see results. A future enhancement could add polling or notifications.
- **[Trade-off] OpenAI dependency** → Choosing a specific provider. Could abstract behind an interface, but YAGNI for v1. Can refactor later if switching providers.
