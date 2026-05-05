<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdminKawalanJabatanViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_jabatan_kawalan_index(): void
    {
        $this->get(route('admin.kawalan.jabatan.index'))->assertRedirect();
    }

    public function test_non_admin_cannot_access_jabatan_kawalan_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.kawalan.jabatan.index'))->assertForbidden();
    }

    public function test_admin_can_render_jabatan_kawalan_index_view(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.kawalan.jabatan.index'))
            ->assertOk()
            ->assertSee('Kawalan Jabatan', false);
    }
}
