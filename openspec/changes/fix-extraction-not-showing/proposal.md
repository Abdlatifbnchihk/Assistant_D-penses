## Why

After implementing the AI extraction feature, extracted expenses don't display in the UI. The Blade views reference `$receipt->depenses` (French) but the `Receipt` model relationship is defined as `expenses()` (English). This causes the expenses table to always show "Aucune dépense extraite" even when expenses exist in the database.

## What Changes

- Fix `show.blade.php`: change `$receipt->depenses` to `$receipt->expenses` and `$depense` to `$expense`
- Fix `index.blade.php`: change `$recu->depenses->count()` to `$recu->expenses->count()`

## Capabilities

### New Capabilities
<!-- None — this is a bug fix, not a new capability. -->

### Modified Capabilities
<!-- None — no spec-level behavior changes, just a view rendering fix. -->

## Impact

- **Code**: `resources/views/Receipt/show.blade.php`, `resources/views/Receipt/index.blade.php`
- **Dependencies**: None
- **Database**: None
