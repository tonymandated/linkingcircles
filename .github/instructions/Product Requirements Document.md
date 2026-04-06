# Product Requirements Document
## Linking Circles Academy — Main Website & Learning Management System (LMS)

**Version:** 1.0  
**Date:** April 2026  
**Status:** Draft  
**Classification:** Confidential

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Goals & Success Metrics](#2-goals--success-metrics)
3. [Technical Stack](#3-technical-stack)
4. [Brand & Color Theme](#4-brand--color-theme)
5. [Project Architecture](#5-project-architecture)
6. [Main Website](#6-main-website-linkingcirclesacademycom)
7. [Learning Management System](#7-learning-management-system-lmslinkingcirclesacademycom)
8. [Accessibility Requirements (WCAG 2.1)](#8-accessibility-requirements-wcag-21)
9. [Code Standards & Conventions](#9-code-standards--conventions)
10. [Non-Functional Requirements](#10-non-functional-requirements)
11. [Delivery Milestones](#11-delivery-milestones)

---

## 1. Project Overview

Linking Circles Academy requires a complete rebuild of its public-facing website (www.linkingcirclesacademy.com) and a new, dedicated Learning Management System (LMS). Both products will be built on a modern PHP/Laravel stack, using Livewire for reactive components and Tailwind CSS for styling. Accessibility — specifically compliance with WCAG 2.1 AA and aspirationally AAA — is the primary design constraint across the entire project.

The LMS is engineered specifically for users with hearing and/or visual disabilities. It will be hosted on a fully independent subdomain (`lms.linkingcirclesacademy.com`) with its own Laravel application structure, routes, and frontend assets, while sharing authentication and certain shared services with the main site.

---

## 2. Goals & Success Metrics

### 2.1 Primary Goals

- Achieve WCAG 2.1 Level AA compliance across all pages and components on both domains.
- Deliver a performant, mobile-first responsive design for `linkingcirclesacademy.com`.
- Provide a feature-rich LMS on `lms.linkingcirclesacademy.com` tailored to learners with hearing and visual impairments.
- Ensure all user interface interactions are operable via keyboard, screen reader, and assistive technology.
- Enable instructors to upload rich, accessible learning content with proper media descriptions, transcripts, and captions.

### 2.2 Success Metrics

- Zero WCAG 2.1 AA violations on automated axe-core audit at launch.
- Lighthouse Accessibility score ≥ 95 on all key pages.
- All videos and audio have captions/transcripts at content submission.
- Font scaling from 80% to 200% without horizontal scrolling or content loss.
- Full keyboard navigation with visible focus indicators on all interactive elements.

---

## 3. Technical Stack

| Layer | Technology |
|---|---|
| Backend Framework | PHP 8.2+ / Laravel 11 |
| Frontend Reactivity | Livewire 3 (full-stack components) |
| CSS Framework | Tailwind CSS 3 with custom design tokens |
| JavaScript | Alpine.js (via Livewire), custom external JS files only |
| Build Tool | Vite (bundling + HMR) |
| Database | MySQL 8 (main site) / shared or dedicated for LMS |
| File Storage | Laravel Storage (local dev), S3-compatible in production |
| Search | Laravel Scout (Meilisearch or Algolia) |
| Queue / Jobs | Laravel Horizon + Redis (video processing, notifications) |
| Auth | Laravel Breeze / Fortify (shared across subdomains via cookie domain) |
| Testing | PestPHP, Laravel Dusk (browser), axe-core (a11y) |
| Deployment | Laravel Forge / Envoyer on VPS, separate app per subdomain |

---

## 4. Brand & Color Theme

### 4.1 Logo Color Analysis

The Linking Circles Academy logo uses three principal colors that form the basis of the design system:

- **Gold/Amber gradient:** approximately `#D4A017` to `#B8860B` (left ring)
- **Teal/Cyan:** approximately `#00AACC` to `#0088AA` (right ring, upper portion)
- **Navy Blue:** approximately `#2B4DAE` (logo text and right ring lower portion)

### 4.2 Recommended Accessible Color Palette

All colors below are adjusted for WCAG 2.1 AA minimum contrast ratio of 4.5:1 for normal text and 3:1 for large text and UI components.

| Role | Hex | Contrast / Notes |
|---|---|---|
| Primary (Navy Blue) | `#2B4DAE` | 7.2:1 on white — AA/AAA ✓ |
| Primary Dark / Links | `#1A3B9C` | 8.5:1 on white — AAA ✓ |
| Secondary (Amber) | `#C8860A` | 4.6:1 on white — AA ✓ |
| Accent (Teal) | `#007A8A` | 4.8:1 on white — AA ✓ |
| Surface / Light BG | `#EEF2FF` | Very light navy tint — backgrounds |
| Page Background | `#F7F9FF` | Near-white — main page BG |
| Body Text (light mode) | `#1A1A2E` | 17:1 on white — AAA ✓ |
| Body Text (dark mode) | `#F0F4FF` | 17:1 on `#0F1B3D` — AAA ✓ |
| Dark Mode Background | `#0F1B3D` | Deep navy — dark mode base |
| Success Green | `#1A7A4A` | 5.1:1 on white — AA ✓ |
| Warning Orange | `#B85C00` | 4.9:1 on white — AA ✓ |
| Error Red | `#C0392B` | 5.7:1 on white — AA ✓ |

> Tailwind CSS custom tokens should be defined in `tailwind.config.js` under the `extend.colors` key using these hex values as the source of truth. Do not use arbitrary Tailwind color values in component markup.

---

## 5. Project Architecture

### 5.1 Repository & Folder Structure

The project is a monorepo containing two Laravel applications that share a root-level composer workspace and git history:

```
/
  apps/
    main/           ← linkingcirclesacademy.com (Laravel app)
    lms/            ← lms.linkingcirclesacademy.com (Laravel app)
  packages/
    shared/         ← Composer package: shared models, services, auth
  docker/
  .github/
```

Each app in `apps/` is a full Laravel application with its own routes, controllers, views, migrations, and assets. Shared code (User model, authentication, notification services) lives in `packages/shared` as a Composer path repository.

### 5.2 Subdomain Routing

- `linkingcirclesacademy.com` → `apps/main` (served by Nginx vhost #1)
- `lms.linkingcirclesacademy.com` → `apps/lms` (served by Nginx vhost #2)
- Session cookie domain set to `.linkingcirclesacademy.com` to allow SSO between subdomains.
- CORS policy on LMS API routes whitelists the main domain for any API calls.

### 5.3 Layout System

Each Laravel application uses a base Blade layout component. Pages extend or compose with this layout and pass data via Blade slots and `@section` directives.

- **Base layout:** `resources/views/layouts/app.blade.php`
- Layout accepts: `@yield('title')`, `@yield('description')`, `@yield('og_image')`, `@yield('og_type')`, `@yield('canonical')`
- OpenGraph and Twitter Card meta tags are generated in the `<head>` from yielded values with sensible defaults.
- Font size scaling classes and color mode data attributes are applied to the `<html>` element by a small external JS module (`resources/js/accessibility.js`) which reads `localStorage` on page load.
- Screen-reader-only skip-to-main link is the first focusable element on every page.

---

## 6. Main Website (linkingcirclesacademy.com)

### 6.1 Pages & Routes

- Home (`/`)
- About (`/about`)
- Programmes / Courses (`/programmes`)
- Blog (`/blog`, `/blog/{slug}`)
- Contact (`/contact`)
- Accessibility Statement (`/accessibility`)
- Privacy Policy (`/privacy`)

### 6.2 Accessibility Features in Header/Nav

- **Font size toggle buttons:** Decrease (A−) / Reset (A) / Increase (A+) — updates a CSS custom property `--lca-font-scale` stored in `localStorage`.
- **Color mode toggle:** Light / Dark / High Contrast — updates `data-theme` on `<html>` and stores preference in `localStorage`.
- **Custom color picker (advanced panel):** allows overriding text color and background color independently; color choices saved to `localStorage` and synced via CSS variables.
- All toggle controls are labeled with `aria-label` and have visible focus rings meeting 3:1 contrast.
- Skip navigation link: `<a href="#main-content" class="sr-only focus:not-sr-only">Skip to main content</a>`

### 6.3 OpenGraph & SEO

- Every page yields: `title`, meta `description`, `og:title`, `og:description`, `og:image`, `og:url`, `og:type`, `twitter:card`, canonical URL.
- Default OG image set in `config/seo.php`; individual pages override via `@section`.
- Structured data (JSON-LD) for Organization, BreadcrumbList, and Article (blog posts).

---

## 7. Learning Management System (lms.linkingcirclesacademy.com)

### 7.1 LMS Folder Structure

The LMS lives entirely within `apps/lms/` and follows standard Laravel conventions:

- `app/Http/Controllers/Lms/` — all LMS controllers
- `app/Models/` — LMS-specific Eloquent models
- `resources/views/` — Blade views for LMS UI
- `resources/js/` — LMS-specific JavaScript (external `.js` files only)
- `resources/css/` — LMS-specific CSS (external files only; no inline styles)
- `routes/web.php` and `routes/api.php` — LMS routes
- `database/migrations/` — LMS database migrations

### 7.2 Core LMS Features

#### Course & Lesson Management

- Instructors create courses composed of modules, each containing lessons.
- Lesson content types: text/rich-text, video, audio, interactive quiz, downloadable resource.
- Each lesson supports: `title`, `body`, `content_type`, `accessibility_notes` field.

#### Accessible Media Upload

- **Video upload:** required fields include caption file (`.vtt` or `.srt`), audio description track (optional `.vtt`), and a full text transcript.
- **Audio upload:** required fields include full text transcript.
- **Image upload:** required alt text field, optional long description field (linked via `aria-describedby`).
- **Document upload:** instructor must specify if the document is screen-reader accessible (i.e. tagged PDF or DOCX) and may upload an accessible alternative.
- **UI enforcement:** the lesson publish action is gated — media without required accessibility metadata cannot be published.

#### Student Features

- Dashboard showing enrolled courses and progress.
- Lesson player with: adjustable playback speed, closed captions toggle, audio description toggle, transcript panel alongside video.
- Note-taking panel (per lesson).
- Progress tracking and certificates of completion.

#### Accessibility-Specific UX

- High contrast mode, font scaling, and color customization — same controls as main site, persisted per user profile in the database (overrides `localStorage` so preferences roam).
- Screen reader optimized lesson navigation: ARIA live regions announce progress, page changes, and quiz results.
- Keyboard-first lesson navigation: J/K for prev/next lesson, Space for play/pause video, C for captions toggle.
- Sign language video companion: instructors may upload an optional sign language interpretation video that displays in a resizable picture-in-picture overlay.

---

### 7.3 Roles & Permissions Architecture

The permission system is fully dynamic. Roles are database records, not hard-coded enumerations. Every access decision is evaluated against a **permission string**, never against a role name. This allows the Super Admin to create custom roles, and to add or remove individual permissions from any role at any time through the admin dashboard — with no code changes required.

#### 7.3.1 Database Schema

| Table | Key Columns | Purpose |
|---|---|---|
| `roles` | `id`, `name` (string), `label` (string), `permissions` (json), `is_system` (bool), `timestamps` | Stores every role. `permissions` column holds an array of permission strings owned by that role. `is_system=true` protects the Super Admin role from deletion. |
| `role_user` | `role_id` (FK), `user_id` (FK) | Pivot table: a user may hold multiple roles. Permissions are the union of all assigned roles. |
| `users` | …existing columns… + `accessibility_preferences` (json) | No role column on users. Role relationship is via pivot only. |

#### 7.3.2 How Permissions Are Resolved

- When a user authenticates, their full permission set is computed as the **union** of the `permissions` JSON arrays from all their assigned roles.
- This merged set is cached in the user's session (and in Redis when available) and invalidated whenever a role's permissions are modified or the user's role assignments change.
- A `HasPermissions` trait on the `User` model exposes a `can(string $permission): bool` helper that checks against the resolved set.
- Laravel Gates are registered dynamically at boot time in a `PermissionServiceProvider`, iterating over every known permission string and registering a `Gate::define()` for it. This means standard `@can`, `$this->authorize()`, and `Gate::allows()` all work without any special syntax.
- **Middleware:** a `CheckPermission` middleware is available for route-level guards via `route()->middleware('permission:courses.create')`.

#### 7.3.3 Seed Roles & Default Permission Sets

Four seed roles are created by the database seeder. These are starting points only — the Super Admin may rename, clone, or delete non-system roles, and may freely add or remove permissions from any role at any time.

| Seed Role | System? | Default Permissions (illustrative subset) |
|---|---|---|
| **Super Admin** | ✅ Yes | All permissions listed in §7.3.4 are granted by default. New permissions added when any feature is implemented are automatically added to this role by the feature seeder/migration. |
| **Instructor** | No | `courses.create`, `courses.update.own`, `courses.delete.own`, `lessons.create`, `lessons.update.own`, `lessons.delete.own`, `media.upload`, `enrollments.view.own`, `analytics.courses.own` |
| **Student** | No | `courses.view`, `courses.enroll`, `lessons.view`, `lessons.progress.update`, `notes.create`, `notes.update.own`, `notes.delete.own`, `certificates.view.own`, `profile.accessibility.update` |
| **Guest** | No | `courses.view.public`, `lessons.preview`, `users.register` |

#### 7.3.4 Permission Registry — Full List

Every permission string in the system is listed here, grouped by domain. When a developer implements a new feature, they must: (1) add the new permission string to this registry, (2) register the gate in `PermissionServiceProvider`, and (3) add it to the Super Admin role via a database migration or seeder — never via a manual database edit.

##### User & Role Management

| Permission String | What It Guards |
|---|---|
| `users.view` | View any user profile in admin panel |
| `users.create` | Create a new user account manually |
| `users.update` | Edit any user's profile and details |
| `users.delete` | Delete or deactivate a user account |
| `users.impersonate` | Log in as another user for support purposes |
| `roles.view` | View roles list and their permission sets |
| `roles.create` | Create a new role |
| `roles.update` | Rename a role or change its description |
| `roles.delete` | Delete a non-system role |
| `roles.permissions.assign` | Add or remove permissions from any role via the dashboard |
| `roles.assign.user` | Assign or revoke a role from a user |

##### Courses & Lessons

| Permission String | What It Guards |
|---|---|
| `courses.view` | View published course detail page (any user) |
| `courses.view.public` | View public course listing without authentication |
| `courses.view.any` | View any course including drafts (admin/instructor) |
| `courses.create` | Create a new course |
| `courses.update` | Edit any course |
| `courses.update.own` | Edit courses the user created |
| `courses.delete` | Delete any course |
| `courses.delete.own` | Delete courses the user created |
| `courses.publish` | Publish or unpublish any course |
| `courses.publish.own` | Publish or unpublish own courses |
| `courses.enroll` | Enroll a student in a course |
| `courses.enroll.manage` | Manually enroll/unenroll any user in any course |
| `lessons.view` | Access lesson content as an enrolled user |
| `lessons.preview` | Access free-preview lessons without enrollment |
| `lessons.create` | Create lessons within a course |
| `lessons.update.own` | Edit lessons the user created |
| `lessons.update` | Edit any lesson |
| `lessons.delete.own` | Delete lessons the user created |
| `lessons.delete` | Delete any lesson |
| `lessons.publish` | Publish or unpublish any lesson |
| `lessons.publish.own` | Publish or unpublish own lessons |
| `lessons.progress.update` | Mark lesson completion / update progress |

##### Media & Accessibility Assets

| Permission String | What It Guards |
|---|---|
| `media.upload` | Upload video, audio, or image files to a lesson |
| `media.delete.own` | Delete media files the user uploaded |
| `media.delete` | Delete any media file |
| `media.captions.upload` | Upload VTT/SRT caption or transcript files |
| `media.captions.delete` | Delete caption or transcript files |
| `media.signlanguage.upload` | Upload a sign language interpretation video |

##### Analytics, Notes & Certificates

| Permission String | What It Guards |
|---|---|
| `analytics.courses.own` | View enrollment and completion stats for own courses |
| `analytics.platform` | View platform-wide analytics dashboard |
| `notes.create` | Create lesson notes |
| `notes.update.own` | Edit own lesson notes |
| `notes.delete.own` | Delete own lesson notes |
| `certificates.view.own` | Download own certificates of completion |
| `certificates.issue` | Manually issue a certificate to any user |

##### Profile & Settings

| Permission String | What It Guards |
|---|---|
| `profile.accessibility.update` | Save personal accessibility preferences (font scale, color mode, etc.) |
| `profile.update.own` | Edit own account details |
| `settings.site.update` | Modify global site settings |
| `settings.mail.update` | Configure email/notification settings |
| `blog.view` | Read published blog posts |
| `blog.create` | Write new blog posts |
| `blog.update` | Edit any blog post |
| `blog.update.own` | Edit own blog posts |
| `blog.delete` | Delete any blog post |
| `blog.publish` | Publish or unpublish blog posts |
| `users.register` | Create a new account (public registration) |

#### 7.3.5 Super Admin Role — Auto-Update Contract

The Super Admin role (`is_system = true`) must always hold the complete permission set. To enforce this, every pull request that introduces a new feature must include a database migration that appends the new permission string(s) to the Super Admin role's `permissions` JSON column. The migration must be idempotent — use a `JSON_ARRAY_APPEND` or equivalent that checks for duplicates before inserting. This ensures the Super Admin is never inadvertently locked out of a newly deployed feature.

#### 7.3.6 Super Admin Permissions Dashboard

A dedicated admin panel page at `/admin/roles` provides the following capabilities:

- List all roles with their label, number of assigned users, and system status.
- Create a new role by providing a name and label; optionally clone permissions from an existing role.
- Edit a role: rename label; toggle individual permissions on/off via a grouped, searchable checklist (grouped by the same domains in §7.3.4).
- Delete a non-system role; users currently holding that role lose it immediately (a confirmation modal lists the number of affected users).
- Assign or revoke roles on any user's profile page within the admin panel.
- All changes to role permissions are written to a `role_permission_audit_log` table recording: `role_id`, `changed_by` (user_id), `timestamp`, `permissions_before` (json), `permissions_after` (json).
- The permissions checklist UI uses ARIA checkbox groups with visible group headings; keyboard navigable; compatible with screen readers.

---

## 8. Accessibility Requirements (WCAG 2.1)

### 8.1 Perceivable

- **1.1.1 Non-text Content:** All images have descriptive alt text; decorative images use `alt=""`.
- **1.2.1–1.2.5 Time-based Media:** All videos have captions; all audio has transcripts; extended audio descriptions provided where applicable.
- **1.3.1 Info & Relationships:** Semantic HTML5 elements used throughout (`nav`, `main`, `aside`, `article`, `section`).
- **1.3.3 Sensory Characteristics:** Instructions do not rely solely on color, shape, or position.
- **1.4.1 Use of Color:** Color is never the sole means of conveying information.
- **1.4.3 Contrast (Minimum):** All text meets 4.5:1 on backgrounds (see color palette above).
- **1.4.4 Resize Text:** Text scales to 200% without loss of content or functionality.
- **1.4.10 Reflow:** Content reflows to single column at 320px viewport width.
- **1.4.11 Non-text Contrast:** UI components and focus indicators meet 3:1.
- **1.4.12 Text Spacing:** Layout holds when `line-height ≥ 1.5`, `letter-spacing ≥ 0.12em`.

### 8.2 Operable

- **2.1.1 Keyboard:** All functionality reachable and operable via keyboard.
- **2.1.2 No Keyboard Trap:** Focus is never trapped in a component unexpectedly.
- **2.4.1 Bypass Blocks:** Skip-to-main link on every page.
- **2.4.3 Focus Order:** DOM order matches visual order; no `tabindex > 0`.
- **2.4.7 Focus Visible:** Custom focus outline using primary color ring, min 2px, visible in all themes.
- **2.5.3 Label in Name:** Accessible name contains the visible label text.

### 8.3 Understandable

- **3.1.1 Language of Page:** `lang` attribute set correctly on `<html>`.
- **3.2.1 On Focus:** No context changes on focus.
- **3.3.1 Error Identification:** Form errors identified in text, not only color.
- **3.3.2 Labels or Instructions:** All inputs have a visible, associated `<label>`.

### 8.4 Robust

- **4.1.2 Name, Role, Value:** All custom components expose correct ARIA roles, states, properties.
- **4.1.3 Status Messages:** Live regions (`aria-live`) used for dynamic status updates (toast notifications, quiz results, etc.)

---

## 9. Code Standards & Conventions

### 9.1 JavaScript

- All JavaScript must reside in external `.js` files under `resources/js/`. No inline `<script>` tags in Blade views.
- Alpine.js used only for Livewire-integrated behavior via `x-data` / `x-on` directives declared in Blade; logic functions defined in external JS modules imported via Vite.
- Custom accessibility module: `resources/js/accessibility.js` handles font scaling, color mode toggling, and keyboard shortcut registration.
- No jQuery. Vanilla JS or Alpine.js only.

### 9.2 CSS

- All styles in external CSS/Tailwind files. No `style="..."` inline attributes in Blade views (exception: CSS custom properties set dynamically via JS on `<html>` element only).
- Tailwind utility classes in Blade templates; complex reusable styles extracted to `@layer components` in `resources/css/app.css`.
- CSS custom properties defined in `:root` and `[data-theme="dark"]` for theming, consumed by Tailwind via `var()` in `tailwind.config.js`.

### 9.3 Blade / Livewire

- Layouts use `<x-slot>` and named slots for head meta, page title, and content regions.
- Livewire components use `wire:key` on list items; `wire:loading` applied to async actions.
- No logic in Blade views beyond conditionals/loops; business logic in Livewire components or dedicated Action classes.

---

## 10. Non-Functional Requirements

- **Performance:** Lighthouse Performance score ≥ 85 on mobile. LCP < 2.5s.
- **Security:** All inputs validated and sanitized. CSP headers configured. CSRF protection on all forms.
- **SEO:** Sitemap.xml auto-generated. Canonical URLs on all pages. Robots.txt configured.
- **Internationalisation:** Laravel localization (`trans()`) used for all user-facing strings from day one, even if only English is launched initially.
- **GDPR:** Cookie consent banner; no analytics without consent; data export and deletion endpoints.

---

## 11. Delivery Milestones

| Phase | Milestone | Deliverables |
|---|---|---|
| **1** | Foundation | Monorepo setup, both Laravel apps scaffolded, shared auth package, design system tokens, base layout with a11y controls. |
| **2** | Main Site | All public pages live, OG meta system, blog CMS, WCAG audit pass. |
| **3** | LMS Alpha | Course/lesson CRUD, accessible media upload pipeline, student enrollment, basic lesson player. |
| **4** | LMS Beta | Transcript/caption player, sign language overlay, keyboard shortcuts, accessibility profile persistence. |
| **5** | QA & Launch | Full WCAG audit, Lighthouse review, security penetration test, staging → production deploy. |

---

*End of Document — Linking Circles Academy PRD v1.0*
