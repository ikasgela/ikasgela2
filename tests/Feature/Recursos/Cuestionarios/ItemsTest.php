<?php

namespace Tests\Feature\Recursos\Cuestionarios;

use App\Item;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ItemsTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        parent::crearUsuarios();
    }

    public function testIndex()
    {
        // Given
        $this->actingAs($this->profesor);
        $item = factory(Item::class)->create();

        // When
        $response = $this->get(route('items.index'));

        // Then
        $response->assertSee($item->name);
    }

    public function testNotProfesorNotIndex()
    {
        // Given
        $this->actingAs($this->not_profesor);

        // When
        // Then
        $this->get(route('items.index'))
            ->assertForbidden();
    }

    public function testNotAuthNotIndex()
    {
        // Given
        // When
        // Then
        $this->get(route('items.index'))
            ->assertRedirect(route('login'));
    }

    public function testCreate()
    {
        // Given
        $this->actingAs($this->profesor);

        // When
        $response = $this->get(route('items.create'));

        // Then
        $response->assertSeeInOrder([__('New item'), __('Save')]);
    }

    public function testNotProfesorNotCreate()
    {
        // Given
        $this->actingAs($this->not_profesor);

        // When
        // Then
        $this->get(route('items.create'))
            ->assertForbidden();
    }

    public function testNotAuthNotCreate()
    {
        // Given
        // When
        // Then
        $this->get(route('items.create'))
            ->assertRedirect(route('login'));
    }

    public function testStore()
    {
        // Given
        $this->actingAs($this->profesor);
        $item = factory(Item::class)->make();
        $total = Item::all()->count();

        // When
        $this->post(route('items.store'), $item->toArray());

        // Then
        $this->assertEquals($total + 1, Item::all()->count());
    }

    public function testNotProfesorNotStore()
    {
        // Given
        $this->actingAs($this->not_profesor);
        $item = factory(Item::class)->make();

        // When
        // Then
        $this->post(route('items.store'), $item->toArray())
            ->assertForbidden();
    }

    public function testNotAuthNotStore()
    {
        // Given
        $item = factory(Item::class)->make();

        // When
        // Then
        $this->post(route('items.store'), $item->toArray())
            ->assertRedirect(route('login'));
    }

    public function testStoreRequiresTexto()
    {
        // Given
        $this->actingAs($this->profesor);
        $item = factory(Item::class)->make(['texto' => null]);

        // When
        // Then
        $this->post(route('items.store'), $item->toArray())
            ->assertSessionHasErrors('texto');
    }

    public function testStoreRequiresPregunta()
    {
        // Given
        $this->actingAs($this->profesor);
        $item = factory(Item::class)->make(['pregunta_id' => null]);

        // When
        // Then
        $this->post(route('items.store'), $item->toArray())
            ->assertSessionHasErrors('pregunta_id');
    }

    public function testShow()
    {
        // Given
        $this->actingAs($this->profesor);
        $item = factory(Item::class)->create();

        // When
        $response = $this->get(route('items.show', $item));

        // Then
        $response->assertSee(__('Not implemented.'));
    }

    public function testNotProfesorNotShow()
    {
        // Given
        $this->actingAs($this->not_profesor);
        $item = factory(Item::class)->create();

        // When
        // Then
        $this->get(route('items.show', $item))
            ->assertForbidden();
    }

    public function testNotAuthNotShow()
    {
        // Given
        $item = factory(Item::class)->create();

        // When
        // Then
        $this->get(route('items.show', $item))
            ->assertRedirect(route('login'));
    }

    public function testEdit()
    {
        // Given
        $this->actingAs($this->profesor);
        $item = factory(Item::class)->create();

        // When
        $response = $this->get(route('items.edit', $item), $item->toArray());

        // Then
        $response->assertSeeInOrder([$item->texto, __('Save')]);
    }

    public function testNotProfesorNotEdit()
    {
        // Given
        $this->actingAs($this->not_profesor);
        $item = factory(Item::class)->create();

        // When
        // Then
        $this->get(route('items.edit', $item), $item->toArray())
            ->assertForbidden();
    }

    public function testNotAuthNotEdit()
    {
        // Given
        $item = factory(Item::class)->create();

        // When
        // Then
        $this->get(route('items.edit', $item), $item->toArray())
            ->assertRedirect(route('login'));
    }

    public function testUpdate()
    {
        // Given
        $this->actingAs($this->profesor);
        $item = factory(Item::class)->create();
        $item->texto = "Updated";

        // When
        $this->put(route('items.update', $item), $item->toArray());

        // Then
        $this->assertDatabaseHas('items', ['id' => $item->id, 'texto' => $item->texto]);
    }

    public function testNotProfesorNotUpdate()
    {
        // Given
        $this->actingAs($this->not_profesor);
        $item = factory(Item::class)->create();
        $item->texto = "Updated";

        // When
        // Then
        $this->put(route('items.update', $item), $item->toArray())
            ->assertForbidden();
    }

    public function testNotAuthNotUpdate()
    {
        // Given
        $item = factory(Item::class)->create();
        $item->texto = "Updated";

        // When
        // Then
        $this->put(route('items.update', $item), $item->toArray())
            ->assertRedirect(route('login'));
    }

    public function testUpdateRequiresTexto()
    {
        // Given
        $this->actingAs($this->profesor);
        $item = factory(Item::class)->create();

        // When
        $item->texto = null;

        // Then
        $this->put(route('items.update', $item), $item->toArray())
            ->assertSessionHasErrors('texto');
    }

    public function testUpdateRequiresPregunta()
    {
        // Given
        $this->actingAs($this->profesor);
        $item = factory(Item::class)->create();

        // When
        $item->pregunta_id = null;

        // Then
        $this->put(route('items.update', $item), $item->toArray())
            ->assertSessionHasErrors('pregunta_id');
    }

    public function testDelete()
    {
        // Given
        $this->actingAs($this->profesor);
        $item = factory(Item::class)->create();

        // When
        $this->delete(route('items.destroy', $item));

        // Then
        $this->assertDatabaseMissing('items', $item->toArray());
    }

    public function testNotProfesorNotDelete()
    {
        // Given
        $this->actingAs($this->not_profesor);
        $item = factory(Item::class)->create();

        // When
        // Then
        $this->delete(route('items.destroy', $item))
            ->assertForbidden();
    }

    public function testNotAuthNotDelete()
    {
        // Given
        $item = factory(Item::class)->create();

        // When
        // Then
        $this->delete(route('items.destroy', $item))
            ->assertRedirect(route('login'));
    }
}
