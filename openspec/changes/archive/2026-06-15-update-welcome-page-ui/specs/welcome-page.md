## ADDED Requirements

### REQ-1: Welcome page uses app layout
The welcome page (`welcome.blade.php`) MUST use `<x-app-layout>` to render within the shared layout, inheriting the same navbar, fonts, and CSS as all other pages.

### REQ-2: Welcome page shows for all users
When a user (guest or authenticated) visits `/`, they MUST see the welcome page. No redirect to dashboard or login.

### REQ-3: Welcome page content
The welcome page MUST display:
- Hero section with value proposition
- Before/after demo showing receipt text → extracted expenses
- "How it works" section (3 steps)
- Call-to-action section
- Footer

### REQ-4: Navigation adapts to auth state
The welcome page navbar (via `navigation.blade.php`) MUST show:
- Guest: "Connexion" and "Créer un compte" links
- Authenticated: "Mes reçus" link and navigation tabs

## MODIFIED Requirements

### REQ-WELCOME-1: Welcome page rendering
The welcome page view is modified to use `<x-app-layout>` instead of standalone HTML.

## REMOVED Requirements

### REQ-WELCOME-INLINE-CSS: Standalone CSS
The welcome page's inline `<style>` block and standalone CSS are removed. All styling comes from `app.css` via the layout.

## RENAMED Requirements

### REQ-WELCOME-NAVBAR: Standalone top bar
Renamed to reference the shared `navigation.blade.php` component instead of the standalone `.topbar` markup.

## UNCHANGED Requirements

- Welcome page content structure (hero, demo, how it works, CTA, footer) remains the same
- Before/after demo uses the same ticket cards and stamp components
- Step cards use the same ticket-hover component
