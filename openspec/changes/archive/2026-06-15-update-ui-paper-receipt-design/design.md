## Context

The app currently uses standard Tailwind CSS with generic styling. The config.yaml defines a detailed "corner-store ledger / paper receipt" design system with specific tokens, typography, and component patterns — but none of it has been implemented. Views use default Blade components and basic Tailwind utility classes.

## Goals / Non-Goals

**Goals:**
- Implement the full design system from config.yaml in CSS
- Restyle all Blade views to use ticket cards, stamp badges, and lined paper
- Update navigation to ledger-tab style
- Apply correct typography (Fraunces, Inter, IBM Plex Mono)
- Meet accessibility requirements (focus rings, reduced motion, contrast)

**Non-Goals:**
- Changing backend logic or routes
- Adding new features or views
- Changing the data model
- Implementing the image upload or expense ledger views (not yet built)

## Decisions

### 1. CSS variables + utility classes in app.css
**Decision**: Define all design tokens as CSS custom properties in `resources/css/app.css` and create utility classes (.ticket, .stamp, .lined-paper, etc.).
**Rationale**: Keeps the design system centralized and maintainable. CSS variables allow easy theme adjustments. Utility classes keep Blade templates clean.
**Alternatives considered**: Tailwind config extension — rejected because some effects (scalloped edges, stamp rotation) need custom CSS that can't be done with Tailwind utilities alone.

### 2. Google Fonts loaded via layout head
**Decision**: Add Google Fonts `<link>` tags for Fraunces, Inter, and IBM Plex Mono directly in `layouts/app.blade.php`.
**Rationale**: Simple, no build dependency. The fonts are already specified in config.yaml.
**Alternatives considered**: Self-hosting — rejected for simplicity; @import in CSS — rejected for performance (render-blocking).

### 3. Scalloped ticket edge via CSS mask
**Decision**: Use `mask-image` with `radial-gradient` to create the torn/perforated bottom edge on ticket cards.
**Rationale**: Pure CSS, no images needed. Works across modern browsers.
**Alternatives considered**: SVG background — rejected for complexity; border-image — rejected for limited control.

### 4. Stamp effect via CSS transform + border
**Decision**: Use `transform: rotate(-3deg)` with a double border and monospace font for stamp badges.
**Rationale**: Simple CSS that matches the rubber-ink-stamp aesthetic described in config.yaml.
**Alternatives considered**: SVG stamps — rejected for dynamic content difficulty.

## Risks / Trade-offs

- **[Font loading performance]** → Mitigated by using `display=swap` and only loading needed weights.
- **[Browser support for mask-image]** → Mitigated by providing a fallback solid border for older browsers.
- **[Scalped edge alignment]** → The edge is at the bottom of the card; content must not overlap it.
