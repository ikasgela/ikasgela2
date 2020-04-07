<?php

namespace Tests\Feature;

use App\Cuestionario;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CuestionariosCRUDTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        parent::crearUsuarios();
    }

    public function testIndex()
    {
        // Auth
        $this->actingAs($this->profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->create([
            'plantilla' => true,
        ]);

        // When
        $response = $this->get(route('cuestionarios.index'));

        // Then
        $response->assertSee($cuestionario->titulo);
    }

    public function testNotPlantillaNotIndex()
    {
        // Auth
        $this->actingAs($this->profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->create();

        // When
        $response = $this->get(route('cuestionarios.index'));

        // Then
        $response->assertDontSee($cuestionario->titulo);
    }

    public function testNotProfesorNotIndex()
    {
        // Auth
        $this->actingAs($this->not_profesor);

        // Given
        // When
        $response = $this->get(route('cuestionarios.index'));

        // Then
        $response->assertForbidden();
    }

    public function testNotAuthNotIndex()
    {
        // Auth
        // Given
        // When
        $response = $this->get(route('cuestionarios.index'));

        // Then
        $response->assertRedirect(route('login'));
    }

    public function testCreate()
    {
        // Auth
        $this->actingAs($this->profesor);

        // Given
        // When
        $response = $this->get(route('cuestionarios.create'));

        // Then
        $response->assertSeeInOrder([__('New questionnaire'), __('Save')]);
    }

    public function testNotProfesorNotCreate()
    {
        // Auth
        $this->actingAs($this->not_profesor);

        // Given
        // When
        $response = $this->get(route('cuestionarios.create'));

        // Then
        $response->assertForbidden();
    }

    public function testNotAuthNotCreate()
    {
        // Auth
        // Given
        // When
        $response = $this->get(route('cuestionarios.create'));

        // Then
        $response->assertRedirect(route('login'));
    }

    public function testStore()
    {
        // Auth
        $this->actingAs($this->profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->make();
        $total = Cuestionario::all()->count();

        // When
        $this->post(route('cuestionarios.store'), $cuestionario->toArray());

        // Then
        $this->assertEquals($total + 1, Cuestionario::all()->count());
    }

    public function testNotProfesorNotStore()
    {
        // Auth
        $this->actingAs($this->not_profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->make();

        // When
        $response = $this->post(route('cuestionarios.store'), $cuestionario->toArray());

        // Then
        $response->assertForbidden();
    }

    public function testNotAuthNotStore()
    {
        // Auth
        // Given
        $cuestionario = factory(Cuestionario::class)->make();

        // When
        $response = $this->post(route('cuestionarios.store'), $cuestionario->toArray());

        // Then
        $response->assertRedirect(route('login'));
    }

    public function testStoreThereAreRequiredFields()
    {
        // Auth
        $this->actingAs($this->profesor);

        // Given
        $empty = new Cuestionario();

        // When
        $response = $this->post(route('cuestionarios.store'), $empty->toArray());

        // Then
        $response->assertSessionHasErrors();
    }

    private function storeRequires(string $field)
    {
        // Auth
        $this->actingAs($this->profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->make([$field => null]);

        // When
        $response = $this->post(route('cuestionarios.store'), $cuestionario->toArray());

        // Then
        $response->assertSessionHasErrors($field);
    }

    public function testStoreRequiresTitulo()
    {
        $this->storeRequires('titulo');
    }

    public function testShow()
    {
        // Auth
        $this->actingAs($this->profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->create();

        // When
        $response = $this->get(route('cuestionarios.show', $cuestionario));

        // Then
        $response->assertSeeInOrder([__('Questionnaire'), $cuestionario->titulo]);
    }

    public function testNotProfesorNotShow()
    {
        // Auth
        $this->actingAs($this->not_profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->create();

        // When
        $response = $this->get(route('cuestionarios.show', $cuestionario));

        // Then
        $response->assertForbidden();
    }

    public function testNotAuthNotShow()
    {
        // Given
        $cuestionario = factory(Cuestionario::class)->create();

        // When
        $response = $this->get(route('cuestionarios.show', $cuestionario));

        // Then
        $response->assertRedirect(route('login'));
    }

    public function testEdit()
    {
        // Auth
        $this->actingAs($this->profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->create();

        // When
        $response = $this->get(route('cuestionarios.edit', $cuestionario), $cuestionario->toArray());

        // Then
        $response->assertSeeInOrder([$cuestionario->titulo, __('Save')]);
    }

    public function testNotProfesorNotEdit()
    {
        // Auth
        $this->actingAs($this->not_profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->create();

        // When
        $response = $this->get(route('cuestionarios.edit', $cuestionario), $cuestionario->toArray());

        // Then
        $response->assertForbidden();
    }

    public function testNotAuthNotEdit()
    {
        // Auth
        // Given
        $cuestionario = factory(Cuestionario::class)->create();

        // When
        $response = $this->get(route('cuestionarios.edit', $cuestionario), $cuestionario->toArray());

        // Then
        $response->assertRedirect(route('login'));
    }

    public function testUpdate()
    {
        // Auth
        $this->actingAs($this->profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->create();
        $cuestionario->titulo = "Updated";

        // When
        $this->put(route('cuestionarios.update', $cuestionario), $cuestionario->toArray());

        // Then
        $this->assertDatabaseHas('cuestionarios', ['id' => $cuestionario->id, 'titulo' => $cuestionario->titulo]);
    }

    public function testNotProfesorNotUpdate()
    {
        // Auth
        $this->actingAs($this->not_profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->create();
        $cuestionario->titulo = "Updated";

        // When
        $response = $this->put(route('cuestionarios.update', $cuestionario), $cuestionario->toArray());

        // Then
        $response->assertForbidden();
    }

    public function testNotAuthNotUpdate()
    {
        // Auth
        // Given
        $cuestionario = factory(Cuestionario::class)->create();
        $cuestionario->titulo = "Updated";

        // When
        $response = $this->put(route('cuestionarios.update', $cuestionario), $cuestionario->toArray());

        // Then
        $response->assertRedirect(route('login'));
    }

    public function testUpdateThereAreRequiredFields()
    {
        // Auth
        $this->actingAs($this->profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->create();
        $empty = new Cuestionario();

        // When
        $response = $this->put(route('cuestionarios.update', $cuestionario), $empty->toArray());

        // Then
        $response->assertSessionHasErrors();
    }

    private function updateRequires(string $field)
    {
        // Auth
        $this->actingAs($this->profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->create();
        $cuestionario->$field = null;

        // When
        $response = $this->put(route('cuestionarios.update', $cuestionario), $cuestionario->toArray());

        // Then
        $response->assertSessionHasErrors($field);
    }

    public function testUpdateRequiresTitulo()
    {
        $this->updateRequires('titulo');
    }

    public function testDelete()
    {
        // Auth
        $this->actingAs($this->profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->create();

        // When
        $this->delete(route('cuestionarios.destroy', $cuestionario));

        // Then
        $this->assertDatabaseMissing('cuestionarios', $cuestionario->toArray());
    }

    public function testNotProfesorNotDelete()
    {
        // Auth
        $this->actingAs($this->not_profesor);

        // Given
        $cuestionario = factory(Cuestionario::class)->create();

        // When
        $response = $this->delete(route('cuestionarios.destroy', $cuestionario));

        // Then
        $response->assertForbidden();
    }

    public function testNotAuthNotDelete()
    {
        // Auth
        // Given
        $cuestionario = factory(Cuestionario::class)->create();

        // When
        $response = $this->delete(route('cuestionarios.destroy', $cuestionario));

        // Then
        $response->assertRedirect(route('login'));
    }
}
