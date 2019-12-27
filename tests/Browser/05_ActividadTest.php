<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ActividadTest extends DuskTestCase
{
    public function testActividad()
    {
        $this->browse(function (Browser $browser) {

            // Login de profesor
            $browser->visit('/login');
            $browser->type('email', 'lucia@ikasgela.com');
            $browser->type('password', '12345Abcde');
            $browser->check('remember');
            $browser->press('Entrar');
            $browser->assertPathIs('/alumnos');

            $browser->visit('/alumnos');
            $browser->check('usuarios_seleccionados[1]');
            $browser->check('seleccionadas[5]');
            $browser->press('Guardar asignación');
            $browser->assertPathIs('/alumnos');

            $browser->assertSeeIn('#app > div > main > div > div.table-responsive > table > tbody > tr:nth-child(1) > td:nth-child(4)', 'Marc');
            $browser->assertSeeIn('#app > div > main > div > div.table-responsive > table > tbody > tr:nth-child(1) > td:nth-child(6)', '1');

            // Cerrar sesión
            $browser->logout();

            // Login de alumno
            $browser->visit('/login');
            $browser->type('email', 'marc@ikasgela.com');
            $browser->type('password', '12345Abcde');
            $browser->check('remember');
            $browser->press('Entrar');
            $browser->assertPathIs('/home');

            // Aceptar actividad
            $browser->assertSee('Tres en raya');

            $browser->press('Aceptar actividad');
            $browser->assertPathIs('/home');

            // Clonar el repositorio
            $browser->assertSee('Juego de tres en raya');

            $browser->clickLink('Clonar el proyecto');
            $browser->assertPathIs('/home');
            $browser->assertSee('Abrir en IntelliJ IDEA');

            // Enviar para revisión
            $browser->press('Enviar para revisión');
            $browser->assertPathIs('/home');

            // Aparece la siguiente actividad
            $browser->assertSee('Agenda');

            // Cerrar sesión
            $browser->logout();

            // Login de profesor
            $browser->visit('/login');
            $browser->type('email', 'lucia@ikasgela.com');
            $browser->type('password', '12345Abcde');
            $browser->check('remember');
            $browser->press('Entrar');
            $browser->assertPathIs('/alumnos');

            // Corregir la tarea y darla por terminada
            $browser->visit('/alumnos/1/tareas');
            $browser->visit('/profesor/1/revisar/2');
            $browser->type('puntuacion', '80');
            $browser->press('Añadir');
            $browser->press('Terminada');
            $browser->assertPathIs('/alumnos/1/tareas');

            $browser->assertSeeIn('#app > div > main > div > div.table-responsive > table > tbody > tr:nth-child(2) > td:nth-child(8)', '80');

            // Cerrar sesión
            $browser->logout();

            // Login de alumno
            $browser->visit('/login');
            $browser->type('email', 'marc@ikasgela.com');
            $browser->type('password', '12345Abcde');
            $browser->check('remember');
            $browser->press('Entrar');
            $browser->assertPathIs('/home');

            // Aceptar actividad
            $browser->assertSee('Tres en raya');

            $browser->press('Archivar');
            $browser->assertPathIs('/home');

            // No hay más tareas
            $browser->assertSee('Agenda');
        });
    }
}