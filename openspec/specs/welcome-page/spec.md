# Welcome Page

## Purpose

Landing page for Assistant Dépenses, shown to both guest and authenticated users. Provides an overview of the app's value proposition and entry points for registration/login or receipt management.

## Requirements

### REQ-1: Welcome page shows for all users
When a user (guest or authenticated) visits `/`, they MUST see the welcome page. No redirect to dashboard or login.

### REQ-2: Welcome page content
The welcome page MUST display:
- Hero section with value proposition
- Before/after demo showing receipt text → extracted expenses
- "How it works" section (3 steps)
- Call-to-action section
- Footer

### REQ-3: Navigation adapts to auth state
The welcome page navbar MUST show:
- Guest: brand mark, "Connexion" and "Créer un compte" links
- Authenticated: shared navigation component with "Reçus" tab, user dropdown, and hamburger menu

### REQ-4: Auth-aware rendering
The welcome page uses `@guest`/`@else`/`@endguest` Blade directives to conditionally render either a standalone guest navbar or the shared `layouts.navigation` component for authenticated users.

### REQ-5: Design system usage
The welcome page MUST use the app's design system classes from `app.css`:
- `.ticket`, `.ticket-hover` for card sections
- `.stamp`, `.stamp-rotate`, `.stamp-processed`, `.stamp-food`, `.stamp-drink`, `.stamp-cleaning` for category badges
- `.lined-paper` for the "before" demo text
- `.divider-dashed` for totals
- `.btn-ink`, `.btn-ghost`, `.btn-outline` for CTAs
- `.font-display`, `.font-mono` for typography
- Colors via CSS custom properties: `--color-page-bg`, `--color-paper`, `--color-line`, `--color-ink`, `--color-ink-soft`, `--color-teal`, `--color-red`, `--color-green`

### REQ-6: Standalone CSS removed
The welcome page MUST NOT contain inline `<style>` blocks or standalone CSS. All styling comes from `app.css` loaded via `@vite`.

### REQ-7: Flash messages
The welcome page MUST display success and error flash messages using the same banner components as other pages.
