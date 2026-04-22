<?php
// ─────────────────────────────────────────────────────────────────────────
// PATCH for MemberService::createMemberByAdmin()
// Replace the $statusId line and the no_ahli update block with the below.
// ─────────────────────────────────────────────────────────────────────────

// BEFORE (old):
// $statusId = $data['member_status_id'] ?? MemberStatus::where('code', 'tidak_aktif')->first()?->id;

// AFTER (new) — always defaults to Aktif:
// $aktifStatus = MemberStatus::where('code', 'aktif')->first();
// $statusId = $aktifStatus?->id ?? ($data['member_status_id'] ?? MemberStatus::where('code', 'tidak_aktif')->first()?->id);

// ─────────────────────────────────────────────────────────────────────────
// Also add this block RIGHT AFTER: $member = Member::create($memberData);
// ─────────────────────────────────────────────────────────────────────────

// Auto-generate no_ahli immediately on create (not only on approve)
// if (empty($member->no_ahli)) {
//     $member->update([
//         'no_ahli' => 'AHL-' . str_pad((string) $member->id, 5, '0', STR_PAD_LEFT),
//     ]);
// }

// ─────────────────────────────────────────────────────────────────────────
// Remove the duplicate no_ahli update inside the $approveImmediately block:
// ─────────────────────────────────────────────────────────────────────────
// DELETE these lines inside `if ($approveImmediately) {`:
//   'no_ahli' => $member->no_ahli ?? 'AHL-'.str_pad((string) $member->id, 5, '0', STR_PAD_LEFT),
// (keep 'member_status_id' update as-is for safety)