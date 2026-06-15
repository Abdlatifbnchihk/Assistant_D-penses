## Why

The current UI uses generic Tailwind CSS styling that doesn't reflect the app's identity as a receipt/expense tracking tool. The config.yaml defines a "corner-store ledger / paper receipt" design system with ticket cards, rubber stamps, lined paper backgrounds, and specific typography — but none of this has been implemented yet. Applying this design system will make the app visually cohesive and thematically appropriate.

## What Changes

- Add global CSS design system (variables, fonts, component classes) to the master layout
- Restyle all Blade views to use ticket cards, stamp badges, and lined paper aesthetics
- Update navigation to use ledger-tab style
- Apply consistent typography (Fraunces for headings, Inter for body, IBM Plex Mono for numbers)
- Ensure accessibility requirements are met (focus rings, reduced motion, color contrast)

## Capabilities

### New Capabilities
- `paper-receipt-design-system`: Global CSS variables, fonts, and reusable component classes (.ticket, .stamp, .lined-paper, .btn-ink, .btn-ghost, .banner) applied to the master layout

### Modified Capabilities
- `receipt-editing`: Views restyled to use ticket cards and stamp badges (no requirement changes, only visual)
- `expense-category-filtering`: Filter chips restyled as stamp-style toggles (no requirement changes, only visual)

## Impact

- **Views**: `layouts/app.blade.php`, `Receipt/index.blade.php`, `Receipt/create.blade.php`, `Receipt/show.blade.php`, `Receipt/edit.blade.php`, `layouts/navigation.blade.php`
- **CSS**: New design tokens and component classes in `resources/css/app.css` or inline in layout
- **Fonts**: Google Fonts imports (Fraunces, Inter, IBM Plex Mono) added to layout head
- **No backend changes**: This is a frontend-only change
