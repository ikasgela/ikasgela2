<?php

namespace Tests\Feature;

use App\Curso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CursosTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        parent::crearUsuarios();
    }

    public function testIndex()
    {
        // Given
        $this->actingAs($this->admin);
        $curso = factory(Curso::class)->create();

        // When
        $response = $this->get(route('cursos.index'));

        // Then
        $response->assertSee($curso->name);
    }

    public function testNotAdminNotIndex()
    {
        // Given
        $this->actingAs($this->not_admin);

        // When
        // Then
        $this->get(route('cursos.index'))
            ->assertForbidden();
    }

    public function testNotAuthNotIndex()
    {
        // Given
        // When
        // Then
        $this->get(route('cursos.index'))
            ->assertRedirect(route('login'));
    }

    public function testCreate()
    {
        // Given
        $this->actingAs($this->admin);

        // When
        $response = $this->get(route('cursos.create'));

        // Then
        $response->assertSeeInOrder([__('New course'), __('Save')]);
    }

    public function testNotAdminNotCreate()
    {
        // Given
        $this->actingAs($this->not_admin);

        // When
        // Then
        $this->get(route('cursos.create'))
            ->assertForbidden();
    }

    public function testNotAuthNotCreate()
    {
        // Given
        // When
        // Then
        $this->get(route('cursos.create'))
            ->assertRedirect(route('login'));
    }

    public function testStore()
    {
        // Given
        $this->actingAs($this->admin);
        $curso = factory(Curso::class)->make();

        // When
        $this->post(route('cursos.store'), $curso->toArray());

        // Then
        $this->assertEquals(1, Curso::all()->count());
    }

    public function testNotAdminNotStore()
    {
        // Given
        $this->actingAs($this->not_admin);
        $curso = factory(Curso::class)->make();

        // When
        // Then
        $this->post(route('cursos.store'), $curso->toArray())
            ->assertForbidden();
    }

    public function testNotAuthNotStore()
    {
        // Given
        $curso = factory(Curso::class)->make();

        // When
        // Then
        $this->post(route('cursos.store'), $curso->toArray())
            ->assertRedirect(route('login'));
    }

    public function testStoreRequiresNombre()
    {
        // Given
        $this->actingAs($this->admin);
        $curso = factory(Curso::class)->make(['nombre' => null]);

        // When
        // Then
        $this->post(route('cursos.store'), $curso->toArray())
            ->assertSessionHasErrors('nombre');
    }

    public function testStoreRequiresCategory()
    {
        // Given
        $this->actingAs($this->admin);
        $curso = factory(Curso::class)->make(['category_id' => null]);

        // When
        // Then
        $this->post(route('cursos.store'), $curso->toArray())
            ->assertSessionHasErrors('category_id');
    }

    public function testShow()
    {
        // Given
        $this->actingAs($this->admin);
        $curso = factory(Curso::class)->create();

        // When
        $response = $this->get(route('cursos.show', ['id' => $curso->id]));

        // Then
        $response->assertSee(__('Not implemented.'));
    }

    public function testNotAdminNotShow()
    {
        // Given
        $this->actingAs($this->not_admin);
        $curso = factory(Curso::class)->create();

        // When
        // Then
        $this->get(route('cursos.show', ['id' => $curso->id]))
            ->assertForbidden();
    }

    public function testNotAuthNotShow()
    {
        // Given
        $curso = factory(Curso::class)->create();

        // When
        // Then
        $this->get(route('cursos.show', ['id' => $curso->id]))
            ->assertRedirect(route('login'));
    }

    public function testEdit()
    {
        // Given
        $this->actingAs($this->admin);
        $curso = factory(Curso::class)->create();

        // When
        $response = $this->get(route('cursos.edit', ['id' => $curso->id]), $curso->toArray());

        // Then
        $response->assertSeeInOrder([$curso->name, $curso->slug, __('Save')]);
    }

    public function testNotAdminNotEdit()
    {
        // Given
        $this->actingAs($this->not_admin);
        $curso = factory(Curso::class)->create();

        // When
        // Then
        $this->get(route('cursos.edit', ['id' => $curso->id]), $curso->toArray())
            ->assertForbidden();
    }

    public function testNotAuthNotEdit()
    {
        // Given
        $curso = factory(Curso::class)->create();

        // When
        // Then
        $this->get(route('cursos.edit', ['id' => $curso->id]), $curso->toArray())
            ->assertRedirect(route('login'));
    }

    public function testUpdate()
    {
        // Given
        $this->actingAs($this->admin);
        $curso = factory(Curso::class)->create();
        $curso->nombre = "Updated";

        // When
        $this->put(route('cursos.update', ['id' => $curso->id]), $curso->toArray());

        // Then
        $this->assertDatabaseHas('cursos', ['id' => $curso->id, 'nombre' => $curso->nombre]);
    }

    public function testNotAdminNotUpdate()
    {
        // Given
        $this->actingAs($this->not_admin);
        $curso = factory(Curso::class)->create();
        $curso->name = "Updated";

        // When
        // Then
        $this->put(route('cursos.update', ['id' => $curso->id]), $curso->toArray())
            ->assertForbidden();
    }

    public function testNotAuthNotUpdate()
    {
        // Given
        $curso = factory(Curso::class)->create();
        $curso->name = "Updated";

        // When
        // Then
        $this->put(route('cursos.update', ['id' => $curso->id]), $curso->toArray())
            ->assertRedirect(route('login'));
    }

    public function testUpdateRequiresNombre()
    {
        // Given
        $this->actingAs($this->admin);
        $curso = factory(Curso::class)->create();

        // When
        $curso->nombre = null;

        // Then
        $this->put(route('cursos.update', ['id' => $curso->id]), $curso->toArray())
            ->assertSessionHasErrors('nombre');
    }

    public function testUpdateRequiresCategory()
    {
        // Given
        $this->actingAs($this->admin);
        $curso = factory(Curso::class)->create();

        // When
        $curso->category_id = null;

        // Then
        $this->put(route('cursos.update', ['id' => $curso->id]), $curso->toArray())
            ->assertSessionHasErrors('category_id');
    }

    public function testDelete()
    {
        // Given
        $this->actingAs($this->admin);
        $curso = factory(Curso::class)->create();

        // When
        $this->delete(route('cursos.destroy', ['id' => $curso->id]));

        // Then
        $this->assertDatabaseMissing('cursos', ['id' => $curso->id]);
    }

    public function testNotAdminNotDelete()
    {
        // Given
        $this->actingAs($this->not_admin);
        $curso = factory(Curso::class)->create();

        // When
        // Then
        $this->delete(route('cursos.destroy', ['id' => $curso->id]))
            ->assertForbidden();
    }

    public function testNotAuthNotDelete()
    {
        // Given
        $curso = factory(Curso::class)->create();

        // When
        // Then
        $this->delete(route('cursos.destroy', ['id' => $curso->id]))
            ->assertRedirect(route('login'));
    }
}
