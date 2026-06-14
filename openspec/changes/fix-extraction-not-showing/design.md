## Context

The `Receipt` model defines the relationship as `expenses()` (English), but the Blade views reference `depenses` (French). This is a naming inconsistency introduced during the initial implementation. The fix is a direct variable rename in two Blade template files.

## Goals / Non-Goals

**Goals:**
- Fix the view rendering so extracted expenses display correctly
- Ensure the `show` and `index` pages iterate over the correct relationship

**Non-Goals:**
- Refactoring the relationship name (changing `expenses()` to `depenses()` would break the model and job)
- Adding new features or tests

## Decisions

**Decision:** Rename the Blade template variables to match the existing model relationship (`expenses`) rather than changing the model to match the views.
**Why:** The model relationship `expenses()` is used in the controller (`$receipt->expenses`), the job (`$receipt->expenses()->createMany()`), and the extraction service. Changing the model would require updating all these locations. Fixing the two Blade files is simpler and less error-prone.

## Risks / Trade-offs

- **[Risk]** None — this is a straightforward variable rename with no behavioral change.
