# 🗂️ Implementation Plan — Admin Member Create Redesign
**Repository:** Shahkimi/bakisv2 | **Branch:** new_user_role  
**Date:** 2026-04-22

---

## 📋 Overview

Redesign `admin/members/create` multi-step form (Form 1–4) with:
- **Auto-aktif** Status Ahli (default = Aktif) on create
- **Auto-generate** No. Ahli (system-generated, read-only with preview)
- **Auto-fill** Tarikh Daftar with today's timestamp (read-only, editable if needed)
- Modern, professional, interactive UI/UX using Alpine.js + Tailwind CSS

---

## 🎯 Business Rules

| Field | Behaviour on Create |
|---|---|
| `member_status_id` | **Always defaults to "Aktif"** — shown as locked badge, not editable dropdown |
| `no_ahli` | **Auto-generated preview** shown as `AHL-XXXXX` (read-only), system finalises on save |
| `tarikh_daftar` | **Pre-filled with `now()` timestamp** (date), admin can override if needed |

---

## 🗂️ Files to Change

### Views (Blade)
| File | Change |
|---|---|
| `resources/views/admin/members/partials/form1.blade.php` | Lock status to Aktif; show auto-generate preview for No. Ahli; datetime badge for Tarikh Daftar |
| `resources/views/admin/members/partials/form2.blade.php` | Full redesign — floating labels, avatar upload preview, IC mask, grouped layout |
| `resources/views/admin/members/partials/form3.blade.php` | Postcode API autocomplete, state dropdown, modern card layout |
| `resources/views/admin/members/partials/form4.blade.php` | Payment section toggle (show/hide), conditional required, receipt upload preview |
| `resources/views/admin/members/partials/step-indicator.blade.php` | Progress bar + step label improvements |

### Backend
| File | Change |
|---|---|
| `app/Services/MemberService.php` | `createMemberByAdmin()` — auto-resolve Aktif status, auto-generate `no_ahli` at create (not only on approve) |
| `app/Http/Requests/Admin/StoreMemberRequest.php` | Make `member_status_id` optional (auto-set backend), add `nullable` for `no_ahli` |

---

## ⚙️ Backend Changes Detail

### `MemberService::createMemberByAdmin()`

**Current issue:** `no_ahli` and `member_status_id = aktif` are only set when `approve_immediately = true`.

**Fix:**
```php
// Always default to Aktif status on admin create
$aktifStatus = MemberStatus::where('code', 'aktif')->first();
$statusId = $aktifStatus?->id ?? $data['member_status_id'];

// Auto-generate no_ahli — use temp placeholder, update after insert
$member = Member::create($memberData);
if (empty($member->no_ahli)) {
    $member->update([
        'no_ahli' => 'AHL-' . str_pad((string) $member->id, 5, '0', STR_PAD_LEFT),
    ]);
}
```

### `tarikh_daftar` 
Already defaults to `now()->toDateString()` in service — **no change needed**, just lock the UI field as pre-filled.

---

## 🎨 UI/UX Design System

### Design Tokens
- **Primary:** Indigo-600 / Indigo-500
- **Success/Aktif:** Emerald-500
- **Warning:** Amber-400
- **Danger:** Red-500
- **Background:** Gray-50 / Gray-900 (dark)
- **Card radius:** `rounded-2xl`
- **Shadow:** `shadow-sm` on inputs, `shadow-md` on cards
- **Transitions:** `transition-all duration-200`

### Interactive Patterns
1. **Read-only badge fields** — use `<div class="badge-field">` pattern instead of disabled inputs
2. **Inline status indicator** — green dot + "Aktif" pill badge for member status
3. **Auto-generated preview** — ghost input showing `AHL-?????` with lock icon and tooltip
4. **Drag & drop file upload** — file upload zones with preview (image/pdf)
5. **Step progress** — animated progress bar connecting step circles
6. **Postcode autocomplete** — using existing `/api/postcode/{code}` endpoint
7. **IC auto-format** — dash insertion `XXXXXX-XX-XXXX` via Alpine.js

---

## 📐 Form Step Layout

### Step 1 — Maklumat Keanggotaan
```
┌─────────────────────────────────────────────────────────┐
│  ✅ Status Ahli                                          │
│  [● AKTIF]  — auto-set badge (not a dropdown)           │
│                                                          │
│  🔢 No. Ahli                                             │
│  [AHL-?????] — auto-generate preview (read-only)        │
│                                                          │
│  📅 Tarikh Daftar                                        │
│  [22/04/2026] — pre-filled, admin can change            │
└─────────────────────────────────────────────────────────┘
```

### Step 2 — Maklumat Peribadi
```
┌─────────────────────────────────────────────────────────┐
│  [Avatar Upload — drag & drop with preview]             │
│  Nama Penuh          No. KP (masked: XXXXXX-XX-XXXX)   │
│  Jawatan             Jabatan/Unit/Wad                   │
│  Jantina (toggle)    Email                              │
│  No. Tel Pejabat     No. HP                             │
│  Catatan (textarea)                                      │
└─────────────────────────────────────────────────────────┘
```

### Step 3 — Maklumat Alamat
```
┌─────────────────────────────────────────────────────────┐
│  Alamat 1 (full width)                                  │
│  Alamat 2 (full width)                                  │
│  Poskod [auto-lookup] → Bandar (auto-fill)              │
│  Negeri (dropdown Malaysia states)                      │
└─────────────────────────────────────────────────────────┘
```

### Step 4 — Maklumat Pembayaran
```
┌─────────────────────────────────────────────────────────┐
│  [Toggle: Rekod Bayaran Sekarang?]                      │
│  ── if toggled ON ──────────────────                    │
│  Tahun Bayar    Jenis Yuran                             │
│  Tahun Liputan: [2026] hingga [2026]                    │
│  No. Resit      No. Resit Sistem (auto)                 │
│  Bukti Bayaran [drag & drop]                            │
│  [✓] Aktifkan serta-merta (auto-checked)                │
└─────────────────────────────────────────────────────────┘
```

---

## ✅ Checklist

- [ ] `form1.blade.php` — Status badge, No. Ahli preview, Tarikh Daftar pre-fill
- [ ] `form2.blade.php` — Avatar upload, IC mask, modern grid layout
- [ ] `form3.blade.php` — Postcode autocomplete, state dropdown
- [ ] `form4.blade.php` — Payment toggle panel, upload preview
- [ ] `step-indicator.blade.php` — Progress bar, labels
- [ ] `MemberService.php` — Auto-aktif + auto-generate no_ahli always
- [ ] `StoreMemberRequest.php` — Make member_status_id optional

---

## 🚀 Deployment Notes

1. Run `php artisan view:clear` after deploying blade changes
2. Verify `MemberStatus` table has a record with `code = 'aktif'`
3. Test `no_ahli` uniqueness — consider adding DB unique constraint if not present
4. Test postcode API endpoint availability on server