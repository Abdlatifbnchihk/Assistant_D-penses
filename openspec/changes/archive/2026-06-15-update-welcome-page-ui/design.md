## Context

The welcome page is the landing page for both guests and logged-in users. It needs to match the app's paper-receipt design system and use the shared layout with navigation.

## Objectives

- Consistent visual experience using the app's design system (fonts, colors, components)
- Same navbar as other pages via `<x-app-layout>`
- Welcome page shows for all users (guest and authenticated)
- Guest users see login/register; logged-in users see receipt links

## Approach

- Rewrite `welcome.blade.php` to use `<x-app-layout>` with `slot` content
- Remove all standalone CSS (design tokens, component classes) — use `app.css` instead
- Keep the existing content sections (hero, before/after demo, how it works, CTA, footer)
- Use the app's Tailwind classes for spacing and layout
- Preserve the same SVG receipt icon in the navbar (already in `navigation.blade.php`)

## Risks & Mitigations

- **Risk**: Breaking existing guest experience. **Mitigation**: Keep same content, just restructure to use layout.
- **Risk**: Dashboard route becomes unused. **Mitigation**: Keep dashboard route for now, can be removed later.
