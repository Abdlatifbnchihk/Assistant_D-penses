# Paper Receipt Design System

## Purpose

Defines the visual design system for the application: a "corner-store ledger / paper receipt" aesthetic with ticket cards, rubber stamp badges, lined paper backgrounds, and specific typography (Fraunces, Inter, IBM Plex Mono).

## Requirements

### Requirement: Master layout defines the paper receipt design system
The system SHALL provide a master layout that defines CSS custom properties for all design tokens (colors, typography) and reusable component classes (.ticket, .stamp, .lined-paper, .btn-ink, .btn-ghost, .banner).

#### Scenario: Design tokens available in all views
- **WHEN** any Blade view is rendered
- **THEN** CSS custom properties for colors (page_bg, paper, line, ink, ink_soft, teal, teal_soft, amber, green, red) and typography (Fraunces, Inter, IBM Plex Mono) are available

#### Scenario: Ticket card component renders with scalloped edge
- **WHEN** an element has class `ticket`
- **THEN** it displays with a paper background color, subtle border, and a scalloped/perforated bottom edge effect

#### Scenario: Stamp badge component renders with rotation
- **WHEN** an element has class `stamp`
- **THEN** it displays rotated -3 degrees with a double border and monospace font, resembling a rubber ink stamp

#### Scenario: Lined paper background renders ruled lines
- **WHEN** an element has class `lined-paper`
- **THEN** it displays a repeating horizontal line pattern mimicking handwritten ledger paper

### Requirement: Navigation uses ledger-tab style
The system SHALL render top navigation tabs styled as monospace, uppercase, letter-spaced labels with a teal underline on the active tab.

#### Scenario: Active tab has teal underline
- **WHEN** a user views any page
- **THEN** the active navigation tab has a teal underline and full opacity
- **AND** inactive tabs have reduced opacity

### Requirement: Flash messages render as styled banners
The system SHALL render success flash messages as teal banners and validation error messages as red banners.

#### Scenario: Success message displays as teal banner
- **WHEN** a flash success message is present
- **THEN** it renders in a teal-colored banner with appropriate icon

#### Scenario: Validation errors display as red banner
- **WHEN** validation errors are present
- **THEN** they render in a red-colored banner with bullet list
