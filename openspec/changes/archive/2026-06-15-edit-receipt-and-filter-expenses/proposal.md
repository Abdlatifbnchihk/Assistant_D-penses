## Why

Users currently have no way to correct receipt data after AI extraction — if the AI misreads an item, mis-categorizes it, or the user spots an error, the only option is to delete the entire receipt and re-submit. Additionally, as the number of extracted expenses grows, there is no way to browse or filter expenses by category, making it hard to review spending patterns.

## What Changes

- Add edit/update functionality for receipts (source text and extracted expenses)
- Add ability to manually add, edit, and delete individual expenses on a receipt
- Add category filter on the receipt detail page to filter the expense list
- Add category filter on the receipts index page to filter receipts by expense category

## Capabilities

### New Capabilities
- `receipt-editing`: Allow users to edit receipt source text and manage individual expenses (add, edit, delete) on a processed receipt
- `expense-category-filtering`: Filter expenses and receipts by category on list and detail views

### Modified Capabilities

## Impact

- **Controllers**: `ReceiptController` will gain `edit`, `update` methods; new `ExpenseController` for CRUD on individual expenses
- **Routes**: New routes for receipt edit/update, expense store/update/destroy
- **Views**: `receipt/edit.blade.php` (new), updates to `receipt/show.blade.php` and `receipt/index.blade.php` for filters
- **Models**: `Receipt` and `Expenses` models remain largely unchanged; may add scopes for category filtering
- **Validation**: New form request classes for receipt update and expense store/update
