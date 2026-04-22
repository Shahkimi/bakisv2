# UI/UX Improvement Plan: `admin/members/edit` — bakisv2

> **Branch:** `new_user_role` | **File:** `resources/views/admin/members/edit.blade.php`  
> **Date:** 22 April 2026  
> **Purpose:** Professional, modern, interactive UI/UX redesign plan

---

## 1. Current State Analysis

### What Exists (Good Foundation)
- Two-column layout: sticky left sidebar + right accordion form
- Alpine.js accordion for 4 sections (Keanggotaan, Peribadi, Alamat, Pembayaran)
- Select2 for Jabatan/Jawatan dropdowns
- SweetAlert2 for form submission feedback
- Dark mode support via Tailwind CSS
- Postcode autocomplete via AJAX
- Per-section Save buttons + global Kemaskini button

### Identified UX Problems

| # | Problem | Impact |
|---|---------|--------|
| 1 | **Accordion only opens ONE section at a time** — user cannot see multiple sections simultaneously | High |
| 2 | **Duplicate Save buttons** inside each accordion section AND a global button at bottom — confusing | High |
| 3 | **No unsaved changes indicator** — user may navigate away without saving | High |
| 4 | **No progress/completion indicator** — user doesn't know which sections are complete | Medium |
| 5 | **Left sidebar has no avatar/photo** — feels impersonal for a member profile | Medium |
| 6 | **Section headers use full uppercase colored text** — inconsistent when active vs inactive | Medium |
| 7 | **Gender field uses plain radio buttons** — not visually engaging | Low |
| 8 | **No inline field validation feedback** — errors only shown after submit | High |
| 9 | **Payment section mixed inside the edit form `<form>` tag** — read-only table inside a POST form is semantically wrong | Medium |
| 10 | **No breadcrumb / back navigation** at top of page | Low |
| 11 | **No keyboard shortcut** (Ctrl+S) for power-admin users | Low |
| 12 | **Mobile: sidebar stacks on top** of form — too much vertical scroll before reaching editable fields | Medium |

---

## 2. Design Principles Applied

1. **Progressive Disclosure** — show all section tabs simultaneously (tabbed), reveal content on click
2. **Contextual Feedback** — inline validation, unsaved dot indicator, autosave state
3. **Visual Hierarchy** — member avatar card prominent, action buttons consistent placement
4. **Accessibility** — ARIA labels, focus ring, keyboard nav
5. **Consistency** — one save action per logical group, single CTA color
6. **Responsive First** — mobile collapses sidebar into a top summary strip

---

## 3. Proposed Layout Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│ Breadcrumb: Admin > Ahli > Edit Ahli                            │
├───────────────────────┬─────────────────────────────────────────┤
│  LEFT SIDEBAR         │  RIGHT CONTENT                          │
│  (sticky, w-80)       │                                         │
│                       │  ┌─ Tab Navigation ─────────────────┐  │
│  ┌─ Avatar Card ────┐ │  │ [👤 Keanggotaan] [📋 Peribadi]   │  │
│  │  Initials Circle │ │  │ [🏠 Alamat]      [💳 Pembayaran] │  │
│  │  Nama Penuh      │ │  └──────────────────────────────────┘  │
│  │  No. KP badge    │ │                                         │
│  │  Status badge    │ │  ┌─ Active Tab Panel ───────────────┐  │
│  │  No. Ahli badge  │ │  │  Form fields with inline         │  │
│  └──────────────────┘ │  │  validation                      │  │
│                       │  │                                   │  │
│  ┌─ Info Cards ─────┐ │  │  [Batal]        [Kemaskini →]    │  │
│  │  Jabatan         │ │  └──────────────────────────────────┘  │
│  │  Unit/Jawatan    │ │                                         │
│  │  Tarikh Daftar   │ │                                         │
│  └──────────────────┘ │                                         │
│                       │                                         │
│  ┌─ Completion ─────┐ │                                         │
│  │  ● Keanggotaan ✓ │ │                                         │
│  │  ○ Peribadi  ✓   │ │                                         │
│  │  ○ Alamat    ✗   │ │                                         │
│  └──────────────────┘ │                                         │
└───────────────────────┴─────────────────────────────────────────┘
```

---

## 4. UI/UX Improvements (Detailed)

### 4.1 Replace Accordion → Horizontal Tab Navigation

**Why:** Tabs allow all sections to be equally discoverable at a glance. Accordion forces user to hunt through collapsed panels.

**Implementation:**
```html
<!-- Alpine.js tab state -->
<div x-data="{ activeTab: 'membership', dirty: false, saving: false }">

  <!-- Tab Bar -->
  <div class="flex border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
    <button @click="activeTab = 'membership'"
      :class="activeTab === 'membership' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
      class="flex items-center gap-2 px-5 py-3 text-sm font-medium whitespace-nowrap transition">
      <!-- Icon + Label + Completion dot -->
      <span class="w-2 h-2 rounded-full bg-green-500"></span>
      Keanggotaan
    </button>
    <!-- repeat for Peribadi, Alamat -->
  </div>

  <!-- Tab Panels -->
  <div x-show="activeTab === 'membership'" ...>
    <!-- membership fields -->
  </div>
</div>
```

**Change Required:** Remove `openSection` accordion logic; replace with `activeTab` state. Keep `x-show` per panel.

---

### 4.2 Member Avatar with Initials

**Why:** Humanizes the record. Industry standard (e.g., HubSpot, Salesforce CRM profiles).

**Implementation:**
```html
<!-- Generate initials from name -->
@php
  $initials = collect(explode(' ', $member->nama))
    ->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
  $avatarColors = ['bg-blue-600', 'bg-indigo-600', 'bg-emerald-600', 'bg-purple-600'];
  $avatarBg = $avatarColors[crc32($member->nama) % 4];
@endphp

<div class="w-20 h-20 rounded-full {{ $avatarBg }} flex items-center justify-center mx-auto mb-3">
  <span class="text-2xl font-bold text-white tracking-wide">{{ $initials }}</span>
</div>
```

**Change Required:** Add above `<h2>` in the member name card in left sidebar.

---

### 4.3 Unsaved Changes Indicator

**Why:** Prevents data loss. Critical for admin forms.

**Implementation (Alpine.js):**
```html
<div x-data="{ dirty: false }">
  <!-- Sticky top banner when dirty -->
  <div x-show="dirty" x-transition
    class="sticky top-0 z-50 bg-amber-50 dark:bg-amber-900/30 border-b border-amber-200 px-4 py-2 flex items-center justify-between">
    <span class="text-xs font-medium text-amber-700 dark:text-amber-300 flex items-center gap-2">
      <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
      Terdapat perubahan yang belum disimpan
    </span>
    <button type="submit" form="member-edit-form" class="text-xs font-semibold text-amber-700 underline">
      Simpan sekarang
    </button>
  </div>

  <form @change="dirty = true" @submit="dirty = false" ...>
```

**Change Required:** Add `@change="dirty = true"` to `<form>` element. Add sticky amber banner to top of right content area. Add `beforeunload` guard in JS.

---

### 4.4 Inline Field Validation

**Why:** Reduces form submission errors. Users know immediately what's wrong.

**Implementation (Alpine.js + Blade):**
```html
<!-- IC Number with live format validation -->
<div x-data="{ val: '{{ old('no_kp', $member->no_kp) }}', error: '' }">
  <input type="text"
    x-model="val"
    @input="error = val.length > 0 && !/^\d{12}$/.test(val) ? 'Format tidak sah. 12 digit diperlukan.' : ''"
    :class="error ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
    class="block w-full px-4 py-3 text-sm rounded-lg transition">
  <p x-show="error" x-text="error" class="mt-1 text-xs text-red-500"></p>
  <!-- Server-side error fallback -->
  @error('no_kp')
    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
  @enderror
</div>
```

**Change Required:** Wrap `no_kp`, `no_hp`, `poskod`, `email`, `tarikh_daftar` fields in Alpine `x-data` for inline validation. Add `@error()` Blade fallbacks for all fields.

---

### 4.5 Gender Toggle Button (Replace Radio Buttons)

**Why:** Radio buttons are visually plain. Toggle buttons are more modern and touch-friendly.

**Implementation:**
```html
<div x-data="{ gender: '{{ old('jantina', $member->jantina) }}' }">
  <input type="hidden" name="jantina" :value="gender">
  <div class="flex rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden w-fit">
    <button type="button"
      @click="gender = 'L'"
      :class="gender === 'L' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50'"
      class="px-6 py-2.5 text-sm font-medium transition flex items-center gap-2">
      <svg ...> <!-- male icon --> </svg>
      Lelaki
    </button>
    <button type="button"
      @click="gender = 'P'"
      :class="gender === 'P' ? 'bg-pink-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50'"
      class="px-6 py-2.5 text-sm font-medium transition flex items-center gap-2">
      <svg ...> <!-- female icon --> </svg>
      Perempuan
    </button>
  </div>
</div>
```

**Change Required:** Replace `<input type="radio">` block for `jantina` with the toggle button component above.

---

### 4.6 Section Completion Tracker (Sidebar)

**Why:** Gives admin visual feedback on which sections are filled vs missing required fields.

**Implementation:**
```html
<!-- Add to sidebar, below info cards -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
  <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-3">Kelengkapan Data</p>
  @php
    $sections = [
      'Keanggotaan' => $member->member_status_id && $member->jabatan_id && $member->jawatan_id,
      'Peribadi'    => $member->nama && $member->no_kp && $member->no_hp,
      'Alamat'      => $member->alamat1 && $member->poskod && $member->negeri,
      'Pembayaran'  => $member->payments->isNotEmpty(),
    ];
  @endphp
  <div class="space-y-2">
    @foreach($sections as $label => $complete)
    <div class="flex items-center gap-2.5">
      @if($complete)
        <span class="w-5 h-5 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
          <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
          </svg>
        </span>
      @else
        <span class="w-5 h-5 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
          <span class="w-2 h-2 rounded-full bg-red-400"></span>
        </span>
      @endif
      <span class="text-xs {{ $complete ? 'text-gray-600 dark:text-gray-300' : 'text-red-500 font-medium' }}">
        {{ $label }}
      </span>
    </div>
    @endforeach
  </div>
</div>
```

**Change Required:** Add this blade snippet at the bottom of the sticky sidebar, after the Tarikh Daftar card.

---

### 4.7 Move Payment Section Outside `<form>` Tag

**Why:** Payment history is read-only. Keeping it inside a POST form is semantically incorrect and could cause unintended POST data.

**Change Required:**
```html
<!-- BEFORE: Payment section is inside <form id="member-edit-form"> -->

<!-- AFTER: Close the form before payment section -->
            </div><!-- end address section -->
          </form><!-- ← close form HERE -->

          <!-- Action Buttons (inside form via form= attribute) -->
          <div class="flex flex-wrap gap-3 justify-end pt-2">
            <a href="{{ route('admin.members.index') }}" ...>Batal</a>
            <button type="submit" form="member-edit-form" ...>Kemaskini</button>
          </div>

          <!-- Payment section OUTSIDE the form -->
          <div class="bg-white dark:bg-gray-800 rounded-xl ...">
            <!-- Payment history table -->
          </div>
```

---

### 4.8 Breadcrumb Navigation

**Why:** Helps admin understand context and navigate quickly.

**Implementation (add above the main container div):**
```html
<nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 px-4 sm:px-6 lg:px-8 pt-4 pb-2" aria-label="Breadcrumb">
  <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-200 transition">Dashboard</a>
  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
  </svg>
  <a href="{{ route('admin.members.index') }}" class="hover:text-gray-700 dark:hover:text-gray-200 transition">Ahli</a>
  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
  </svg>
  <span class="text-gray-900 dark:text-white font-medium truncate max-w-xs">{{ $member->nama }}</span>
</nav>
```

**Change Required:** Add above `<div class="min-h-[calc(100vh-4rem)]...">`.

---

### 4.9 Keyboard Shortcut (Ctrl+S / Cmd+S)

**Why:** Power admin users expect keyboard shortcuts. Standard in professional tools.

**Implementation (add to `@push('scripts')`):**
```javascript
// Keyboard shortcut: Ctrl+S / Cmd+S to save
document.addEventListener('keydown', function(e) {
  if ((e.ctrlKey || e.metaKey) && e.key === 's') {
    e.preventDefault();
    document.getElementById('member-edit-form').dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
  }
});
```

---

### 4.10 Mobile: Top Summary Strip

**Why:** On mobile, the sticky sidebar stacks above the form causing excessive scroll.

**Change Required:** Add `md:hidden` summary bar that replaces sidebar on mobile:
```html
<!-- Mobile-only top strip -->
<div class="md:hidden bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-3 flex items-center gap-3 sticky top-0 z-30">
  <div class="w-10 h-10 rounded-full {{ $avatarBg }} flex items-center justify-center flex-shrink-0">
    <span class="text-sm font-bold text-white">{{ $initials }}</span>
  </div>
  <div class="flex-1 min-w-0">
    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $member->nama }}</p>
    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $member->no_kp }}</p>
  </div>
  @if($member->memberStatus)
    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
      {{ $member->memberStatus->name }}
    </span>
  @endif
</div>
```

Add `hidden md:flex` to the existing left sidebar `<div>`.

---

## 5. Full Implementation Plan

### Phase 1 — Structural & Semantic Fixes (1–2 hours)
| Task | File | Priority |
|------|------|----------|
| Move payment section outside `<form>` tag | `edit.blade.php` | 🔴 High |
| Add breadcrumb navigation | `edit.blade.php` | 🟡 Medium |
| Fix duplicate Save buttons — remove per-section buttons, keep only bottom Kemaskini | `edit.blade.php` | 🔴 High |
| Add `form="member-edit-form"` to bottom action buttons | `edit.blade.php` | 🔴 High |

### Phase 2 — Layout & Navigation Redesign (2–3 hours)
| Task | File | Priority |
|------|------|----------|
| Replace accordion with horizontal tab navigation | `edit.blade.php` | 🔴 High |
| Add member avatar with initials (PHP + Blade) | `edit.blade.php` | 🟡 Medium |
| Add section completion tracker to sidebar | `edit.blade.php` | 🟡 Medium |
| Add mobile top summary strip | `edit.blade.php` | 🟡 Medium |

### Phase 3 — Interactivity & Feedback (2–3 hours)
| Task | File | Priority |
|------|------|----------|
| Add unsaved changes indicator (Alpine.js `dirty` state) | `edit.blade.php` | 🔴 High |
| Add `beforeunload` JS guard | `edit.blade.php` | 🔴 High |
| Replace gender radio buttons with toggle buttons | `edit.blade.php` | 🟢 Low |
| Add inline field validation (no_kp, no_hp, poskod, email) | `edit.blade.php` | 🔴 High |
| Add Ctrl+S keyboard shortcut | `edit.blade.php` | 🟢 Low |

### Phase 4 — Polish & Accessibility (1 hour)
| Task | File | Priority |
|------|------|----------|
| Add ARIA labels to all form groups | `edit.blade.php` | 🟡 Medium |
| Ensure all interactive elements have visible focus rings | `edit.blade.php` + custom CSS | 🟡 Medium |
| Ensure tab order is logical (sidebar → tabs → form → action) | `edit.blade.php` | 🟡 Medium |
| Test dark mode for all new components | `edit.blade.php` | 🟡 Medium |

---

## 6. Files to Change

| File | Type of Change |
|------|---------------|
| `resources/views/admin/members/edit.blade.php` | Major — all UI/UX changes (primary file) |
| `resources/views/layouts/app.blade.php` | Minor — verify Alpine.js x-collapse plugin is loaded |
| `routes/web.php` | No change needed |
| `app/Http/Controllers/Admin/MemberController.php` | No change needed (existing JSON response already correct) |
| `public/css/app.css` or `resources/css/app.css` | Optional — custom tab indicator animation if needed |

---

## 7. Dependencies (Already in Use — No New Libraries Needed)

| Library | Version | Current Usage | New Usage |
|---------|---------|--------------|-----------|
| Alpine.js | CDN | Accordion state | Tab state, dirty tracking, toggle buttons |
| Tailwind CSS | CDN/build | All styling | No change |
| Select2 | 4.1.0-rc.0 | Jabatan/Jawatan | No change |
| SweetAlert2 | v11 | Form submit feedback | No change |
| jQuery | 3.7.1 | AJAX, Select2 | Keyboard shortcut, beforeunload |

> ✅ **Zero new dependencies required.** All improvements use existing stack.

---

## 8. Quick Reference: Alpine.js State Object

Replace the current `x-data="{ openSection: 'membership' }"` with this enhanced state:

```javascript
x-data="{
  activeTab: 'membership',
  dirty: false,
  saving: false,
  tabs: [
    { id: 'membership', label: 'Keanggotaan', color: 'blue',    icon: 'user-group' },
    { id: 'personal',   label: 'Peribadi',    color: 'indigo',  icon: 'identification' },
    { id: 'address',    label: 'Alamat',       color: 'emerald', icon: 'home' },
  ],
  switchTab(tab) {
    this.activeTab = tab;
    this.$nextTick(() => {
      // Re-trigger Select2 width recalculate on tab switch
      if (window.$ && $.fn.select2) {
        $('#jabatan_id, #jawatan_id').trigger('change.select2');
      }
    });
  }
}"
```

---

## 9. Before / After Summary

| Aspect | Before | After |
|--------|--------|-------|
| Navigation | Single-open accordion | Multi-section tabs |
| Member identity | Name + badges only | Avatar initials + name + badges |
| Save buttons | 3 buttons (per-section + global) | 1 global Kemaskini button |
| Unsaved changes | No indicator | Amber sticky banner + JS guard |
| Field validation | Server-side only | Inline + server-side fallback |
| Gender input | Plain radio buttons | Styled toggle button group |
| Data completeness | Not shown | Sidebar completion tracker |
| Mobile experience | Sidebar stacks (long scroll) | Top summary strip |
| Breadcrumb | None | Dashboard > Ahli > Member name |
| Keyboard shortcut | None | Ctrl+S / Cmd+S to save |
| Payment in form | Inside `<form>` (wrong) | Outside `<form>` (correct) |
| New dependencies | — | **None** |

---

*Generated for: [Shahkimi/bakisv2](https://github.com/Shahkimi/bakisv2) — branch `new_user_role`*
