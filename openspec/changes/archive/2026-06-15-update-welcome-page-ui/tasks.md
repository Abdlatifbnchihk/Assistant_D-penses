## 1. Rewrite welcome.blade.php with auth-aware navbar

Rewrite `resources/views/welcome.blade.php` to:
- Use standalone HTML with `@vite(['resources/css/app.css', 'resources/js/app.js'])` for design system
- Use `@guest`/`@else`/`@endguest` to show different navbars for guests vs authenticated users
- Guest navbar: brand mark + Connexion/Créer un compte links
- Auth navbar: `@include('layouts.navigation')` for shared navigation
- Use Tailwind classes for layout and spacing
- Keep all existing content: hero, before/after demo, how it works, CTA, footer
- Use design system component classes: `.ticket`, `.ticket-hover`, `.stamp`, `.stamp-rotate`, `.stamp-processed`, `.stamp-food`, `.stamp-drink`, `.stamp-cleaning`, `.lined-paper`, `.divider-dashed`, `.btn-ink`, `.btn-ghost`, `.btn-outline`, `.font-display`
- Flash messages and error handling included

**Files**:
- `resources/views/welcome.blade.php`

**Verification**: View renders correctly for both guest and logged-in users, no standalone CSS conflicts.

## 2. Update navigation.blade.php for guest users

Update `resources/views/layouts/navigation.blade.php` to wrap all `Auth::user()` calls in `@auth` directives, add guest login/register links in both desktop and mobile menus, and point brand link to `/` for guests.

**Files**:
- `resources/views/layouts/navigation.blade.php`

**Verification**: No errors when rendered for guest users.

## 3. Remove dashboard route

Update `routes/web.php` to remove the `/dashboard` route since welcome page now serves as the landing page for all users.

**Files**:
- `routes/web.php`

**Verification**: Visiting `/` shows welcome page; no dead dashboard route.
