<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Constants\Roles;
use App\Models\Admin\Admin;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->seed([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);
        $this->admin = factory(Admin::class)->create();
        $this->admin->assignRole(Roles::ADMIN);
        $this->withoutExceptionHandling();
    }

    public function testIndex(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('tags.index'));

        $response
            ->assertStatus(200)
            ->assertViewIs('admin.tags.index')
            ->assertViewHas('tags');
    }

    public function testUpdate(): void
    {
        $tag = factory(Tag::class)->create();

        $response = $this->actingAs($this->admin, 'admin')->put(
            route('tags.update', $tag),
            [
                                'name' => 'new name',
                            ]
        );

        $response
            ->assertStatus(302)
            ->assertSessionHas('success');

        $this->assertDatabaseHas(
            'tags',
            [
            'name' => 'new name',
            ]
        );
    }

    public function testStore(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(
            route('tags.store'),
            [
                                'name' => 'new tag',
                            ]
        );

        $response
            ->assertStatus(302)
            ->assertSessionHas('success');

        $this->assertDatabaseHas(
            'tags',
            [
            'name' => 'new tag',
            ]
        );
    }

    public function testDestroy(): void
    {
        $tag = factory(Tag::class)->create();

        $response = $this->actingAs($this->admin, 'admin')->delete(route('tags.destroy', $tag));

        $response
            ->assertStatus(302)
            ->assertSessionHas('success');

        $this->assertDatabaseMissing(
            'tags',
            [
            'id' => $tag->id,
            ]
        );
    }
}