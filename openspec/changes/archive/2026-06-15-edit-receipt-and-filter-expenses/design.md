## Context

The Assistant Depenses app currently supports creating receipts via text paste, AI-powered extraction of expenses, viewing, and deleting. There is no way to edit receipt data after creation — if the AI makes a mistake, the user must delete and re-submit. Additionally, as receipts accumulate, there is no filtering mechanism to focus on specific expense categories.

The codebase follows Laravel conventions with Blade views, Tailwind CSS, and Alpine.js. Routes are named with `Receipt.*` prefix. The `ReceiptController` has commented-out `edit()` and `update()` stubs. Expenses are displayed in a simple table on the receipt show page with no interactivity.

## Goals / Non-Goals

**Goals:**
- Allow users to edit a receipt's source text after creation
- Allow users to manually add, edit, and delete individual expenses on a processed receipt
- Add category filter on the receipt detail page to filter the expense table
- Add category filter on the receipts index page to filter receipts by expense category
- Keep changes consistent with existing Blade/Tailwind/Alpine patterns

**Non-Goals:**
- Re-running AI extraction on edited text (out of scope for now)
- Bulk editing of expenses across multiple receipts
- Advanced search or date-range filtering
- Changing the expense data model or categories

## Decisions

### 1. Edit receipt source text via dedicated edit page
**Decision**: Add a `receipt/edit.blade.php` view with a form to update `source_text`.
**Rationale**: Follows standard Laravel resource controller pattern. The existing stubs in `ReceiptController` already anticipated this. A separate edit page is cleaner than inline editing for this Blade-based UI.
**Alternatives considered**: Inline editing with Alpine.js — rejected for complexity; modal editing — rejected as less discoverable.

### 2. Expense CRUD via controller on the receipt show page
**Decision**: Create an `ExpenseController` with `store`, `update`, `destroy` methods. Forms will POST to these routes and redirect back to the receipt show page.
**Rationale**: Separates expense management from receipt management. The receipt show page becomes the hub for managing individual expenses. Using standard form submissions with redirects keeps it consistent with existing patterns.
**Alternatives considered**: Adding expense methods to `ReceiptController` — rejected to avoid bloating the controller; API endpoints with AJAX — rejected to keep the app server-rendered.

### 3. Category filtering via query parameters
**Decision**: Add `?categorie=` query parameter to both the receipts index and receipt show routes. The controller filters the query builder accordingly.
**Rationale**: Simple, URL-shareable, no JavaScript required. Follows standard Laravel filtering patterns. The existing enum provides the valid filter values.
**Alternatives considered**: Alpine.js client-side filtering — rejected because it wouldn't work with paginated data; session-based filter state — rejected for complexity.

### 4. Only allow editing on processed receipts
**Decision**: The edit link and expense management forms only appear when `receipt.status === 'processed'`.
**Rationale**: Editing a pending receipt could conflict with the extraction job. Failed receipts could be re-submitted instead. Only processed receipts have stable expense data worth editing.
**Alternatives considered**: Allow editing on all statuses with warnings — rejected for simplicity.

## Risks / Trade-offs

- **[Race condition on edit during extraction]** → Mitigated by restricting edits to processed receipts only.
- **[Expense totals may become inconsistent after manual edits]** → The `prix_total` accessor computes dynamically from `quantite * prix_unitaire`, so totals are always consistent.
- **[Filter state lost on navigation]** → Acceptable; filters are in the URL so back/forward works naturally.
