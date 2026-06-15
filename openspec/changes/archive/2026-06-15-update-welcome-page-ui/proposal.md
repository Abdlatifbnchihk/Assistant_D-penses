## Why

The current welcome page uses a standalone HTML structure with its own inline CSS and a separate navbar, making it visually inconsistent with the rest of the app. When logged in, users are redirected to the dashboard (which is a blank "You're logged in!" page) instead of seeing the welcome page. The welcome page should use the app's shared layout and navigation for a cohesive experience.

## What Changes

- Rewrite `welcome.blade.php` to use `<x-app-layout>` for shared navbar and design system
- Remove standalone CSS (rely on the app's design system from `app.css`)
- Update the route to show welcome page for both logged-in and guest users
- Keep the welcome page content (hero, before/after demo, how it works, CTA)
- When logged in, show navbar with receipt links; when guest, show login/register links

## Capabilities

### New Capabilities

### Modified Capabilities

## Impact

- **Views**: `resources/views/welcome.blade.php` — full rewrite to use app layout
- **Routes**: `routes/web.php` — remove auth middleware from `/` route, potentially remove or repurpose dashboard route
- **No backend changes**: This is a frontend-only change
