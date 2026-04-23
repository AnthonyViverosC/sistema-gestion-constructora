<?php

namespace Tests\Feature;

use App\Models\Contrato;
use App\Models\Documento;
use App\Models\DocumentoRequerido;
use App\Models\Notificacion;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContractWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_contract_and_base_document_structure_is_created(): void
    {
        $admin = $this->createUser('admin');

        $response = $this->actingAs($admin)->post(route('contratos.store'), [
            'numero_contrato' => 'C-1001',
            'fecha_contrato' => '2026-04-10',
            'fecha_inicio' => '2026-04-11',
            'fecha_fin' => '2026-05-11',
            'cedula_contratista' => '123456789',
            'nombre_contratista' => 'Mario',
            'estado' => 'Activo',
            'etiqueta' => 'Pendiente',
            'descripcion' => 'Contrato de prueba',
        ]);

        $response->assertRedirect(route('contratos.index'));

        $contrato = Contrato::where('numero_contrato', 'C-1001')->first();

        $this->assertNotNull($contrato);
        $this->assertDatabaseHas('contratos', [
            'numero_contrato' => 'C-1001',
            'created_by' => $admin->id,
        ]);
        $this->assertCount(4, $contrato->documentosRequeridos);
    }

    public function test_admin_can_add_a_document_structure_section_to_a_contract(): void
    {
        $admin = $this->createUser('admin');
        $contrato = $this->createContract($admin);

        $response = $this->actingAs($admin)->post(route('contratos.estructura-documental.store', $contrato), [
            'nombre' => 'Poliza contractual',
            'categoria' => 'Garantias',
            'descripcion' => 'Cobertura vigente',
            'obligatorio' => '1',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('documento_requeridos', [
            'contrato_id' => $contrato->id,
            'nombre' => 'Poliza contractual',
            'categoria' => 'Garantias',
        ]);
    }

    public function test_admin_can_upload_a_document_and_a_version_record_is_created(): void
    {
        Storage::fake('public');

        $admin = $this->createUser('admin');
        $contrato = $this->createContract($admin);

        $response = $this->actingAs($admin)->post(route('documentos.store', $contrato), [
            'nombre_documento' => 'Acta inicial',
            'archivo' => UploadedFile::fake()->create('acta-inicial.zip', 120, 'application/zip'),
            'categoria' => 'Actos Administrativos',
            'fecha_carga' => '2026-04-12',
            'estado' => 'Pendiente',
            'etiqueta' => 'Pendiente',
            'descripcion' => 'Soporte inicial',
        ]);

        $response->assertRedirect(route('documentos.create', $contrato));

        $documento = Documento::where('nombre_documento', 'Acta inicial')->first();

        $this->assertNotNull($documento);
        $this->assertDatabaseHas('documentos', [
            'id' => $documento->id,
            'contrato_id' => $contrato->id,
            'uploaded_by' => $admin->id,
            'categoria' => 'Actos Administrativos',
        ]);
        $this->assertDatabaseHas('documento_versiones', [
            'documento_id' => $documento->id,
            'numero_version' => 1,
        ]);
        Storage::disk('public')->assertExists($documento->archivo);
    }

    public function test_documents_can_be_filtered_by_category_and_tag(): void
    {
        $admin = $this->createUser('admin');
        $contrato = $this->createContract($admin);

        Documento::create([
            'contrato_id' => $contrato->id,
            'uploaded_by' => $admin->id,
            'nombre_documento' => 'Contrato firmado',
            'nombre_original' => 'contrato.pdf',
            'archivo' => 'documentos/contrato.pdf',
            'categoria' => 'Contrato',
            'fecha_carga' => '2026-04-10',
            'estado' => 'Aprobado',
            'etiqueta' => 'Completo',
        ]);

        Documento::create([
            'contrato_id' => $contrato->id,
            'uploaded_by' => $admin->id,
            'nombre_documento' => 'Seguridad abril',
            'nombre_original' => 'seguridad.xlsx',
            'archivo' => 'documentos/seguridad.xlsx',
            'categoria' => 'Seguridad Social',
            'fecha_carga' => '2026-04-15',
            'estado' => 'Pendiente',
            'etiqueta' => 'Falta revisar',
        ]);

        $response = $this->actingAs($admin)->get(route('documentos.create', [
            'contrato' => $contrato,
            'categoria' => 'Seguridad Social',
            'etiqueta' => 'Falta revisar',
        ]));

        $response->assertOk();
        $response->assertSee('Seguridad abril');
        $response->assertDontSee('Contrato firmado');
    }

    public function test_admin_can_create_and_complete_a_task(): void
    {
        $admin = $this->createUser('admin');
        $gestor = $this->createUser('gestor');
        $contrato = $this->createContract($admin);
        $documento = $this->createDocument($contrato, $admin, [
            'categoria' => 'Contrato',
            'nombre_documento' => 'Documento asociado',
        ]);

        $createResponse = $this->actingAs($admin)->post(route('tareas.store', $contrato), [
            'documento_id' => $documento->id,
            'assigned_to' => $gestor->id,
            'titulo' => 'Revisar soporte',
            'descripcion' => 'Validar datos del archivo',
            'fecha_limite' => '2026-04-20',
        ]);

        $createResponse->assertSessionHas('success');

        $tarea = Tarea::where('titulo', 'Revisar soporte')->first();
        $this->assertNotNull($tarea);
        $this->assertDatabaseHas('tareas', [
            'id' => $tarea->id,
            'contrato_id' => $contrato->id,
            'documento_id' => $documento->id,
            'assigned_to' => $gestor->id,
            'estado' => 'Pendiente',
        ]);

        $completeResponse = $this->actingAs($gestor)->patch(route('tareas.complete', $tarea));

        $completeResponse->assertSessionHas('success');
        $this->assertDatabaseHas('tareas', [
            'id' => $tarea->id,
            'estado' => 'Completada',
        ]);
        $this->assertNotNull($tarea->fresh()->completed_at);
    }

    public function test_alert_center_generates_notifications_for_due_tasks(): void
    {
        $admin = $this->createUser('admin');
        $contrato = $this->createContract($admin);
        $tarea = Tarea::create([
            'contrato_id' => $contrato->id,
            'created_by' => $admin->id,
            'assigned_to' => $admin->id,
            'titulo' => 'Subir seguro',
            'descripcion' => 'Pendiente critico',
            'fecha_limite' => '2026-04-14',
            'estado' => 'Pendiente',
        ]);

        $response = $this->actingAs($admin)->get(route('notificaciones.index'));

        $response->assertOk();
        $this->assertDatabaseHas('notificaciones', [
            'tarea_id' => $tarea->id,
            'user_id' => $admin->id,
            'tipo' => 'tarea_vencida',
        ]);
        $this->assertNotNull($tarea->fresh()->notified_at);
        $this->assertGreaterThanOrEqual(1, Notificacion::count());
    }

    public function test_contract_can_be_marked_as_complete_when_all_required_documents_are_approved(): void
    {
        $admin = $this->createUser('admin');
        $contrato = $this->createContract($admin);

        foreach (DocumentoRequerido::plantillaBase() as $item) {
            $this->createDocument($contrato, $admin, [
                'nombre_documento' => $item['nombre'],
                'nombre_original' => str_replace(' ', '-', strtolower($item['nombre'])).'.pdf',
                'archivo' => 'documentos/'.str_replace(' ', '-', strtolower($item['nombre'])).'.pdf',
                'categoria' => $item['categoria'],
                'estado' => 'Aprobado',
                'etiqueta' => 'Completo',
            ]);
        }

        $response = $this->actingAs($admin)->post(route('contratos.completar-documentacion', $contrato));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contratos', [
            'id' => $contrato->id,
            'estado' => 'Documentación completa',
            'etiqueta' => 'Completo',
        ]);
    }

    private function createUser(string $rol): User
    {
        return User::factory()->create([
            'rol' => $rol,
        ]);
    }

    private function createContract(User $user): Contrato
    {
        $contrato = Contrato::create([
            'created_by' => $user->id,
            'numero_contrato' => 'C-'.$user->id.'-'.fake()->unique()->numerify('###'),
            'fecha_contrato' => '2026-04-10',
            'fecha_inicio' => '2026-04-11',
            'fecha_fin' => '2026-05-30',
            'cedula_contratista' => fake()->numerify('#########'),
            'nombre_contratista' => fake()->name(),
            'estado' => 'Activo',
            'etiqueta' => 'Pendiente',
            'descripcion' => 'Contrato para pruebas',
        ]);

        foreach (DocumentoRequerido::plantillaBase() as $item) {
            $contrato->documentosRequeridos()->create($item + ['obligatorio' => true]);
        }

        return $contrato;
    }

    private function createDocument(Contrato $contrato, User $user, array $overrides = []): Documento
    {
        $defaults = [
            'contrato_id' => $contrato->id,
            'uploaded_by' => $user->id,
            'nombre_documento' => 'Documento de prueba',
            'nombre_original' => 'documento-prueba.pdf',
            'archivo' => 'documentos/documento-prueba.pdf',
            'categoria' => 'Contrato',
            'fecha_carga' => '2026-04-10',
            'estado' => 'Pendiente',
            'etiqueta' => 'Pendiente',
            'descripcion' => 'Documento generado en test',
        ];

        return Documento::create(array_merge($defaults, $overrides));
    }
}
