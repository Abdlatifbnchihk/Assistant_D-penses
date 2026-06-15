## 1. Design System Foundation

- [x] 1.1 Add Google Fonts links (Fraunces, Inter, IBM Plex Mono) to `layouts/app.blade.php` head
- [x] 1.2 Add CSS custom properties for all design tokens (colors, fonts) to `resources/css/app.css`
- [x] 1.3 Create `.ticket` class with paper background, border, and scalloped bottom edge
- [x] 1.4 Create `.stamp` class with rotation, double border, and monospace font
- [x] 1.5 Create `.lined-paper` class with repeating horizontal line background
- [x] 1.6 Create `.btn-ink` (solid teal) and `.btn-ghost` (transparent) button classes
- [x] 1.7 Create `.banner-info` (teal) and `.banner-red` banner classes
- [x] 1.8 Add focus ring styles (2px solid teal, 2px offset) for accessibility

## 2. Master Layout Update

- [x] 2.1 Update `layouts/app.blade.php` body background to `page_bg` color
- [x] 2.2 Update navigation to ledger-tab style (monospace, uppercase, teal underline on active)
- [x] 2.3 Add brand mark (receipt icon SVG) to top bar
- [x] 2.4 Update flash message rendering to use banner classes
- [x] 2.5 Update validation error rendering to use banner-red class

## 3. Receipt Index View

- [x] 3.1 Restyle `Receipt/index.blade.php` — each receipt as a ticket card
- [x] 3.2 Add status stamp badges (amber=pending, green=processed, red=failed)
- [x] 3.3 Style actions as ghost buttons with dashed divider
- [x] 3.4 Style empty state as a single centered ticket

## 4. Receipt Create View

- [x] 4.1 Restyle `Receipt/create.blade.php` — form inside a ticket card
- [x] 4.2 Apply lined-paper styling to textarea
- [x] 4.3 Style submit button as btn-ink
- [x] 4.4 Add helper text and async processing note below ticket

## 5. Receipt Show View

- [x] 5.1 Restyle `Receipt/show.blade.php` — full-length ticket with centered header
- [x] 5.2 Add status stamp badge to header
- [x] 5.3 Apply lined-paper styling to source text block
- [x] 5.4 Style expense rows with category stamps and monospace numbers
- [x] 5.5 Add dashed divider and bold Total row
- [x] 5.6 Restyle category filter as stamp-style chips (active colored, inactive dimmed)

## 6. Receipt Edit View

- [x] 6.1 Restyle `Receipt/edit.blade.php` — form inside a ticket card
- [x] 6.2 Apply lined-paper styling to textarea
- [x] 6.3 Style buttons as btn-ink (save) and btn-ghost (cancel)

## 7. Accessibility and Polish

- [x] 7.1 Add `@media (prefers-reduced-motion: no-preference)` wrapper for ticket hover lift/shadow
- [x] 7.2 Ensure status and category convey both color AND text label (never color alone)
- [x] 7.3 Verify color contrast exceeds WCAG AA for ink-on-paper and teal-on-paper
