<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\Admin\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Tests\TestCase;

class AdminPermissionsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->withoutExceptionHandling();
        $this->seed([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);
        $this->admin = factory(Admin::class)->create();
        $this->admin->syncRoles(Roles::ADMIN);
    }

    public function testUpdatePermissionsToAdmin(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->put(route('update-permissions', $this->admin->id), [
            'permissions' => [
                Permissions::VIEW_ORDERS,
            ],
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect(route('admins.show', $this->admin->id))
            ->assertSessionHas('success');

        self::assertTrue($this->admin->hasPermissionTo(Permissions::VIEW_ORDERS));
    }

    public function testUpdateRoles(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->put(route('admins.update', $this->admin->id), [
            'roles' => [
                Roles::ADMIN,
                Roles::EMPLOYEE,
            ],
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect(route('admins.show', $this->admin->id))
            ->assertSessionHas('success');

        self::assertTrue($this->admin->hasRole([Roles::ADMIN, Roles::EMPLOYEE]));
    }
}
