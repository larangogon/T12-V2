<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Constants\Roles;
use App\Models\Admin\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Database\Seeders\TestDatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->seed([
            TestDatabaseSeeder::class,
        ]);

        $this->admin = factory(Admin::class)->create();
        $this->admin->assignRole(Roles::ADMIN);
    }

    /**
     * test view products
     *
     * @return void
     */
    public function testIndex(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('products.index'));

        $response
            ->assertStatus(200)
            ->assertViewIs('admin.products.index')
            ->assertViewHas('products');
    }

    public function testIndexWithQuery(): void
    {
        $response = $this
                        ->actingAs($this->admin, 'admin')
                        ->get(route('products.index'), [
                                'category' => 1,
                                'orderBy' => __('Less recent'),
                                'search' => 'new',
                                'tags' => null,
                        ]);

        $response
            ->assertStatus(200)
            ->assertViewIs('admin.products.index')
            ->assertViewHas('products');
    }

    public function testEditProduct(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('products.edit', [
            'product' => Product::all()->random()->id,
        ]));

        $response
            ->assertStatus(200)
            ->assertViewIs('admin.products.edit')
            ->assertViewHas('product');
    }

    /**
     * test route admin/products/$product->id/edit
     *
     * @return void
     */
    public function testStore(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(
            route('products.store'),
            [
            'reference'     =>  1111,
            'name'          =>  'new product',
            'description'   =>  'new description at product incoming',
            'stock'         =>  0,
            'cost'         =>  1000,
            'price'         =>  2000,
            'id_category'   => Category::all()->random()->id,
            'tags'          => [Tag::all()->random()->id],
            'photos'        => [$this->faker->file(storage_path('app/public/photos'))],
            ]
        );

        $response
            ->assertStatus(302);

        $this->assertDatabaseHas('products', [
            'name'          =>  'new product',
            'description'   =>  'new description at product incoming',
            'stock'         =>  '0',
            'price'         =>  2000,
        ]);
    }

    /**
     * test update product
     *
     * @return void
     */
    public function testUpdateProduct(): void
    {
        $this->withoutExceptionHandling();
        $product =  Product::all()->random();
        $response = $this->actingAs($this->admin, 'admin')->put(route('products.update', [
            'product' =>  $product->id,
        ]), [
            'reference'     =>  1111,
            'name'          => 'mi nuevo super producto',
            'description'   => $product->description,
            'cost'          => 1000,
            'price'         => 2000,
            'id_category'   => $product->id_category,
            'tags'          => $product->tags->pluck('id')->toArray(),
        ]);

        $response
            ->assertRedirect(route('products.edit', ['product' => $product->id]))
            ->assertSessionHas('success')
            ->assertStatus(302);
        $this->assertDatabaseHas('products', ['name' => 'mi nuevo super producto']);
    }

    /**
     * test delete product
     *
     * @return void
     */
    public function testDeleteProduct(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->delete(route('products.destroy', [
            'product' => Product::all()->random()->id,
        ]));

        $response->assertRedirect(route('products.index'))
            ->assertSessionHas('success')
            ->assertStatus(302);
    }
}
