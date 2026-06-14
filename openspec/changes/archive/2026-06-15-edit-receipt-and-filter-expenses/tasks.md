## 1. Receipt Editing - Backend

- [x] 1.1 Create `UpdateRecieptRequest` form request class with validation rules for `source_text` (required, 10-5000 chars)
- [x] 1.2 Add `edit(Receipt $recu)` method to `ReceiptController` with ownership check, returning `Receipt.edit` view
- [x] 1.3 Add `update(UpdateRecieptRequest $request, Receipt $recu)` method to `ReceiptController` with ownership check, updating `source_text` and redirecting to show page
- [x] 1.4 Add `GET /receipt/{recu}/edit` and `PUT /receipt/{recu}` routes to `routes/web.php`

## 2. Receipt Editing - Frontend

- [x] 2.1 Create `resources/views/Receipt/edit.blade.php` with pre-filled textarea form for `source_text`
- [x] 2.2 Add "Modifier" link on `receipt/show.blade.php` (visible only for `processed` receipts)
- [x] 2.3 Add "Modifier" link on `receipt/index.blade.php` in the actions column (visible only for `processed` receipts)

## 3. Expense CRUD - Backend

- [x] 3.1 Create `ExpenseController` with `store(Request, Receipt)`, `update(Request, Expense)`, `destroy(Expense)` methods
- [x] 3.2 Add ownership checks in `ExpenseController` — verify receipt belongs to authenticated user
- [x] 3.3 Add `StoreExpenseRequest` form request with validation: `libelle` required, `quantite` integer >= 1, `prix_unitaire` numeric > 0, `categorie` in `CategorieExpensesEnum` values
- [x] 3.4 Add `UpdateExpenseRequest` form request with same validation rules
- [x] 3.5 Add routes: `POST /receipt/{recu}/expense`, `PUT /expense/{expense}`, `DELETE /expense/{expense}`

## 4. Expense CRUD - Frontend

- [x] 4.1 Add inline "Ajouter une dépense" form at the bottom of the expenses table on `receipt/show.blade.php` (visible only for `processed` receipts)
- [x] 4.2 Add "Modifier" and "Supprimer" action buttons to each expense row on `receipt/show.blade.php`
- [x] 4.3 Create a reusable Blade partial or inline form for editing expense fields (libelle, quantite, prix_unitaire, categorie)
- [x] 4.4 Use Alpine.js or form submission with redirect for edit/delete actions

## 5. Category Filtering - Receipt Detail Page

- [x] 5.1 Add category filter dropdown to `receipt/show.blade.php` above the expenses table
- [x] 5.2 Update `ReceiptController@show` to accept `?categorie=` query parameter and filter expenses
- [x] 5.3 Ensure filter preserves receipt context and only filters the displayed expenses

## 6. Category Filtering - Receipts Index Page

- [x] 6.1 Add category filter dropdown to `receipt/index.blade.php` above the receipts table
- [x] 6.2 Update `ReceiptController@index` to accept `?categorie=` query parameter and filter receipts that contain expenses in the selected category
- [x] 6.3 Ensure filter uses `whereHas` on the expenses relationship to match receipts with at least one expense in the category

## 7. Polish and Validation

- [x] 7.1 Verify all form submissions redirect back with success flash messages
- [x] 7.2 Ensure consistent French UI labels throughout (Modifier, Supprimer, Ajouter, Toutes, etc.)
- [x] 7.3 Test ownership checks — non-owner gets 403 on all edit/delete operations
