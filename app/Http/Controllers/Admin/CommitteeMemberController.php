<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Kawalan\StoreCommitteeMemberRequest;
use App\Http\Requests\Admin\Kawalan\UpdateCommitteeMemberRequest;
use App\Models\CommitteeMember;
use App\Services\CommitteeMemberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CommitteeMemberController extends Controller
{
    public function __construct(
        private readonly CommitteeMemberService $committeeMemberService
    ) {}

    public function index(): View
    {
        return view('admin.kawalan.ajk');
    }

    public function list(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            abort(400, 'Invalid request');
        }

        $data = $this->committeeMemberService->listOrdered()->map(fn (CommitteeMember $member) => [
            'id' => $member->id,
            'name' => $member->name,
            'jawatan' => $member->jawatan,
            'photo_url' => $member->photoUrl(),
            'is_active' => $member->is_active,
        ]);

        return response()->json(['data' => $data]);
    }

    public function store(StoreCommitteeMemberRequest $request): JsonResponse
    {
        $this->committeeMemberService->create($request->validated(), $request->file('photo'));

        return response()->json([
            'success' => true,
            'message' => 'Ahli jawatankuasa berjaya ditambah.',
        ]);
    }

    public function update(UpdateCommitteeMemberRequest $request, CommitteeMember $committeeMember): JsonResponse
    {
        $this->committeeMemberService->update($committeeMember, $request->validated(), $request->file('photo'));

        return response()->json([
            'success' => true,
            'message' => 'Ahli jawatankuasa telah dikemas kini.',
        ]);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer'],
        ]);

        $this->committeeMemberService->reorder($validated['order']);

        return response()->json([
            'success' => true,
            'message' => 'Susunan dikemas kini.',
        ]);
    }

    public function destroy(CommitteeMember $committeeMember): JsonResponse
    {
        $this->committeeMemberService->delete($committeeMember);

        return response()->json([
            'success' => true,
            'message' => 'Ahli jawatankuasa berjaya dipadam.',
        ]);
    }
}
