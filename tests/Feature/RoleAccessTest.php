<?php

namespace Tests\Feature;

use App\Models\Contrato;
use App\Models\DocumentoRequerido;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_consulta_cannot_access_users_index(): void
    {
        $consulta = User::factory()->create(['rol' => 'consulta']);

        $response = $this->actingAs($consulta)->get(route('usuarios.index'));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_consulta_cannot_create_a_contract(): void
    {
        $consulta = User::factory()->create(['rol' => 'consulta']);

        $response = $this->actingAs($consulta)->post(route('contratos.store'), [
            'numero_contrato'    => 'C-9999',
            'fecha_contrato'     => '2026-04-10',
            'fecha_inicio'       => '2026-04-11',
            'fecha_fin'          => '2026-05-11',
            'cedula_contratista' => '111222333',
            'nombre_contratista' => 'Sin permiso',
            'estado'             => 'Activo',
            'etiqueta'           => 'Pendiente',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseMissing('contratos', ['numero_contrato' => 'C-9999']);
    }

    public function test_gestor_cannot_delete_a_contract(): void
    {
        $admin    = User::factory()->create(['rol' => 'admin']);
        $gestor   = User::factory()->create(['rol' => 'gestor']);
        $contrato = $this->createContract($admin);

        $response = $this->actingAs($gestor)->delete(route('contratos.destroy', $contrato));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('contratos', ['id' => $contrato->id]);
    }

    public function test_admin_can_delete_a_contract(): void
    {
        $admin    = User::factory()->create(['rol' => 'admin']);
        $contrato = $this->createContract($admin);

        $response = $this->actingAs($admin)->delete(route('contratos.destroy', $contrato));

        $response->assertRedirect(route('contratos.index'));
        $this->assertDatabaseMissing('contratos', ['id' => $contrato->id]);
    }

    public function test_consulta_can_view_contract_index(): void
    {
        $consulta = User::factory()->create(['rol' => 'consulta']);

        $response = $this->actingAs($consulta)->get(route('contratos.index'));

        $response->assertOk();
    }

    public function test_unauthenticated_request_to_protected_route_redirects_to_login(): void
    {
        $response = $this->get(route('usuarios.index'));

        $response->assertRedirect(route('login'));
    }

    private function createContract(User $user): Contrato
    {
        $contrato = Contrato::create([
            'created_by'         => $user->id,
            'numero_contrato'    => 'C-RA-'.fake()->unique()->numerify('####'),
            'fecha_contrato'     => '2026-04-10',
            'fecha_inicio'       => '2026-04-11',
            'fecha_fin'          => '2026-05-30',
            'cedula_contratista' => fake()->numerify('#########'),
            'nombre_contratista' => fake()->name(),
            'estado'             => 'Activo',
            'etiqueta'           => 'Pendiente',
            'descripcion'        => 'Contrato para tests de acceso por rol',
        ]);

        foreach (DocumentoRequerido::plantillaBase() as $item) {
            $contrato->documentosRequeridos()->create($item + ['obligatorio' => true]);
        }

        return $contrato;
    }
}
