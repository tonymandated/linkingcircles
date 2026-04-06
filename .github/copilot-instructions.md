# GitHub Copilot Technical Rules & Skills Reference
## Linking Circles Academy — linkingcirclesacademy.com + lms.linkingcirclesacademy.com

**Save as:** `.github/copilot-instructions.md`  
**Version:** 1.0  
**Date:** April 2026

---

This document defines the coding conventions, architectural decisions, and skill descriptions that GitHub Copilot (and any AI coding assistant) must follow when contributing to this project. Saving it as `.github/copilot-instructions.md` in the repository root causes VS Code to auto-load it as Copilot context on every file.

---

## Table of Contents

1. [Project Context](#1-project-context)
2. [PHP & Laravel Rules](#2-php--laravel-rules)
3. [Livewire 3 Rules](#3-livewire-3-rules)
4. [Tailwind CSS & Styling Rules](#4-tailwind-css--styling-rules)
5. [JavaScript Rules](#5-javascript-rules)
6. [Blade Template Rules](#6-blade-template-rules)
7. [Roles & Permissions Rules](#7-roles--permissions-rules)
8. [LMS-Specific Rules](#8-lms-specific-rules)
9. [Accessibility Rules](#9-accessibility-rules-applied-to-every-file)
10. [Testing Rules](#10-testing-rules)
11. [Security Rules](#11-security-rules)
12. [Pull Request Checklist](#12-pull-request-checklist)

---

## 1. Project Context

- **Project:** Linking Circles Academy — accessible education platform.
- **Stack:** PHP 8.2+, Laravel 11, Livewire 3, Alpine.js, Tailwind CSS 3, Vite.
- **Structure:** Monorepo with `apps/main` (main site) and `apps/lms` (LMS subdomain).
- **Shared code:** `packages/shared` — Composer path repository for the `User` model, auth, and shared services.
- **Primary design constraint:** WCAG 2.1 Level AA accessibility throughout.
- **Audience:** People with hearing and/or visual disabilities.

---

## 2. PHP & Laravel Rules

### 2.1 General PHP

- **PHP version: 8.3+.** Use typed properties, constructor promotion, enum types, readonly properties, and named arguments where they improve clarity.
- **Strict types:** always add `declare(strict_types=1);` at the top of every PHP file.
- **Type hints:** use union types and nullable types correctly; never use `mixed` unless unavoidable.
- Never suppress errors with `@`. Fix the root cause.
- **Docblocks:** use PHPDoc only when types cannot be expressed in native PHP. Do not add redundant `@param` / `@return` that mirror the signature.

### 2.2 Laravel Conventions

- Follow Laravel naming conventions: `PascalCase` controllers/models, `snake_case` database columns, `camelCase` Eloquent accessors/mutators.
- **Controllers are thin:** no business logic inside controllers. Delegate to Action classes (`app/Actions/`) or Service classes.
- Use **Laravel Form Requests** for validation; never validate in controllers directly.
- Use **Laravel Policies** for authorization. Always authorize against a **permission string** (e.g., `$this->authorize('courses.create')`), never against a role name. Policies resolve permissions via the shared `HasPermissions` trait on the `User` model.
- **Eloquent:** use query scopes for reusable query logic. Avoid raw `DB::statement()` unless absolutely necessary.
- **Events & Listeners** for side effects (sending emails, triggering jobs). Do not couple side effects to controller code.
- Use the `config()` helper for all configuration values; never hardcode URLs, credentials, or environment-specific values.
- **Route model binding:** use it always; never manually call `Model::find($id)` in controllers.

### 2.3 Migrations & Database

- Each migration does one thing. Never combine unrelated schema changes in one migration.
- Use `$table->foreignId('user_id')->constrained()->cascadeOnDelete()` for foreign keys.
- Add database comments on columns that store non-obvious data.
- Always add indexes on columns used in `WHERE`, `ORDER BY`, or `JOIN` clauses.

---

## 3. Livewire 3 Rules

- Livewire components live in `app/Livewire/` and `resources/views/livewire/`.
- Component class properties bound to user input must be typed (e.g., `public string $email = ''`).
- Use the `#[Validate]` attribute on component properties instead of `$this->validate()` calls.
- Use `wire:key` on list items to aid Livewire's DOM diffing.
- Use `wire:loading.attr="disabled"` and `wire:loading.class` on interactive elements during async operations.
- Never put database queries or heavy computation in `mount()` — use computed properties instead.
- Use `$this->dispatch()` for cross-component events, not JavaScript custom events.
- **Pagination:** use the `WithPagination` trait; always reset page on filter changes with `$this->resetPage()`.

---

## 4. Tailwind CSS & Styling Rules

- Use Tailwind utility classes in Blade templates. **Do NOT** use arbitrary values like `text-[#2B4DAE]` — use the custom tokens defined in `tailwind.config.js` instead (e.g., `text-primary`).
- All custom colors, spacing, and typography tokens live in `tailwind.config.js` under `theme.extend`. Copy from the PRD color palette.
- Complex reusable component styles go into `@layer components` in `resources/css/app.css` — not repeated inline in every template.
- Never write `style="..."` inline attributes in Blade files. The only exception is CSS custom property values set by JavaScript on the `<html>` element.
- **Dark mode:** use the class strategy (`darkMode: 'class'` in `tailwind.config.js`). Dark mode variants use `dark:` Tailwind classes.
- **High contrast mode:** use a `data-theme="high-contrast"` CSS selector — not a separate Tailwind variant.
- **Responsive:** mobile-first. Start with base classes; add `sm:`, `md:`, `lg:` breakpoints as needed.

---

## 5. JavaScript Rules

- **No inline JavaScript in Blade views.** All JS lives in `resources/js/` (or the LMS equivalent).
- No jQuery. Use vanilla JS or Alpine.js only.
- **Alpine.js:** `x-data` objects with substantial logic should reference functions defined in external JS modules — do not write multi-line arrow functions inside Blade attributes.
- **ES modules:** use `import`/`export`. Vite handles bundling.
- The accessibility module (`resources/js/accessibility.js`) owns: font scale state, color mode state, keyboard shortcut registration. No other file should duplicate this logic.
- Use `data` attributes (`data-action="..."`) as JS hooks; never use CSS class names as JS selectors.
- **DOM manipulation:** prefer `dataset`, `classList`, and `setAttribute` over `innerHTML`.
- **Event listeners:** always use `addEventListener` with named functions (not anonymous) so they can be removed cleanly.

---

## 6. Blade Template Rules

### 6.1 Layout System

- **Main layout:** `resources/views/layouts/app.blade.php`. It yields: `title`, `description`, `og_title`, `og_description`, `og_image`, `og_url`, `og_type`, `canonical`, `head_extra`, `scripts_extra`.
- Every page extends this layout using `@extends('layouts.app')` and provides `@section('title')` etc.
- The `<html>` element always has: `lang="{{ app()->getLocale() }}"`, `data-theme` (managed by JS), and a `font-scale` CSS custom property.
- The `<main>` element always has `id="main-content"` and the appropriate ARIA landmark role.

### 6.2 Accessibility in Blade

- **Skip link** is the first focusable element on every page:
  ```html
  <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 ...">
    Skip to main content
  </a>
  ```
- All `<img>` tags must have `alt="..."`. Decorative images use `alt=""` and `role="presentation"`.
- Icon-only buttons must have `aria-label="..."`.
- Use `<button>` for actions, `<a>` for navigation. Never use `<div>` or `<span>` as interactive elements.
- Form inputs always have an associated `<label for="...">` — never use `placeholder` as a substitute for a label.
- Error messages are associated with their input via `aria-describedby="error-id"`.
- Use `role="alert"` or `aria-live="polite"` for dynamically injected status messages.
- Tables always have `<caption>` and `<th scope="...">` for header cells.
- Modal dialogs use `role="dialog"`, `aria-modal="true"`, `aria-labelledby`, trap focus within the dialog, and return focus to the trigger element on close.

---

## 7. Roles & Permissions Rules

> ⚠️ **This section is mandatory reading before implementing any feature that touches authorization. Violations are blocking PR review failures.**

### 7.1 Core Principles

- **NEVER check role names in code.** No comparisons against strings like `"super_admin"`, `"instructor"`, or `"student"` anywhere in controllers, Livewire components, Blade views, policies, or middleware.
- **ALWAYS authorize against a permission string** using one of the approved mechanisms:
    - `$this->authorize('permission.string')`
    - `Gate::allows('permission.string')`
    - `@can('permission.string')` in Blade
    - `route()->middleware('permission:permission.string')`
- **Roles are database rows, not code constants.** No `Role` enum, no hard-coded role IDs, no magic numbers. Retrieve roles via the `Role` Eloquent model only.
- The `permissions` JSON column on the `roles` table is the single source of truth. There are no hard-coded permission lists anywhere in application code.

### 7.2 Database & Models

- The `roles` table has columns: `id`, `name` (string), `label` (string), `permissions` (json), `is_system` (bool), `timestamps`.
- The `role_user` pivot table joins `users` and `roles`. A user can hold multiple roles.
- The `User` model (in `packages/shared/`) uses the `HasPermissions` trait which provides:
    - `can(string $permission): bool` — use for all authorization checks
    - `hasRole(string $name): bool` — use **sparingly**, only for UI decoration, never for authorization
    - `allPermissions(): Collection`
- **Permission resolution:** the resolved permission set is the **union** of `permissions` arrays from all roles assigned to the user. This is computed once per request and cached in the session.
- **Cache invalidation:** whenever `roles.permissions` is updated or a user's role assignments change, call `$user->flushPermissionCache()` to clear the session/Redis cache for all affected users.

### 7.3 Gates & Service Provider

- All gates are registered in `app/Providers/PermissionServiceProvider.php` (located in `packages/shared/`).
- On `boot()`, the provider loads all distinct permission strings from the `permissions` JSON columns of all roles, then calls `Gate::define()` for each one. This makes every permission available to `@can`, `authorize()`, and `Gate::allows()` automatically.
- **Do not** call `Gate::define()` for a permission anywhere else in the codebase — only in `PermissionServiceProvider`.
- Gates use the `before()` hook: if the user has the special meta-permission `superadmin.bypass`, all gate checks return `true`. This meta-permission is only ever stored on the Super Admin seed role.

### 7.4 Adding a New Permission — Required Workflow

Every time a new feature is built, the developer **MUST** follow this exact sequence:

1. Choose a namespaced permission string following the pattern `domain.action` or `domain.action.scope` (e.g., `quizzes.create`, `quizzes.update.own`). Add it to the Permission Registry section of the PRD and to `config/permissions.php`.
2. Use the permission in the feature code via the standard authorization mechanisms. **Do not ship the feature without the authorization check.**
3. Write a database migration that appends the new permission string to the Super Admin role's `permissions` JSON column. Use a safe, idempotent JSON update (check the array does not already contain the value before appending).
4. Update the relevant seed role(s) in `database/seeders/RoleSeeder.php` so fresh installs also have the permission in the correct default roles.
5. Write a unit test in `tests/Unit/Permissions/` that asserts: (a) a user with the permission can perform the action, (b) a user without the permission receives a 403, (c) the Super Admin always has the permission.

> **Skipping any of these steps is a blocking PR review failure.**

### 7.5 Blade Authorization

- Use `@can('permission.string')` / `@cannot` to conditionally render UI elements. This hides elements visually but does **NOT** replace server-side authorization — always also authorize in the controller or Livewire component.
- **Never use** `@if(auth()->user()->hasRole('instructor'))` or similar role-name checks in Blade. Use `@can` only.
- The admin permissions dashboard UI (roles list, permission checkboxes) must itself be guarded by `@can('roles.view')`.

### 7.6 Livewire Components

- In Livewire component methods that perform write operations, call `$this->authorize('permission.string')` as the **first line** of the method, before any validation or business logic.
- Computed properties that return sensitive data must check `Gate::allows('permission.string')` before returning the data.
- Do not rely solely on `wire:poll`, `wire:click` guards, or Blade `@can` to secure data — the Livewire PHP method must re-authorize on every invocation.

### 7.7 Middleware

- Route groups requiring a specific permission use:
  ```php
  Route::middleware(['auth', 'permission:permission.string'])
  ```
- The `CheckPermission` middleware is registered in the HTTP kernel as `'permission'` and accepts a single permission string argument.
- **Never use** the `'role'` middleware (Spatie-style) or any role-name-based middleware. Only `'permission'` middleware is permitted.

### 7.8 Super Admin Dashboard — Roles UI Rules

- The roles management UI lives at `/admin/roles` and is accessible only to users with the `roles.view` permission.
- The permission checklist for editing a role is loaded from `config/permissions.php` — a nested associative array of the form:
  ```php
  [
      'Group Label' => [
          'permission.string' => 'Human readable description',
          // ...
      ],
  ]
  ```
  Updating this file is **required** whenever a new permission is added. Copilot must never hardcode the permission list in a controller.
- When a role's permissions are saved, write a row to the `role_permission_audit_log` table with columns: `role_id`, `changed_by`, `permissions_before` (json), `permissions_after` (json), `created_at`.
- The Super Admin role's permission checkboxes must all be checked **and disabled** (non-interactive) in the UI — the system must not allow the Super Admin role to lose any permission through the dashboard.

---

## 8. LMS-Specific Rules

- The LMS application is entirely in `apps/lms/`. Do not import or use LMS-specific code in `apps/main/`, and vice versa.
- Shared code (`User`, auth, notifications) comes exclusively from `packages/shared/`.
- **Media upload pipeline:** videos, audio, and images must have associated accessibility metadata before a lesson can be published. Enforce this at the application layer (Livewire validation + DB constraint where possible).
- Caption/transcript files (`.vtt`, `.srt`) are stored in a dedicated storage disk (`captions`) separate from media files.
- **Video player:** use a custom accessible player built on the HTML5 `<video>` element with a fully keyboard-navigable custom control bar. Do not use a third-party player that lacks WCAG compliance.
- Keyboard shortcuts for the lesson player are defined in `resources/js/player-shortcuts.js` and documented in a help overlay (`?` key toggles it).
- **Sign language overlay video:** rendered as an absolutely positioned `<video>` in a resizable/draggable container; its default position must not obscure subtitles.

---

## 9. Accessibility Rules (Applied to Every File)

- Every interactive element is reachable and operable by keyboard alone.
- Every interactive element has a visible focus outline. Use the project's focus ring utility class defined in `@layer utilities` in `app.css`.
- **Color contrast:** do not introduce any color combination that fails WCAG AA (4.5:1 for text, 3:1 for UI components). Consult the PRD color palette.
- Do not use `tabindex` values greater than `0`.
- **ARIA:** use ARIA attributes only when native HTML semantics are insufficient. Prefer `<nav>`, `<main>`, `<aside>`, `<article>`, `<section>`, `<button>`, `<a>` over `role="navigation"` etc.
- When adding a Livewire component that updates content dynamically, include an `aria-live` region to announce the update to screen readers.
- Images generated or selected by the application (e.g., course thumbnails) must have their `alt` text sourced from the database, never hardcoded.

---

## 10. Testing Rules

- Use **PestPHP** for all tests. No PHPUnit test classes.
- Feature tests cover all HTTP endpoints (happy path + auth + validation failures).
- Unit tests cover Action and Service classes.
- Browser tests (Laravel Dusk) cover critical user journeys: registration, course enrollment, lesson completion.
- **Accessibility tests:** each Livewire component has an axe-core check in its browser test. Any new page must pass an automated axe scan before PR merge.
- **Factory states:** create a Factory for every Eloquent model; use states for common variants (e.g., `CourseFactory::published()`, `UserFactory::instructor()`).
- **Permission tests:** every new permission must have a dedicated test in `tests/Unit/Permissions/` asserting:
    - (a) an authorized user can perform the action
    - (b) an unauthorized user receives a `403`
    - (c) the Super Admin always has access

---

## 11. Security Rules

- Always use Blade's `{{ }}` escaping. Use `{!! !!}` only for deliberately trusted HTML and add a comment explaining why.
- Never store sensitive data (passwords, tokens) anywhere except the designated Laravel hashed/encrypted fields.
- All file uploads go through a validation pipeline: MIME type check, file size limit, virus scan hook.
- Use Laravel's signed URLs for any publicly shareable resource links.
- Rate limit all public-facing form submissions using Laravel's `RateLimiter`.
- CORS policy is configured in `config/cors.php` — do not add custom CORS headers manually.

---

## 12. Pull Request Checklist

Every PR must satisfy **all** of the following before merge:

### General

- [ ] No inline JS in Blade files
- [ ] No inline `style` attributes in Blade files
- [ ] All new images have `alt` text sourced from data, not hardcoded
- [ ] All new form inputs have an associated `<label>` element
- [ ] All new interactive elements are keyboard accessible with a visible focus indicator
- [ ] New Livewire components use `wire:key` on list items
- [ ] axe-core accessibility scan passes on any new page or component
- [ ] Feature tests written for all new routes and actions
- [ ] No hardcoded colors — Tailwind custom tokens only
- [ ] Dark mode and high-contrast mode manually verified

### New Feature — Permissions (all five required)

- [ ] Permission string added to the PRD Permission Registry **and** `config/permissions.php`
- [ ] Authorization check (`$this->authorize` / `@can` / middleware) present in **all** entry points for the feature
- [ ] Database migration appends the new permission to the Super Admin role (idempotent)
- [ ] `RoleSeeder` updated so a fresh install seeds the permission correctly
- [ ] Permission unit test written covering: authorized, unauthorized, and Super Admin cases

### Final Check

- [ ] No role-name string comparisons anywhere in the changed files

---
