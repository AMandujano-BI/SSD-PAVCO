<?php

namespace Tests\Feature;

use App\Http\Controllers\ReportController;
use App\Models\Chemical;
use App\Models\Company;
use App\Models\Part;
use App\Models\PlateMethod;
use App\Models\Rol;
use App\Models\Run;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PartPlateLayersTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $run;
    private $semiBrightNi;
    private $brightNi;
    private $trivalentChrome;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['username' => 'tester', 'status' => 1]);
        $this->user->rols()->attach(Rol::create(['name' => 'Employee', 'status' => 1])->id);

        $this->semiBrightNi = Chemical::create(['name' => 'Semi Bright Ni', 'type' => 3]);
        $this->brightNi = Chemical::create(['name' => 'Bright Ni', 'type' => 3]);
        $this->trivalentChrome = Chemical::create(['name' => 'Trivalent Chrome', 'type' => 3]);

        $company = Company::create(['name' => 'Acme']);
        $method = PlateMethod::create(['name' => 'Rack Plated']);
        $this->run = Run::create([
            'start_date' => '2022-03-01 14:05:00',
            'number' => 1,
            'description' => 'test run',
            'company_id' => $company->id,
            'plate_methods_id' => $method->id,
            'user_id' => $this->user->id,
        ]);
    }

    private function partPayload(array $overrides = []): array
    {
        return array_merge([
            'run_id' => $this->run->id,
            'description' => 'Part number 1',
            'plate_layers' => [
                [
                    'plate_type_id' => $this->semiBrightNi->id,
                    'thickness' => '10',
                    'thickness_unit' => 2,
                    'per' => '5',
                    'temp' => '130',
                    'ph' => '4',
                    'diptime' => '2400',
                ],
                [
                    'plate_type_id' => $this->brightNi->id,
                    'thickness' => '6',
                    'thickness_unit' => 1,
                    'per' => '',
                    'temp' => '140',
                    'ph' => '',
                    'diptime' => '1200',
                ],
            ],
        ], $overrides);
    }

    public function test_a_part_can_be_created_with_multiple_plate_layers_each_with_its_own_thickness_and_conditions()
    {
        $response = $this->actingAs($this->user)->postJson('/part', $this->partPayload());

        $response->assertOk()->assertJson(['ok' => true]);

        $part = Part::first();
        $layers = $part->plateLayers;
        $this->assertCount(2, $layers);

        $this->assertSame([1, 2], $layers->pluck('position')->all());
        $this->assertSame($this->semiBrightNi->id, $layers[0]->plate_type_id);
        $this->assertSame('10', $layers[0]->thickness);
        $this->assertSame('2', $layers[0]->thickness_unit);
        $this->assertSame('2400', $layers[0]->diptime);

        $this->assertSame($this->brightNi->id, $layers[1]->plate_type_id);
        $this->assertSame('6', $layers[1]->thickness);
        $this->assertSame('1', $layers[1]->thickness_unit);
        $this->assertNull($layers[1]->per);
        $this->assertSame('140', $layers[1]->temp);
    }

    public function test_the_legacy_plate_columns_mirror_the_first_layer()
    {
        $this->actingAs($this->user)->postJson('/part', $this->partPayload())->assertOk();

        $part = Part::first();
        $this->assertSame($this->semiBrightNi->id, $part->plate_types_id);
        $this->assertSame('10', $part->plateThick);
        $this->assertSame('2', $part->typePlateThick);
    }

    public function test_the_legacy_single_plate_payload_still_creates_one_layer()
    {
        $this->actingAs($this->user)->postJson('/part', [
            'run_id' => $this->run->id,
            'description' => 'Legacy',
            'plate_types_id' => $this->brightNi->id,
            'plateThick' => '7',
            'typePlateThick' => 1,
        ])->assertOk()->assertJson(['ok' => true]);

        $layers = Part::first()->plateLayers;
        $this->assertCount(1, $layers);
        $this->assertSame($this->brightNi->id, $layers[0]->plate_type_id);
        $this->assertSame('7', $layers[0]->thickness);
    }

    public function test_layers_without_a_plate_type_are_ignored()
    {
        $payload = $this->partPayload();
        $payload['plate_layers'][] = ['plate_type_id' => null, 'thickness' => '9'];
        $payload['plate_layers'][] = ['plate_type_id' => 0, 'thickness' => '9'];

        $this->actingAs($this->user)->postJson('/part', $payload)->assertOk();

        $this->assertCount(2, Part::first()->plateLayers);
    }

    public function test_updating_a_part_replaces_its_layers_and_remirrors_the_first_one()
    {
        $this->actingAs($this->user)->postJson('/part', $this->partPayload())->assertOk();
        $part = Part::first();

        $this->actingAs($this->user)->putJson('/part/' . $part->id, $this->partPayload([
            'id' => $part->id,
            'plate_layers' => [
                ['plate_type_id' => $this->trivalentChrome->id, 'thickness' => '0.5', 'thickness_unit' => 1],
            ],
        ]))->assertOk()->assertJson(['ok' => true]);

        $part->refresh();
        $this->assertCount(1, $part->plateLayers);
        $this->assertSame($this->trivalentChrome->id, $part->plate_types_id);
        $this->assertSame('0.5', $part->plateThick);
    }

    public function test_removing_every_layer_clears_the_legacy_columns()
    {
        $this->actingAs($this->user)->postJson('/part', $this->partPayload())->assertOk();
        $part = Part::first();

        $this->actingAs($this->user)->putJson('/part/' . $part->id, $this->partPayload([
            'id' => $part->id,
            'plate_layers' => [],
        ]))->assertOk()->assertJson(['ok' => true]);

        $part->refresh();
        $this->assertCount(0, $part->plateLayers);
        $this->assertNull($part->plate_types_id);
        $this->assertNull($part->plateThick);
    }

    public function test_parts_by_run_returns_the_layers_with_their_plate_type_names()
    {
        $this->actingAs($this->user)->postJson('/part', $this->partPayload())->assertOk();

        $response = $this->actingAs($this->user)->getJson('/part/getPartsByRun/' . $this->run->id);

        $response->assertOk();
        $layers = $response->json('data.0.plate_layers');
        $this->assertSame(['Semi Bright Ni', 'Bright Ni'], array_column(array_column($layers, 'plate_type'), 'name'));
    }

    public function test_a_run_created_with_parts_stores_the_layers_for_every_generated_part()
    {
        $response = $this->actingAs($this->user)->postJson('/run', [
            'start_date' => '2022-03-01',
            'description' => 'multi',
            'company_id' => $this->run->company_id,
            'plate_methods_id' => $this->run->plate_methods_id,
            'parts' => [
                [
                    'numberParts' => 3,
                    'plate_layers' => $this->partPayload()['plate_layers'],
                ],
            ],
        ]);

        $response->assertOk()->assertJson(['ok' => true]);

        $newRunId = $response->json('value.id');
        $parts = Part::where('run_id', $newRunId)->get();
        $this->assertCount(3, $parts);
        foreach ($parts as $part) {
            $this->assertCount(2, $part->plateLayers);
        }
    }

    public function test_a_chemical_used_only_as_a_secondary_layer_cannot_change_type()
    {
        $this->actingAs($this->user)->postJson('/part', $this->partPayload())->assertOk();

        $result = Chemical::updateChemical((object) [
            'id' => $this->brightNi->id,
            'name' => 'Bright Ni',
            'type' => 1,
        ]);

        $this->assertFalse($result['ok']);
        $this->assertSame(3, $this->brightNi->fresh()->type);
    }

    public function test_the_report_plate_filter_matches_parts_that_have_the_plate_as_a_secondary_layer()
    {
        $this->actingAs($this->user)->postJson('/part', $this->partPayload())->assertOk();
        $this->actingAs($this->user)->postJson('/part', $this->partPayload([
            'description' => 'Chrome only',
            'plate_layers' => [['plate_type_id' => $this->trivalentChrome->id]],
        ]))->assertOk();

        $controller = app(ReportController::class);
        $filter = new \ReflectionMethod($controller, 'wherePartHasPlateType');
        $filter->setAccessible(true);

        $matches = fn ($chemical) => $filter->invoke($controller, DB::table('parts'), $chemical->id)->pluck('description')->all();

        $this->assertSame(['Part number 1'], $matches($this->brightNi));
        $this->assertSame(['Part number 1'], $matches($this->semiBrightNi));
        $this->assertSame(['Chrome only'], $matches($this->trivalentChrome));
    }

    public function test_export_all_has_cleaned_headers_formatted_date_and_one_column_group_per_layer()
    {
        $this->actingAs($this->user)->postJson('/part', $this->partPayload())->assertOk();

        $response = $this->actingAs($this->user)->get('/report/exportAll');

        $response->assertOk();
        $lines = array_map('str_getcsv', explode("\n", trim($response->getContent())));
        $header = $lines[0];
        $row = array_combine($header, $lines[1]);

        $this->assertNotContains('run_number', $header);
        foreach (['chromatePer', 'chromateTemp', 'chromatePH', 'chromateDiptime',
                  'secondary_topcoatPer', 'secondary_topcoatTemp', 'secondary_topcoatPH', 'secondary_topcoatDiptime',
                  'topCoatPer', 'plateType1', 'plateType2', 'plateThickness2', 'plateThicknessUnit2'] as $column) {
            $this->assertContains($column, $header);
        }
        foreach (['primaryPer', 'primaryPH', 'coatPer', 'coatPH', 'plate_type', 'plateThick'] as $oldColumn) {
            $this->assertNotContains($oldColumn, $header);
        }

        $this->assertSame('03/01/2022', $row['run_start_date']);
        $this->assertSame('Semi Bright Ni', $row['plateType1']);
        $this->assertSame('mils', $row['plateThicknessUnit1']);
        $this->assertSame('2400', $row['plateDiptime1']);
        $this->assertSame('Bright Ni', $row['plateType2']);
        $this->assertSame('microns', $row['plateThicknessUnit2']);
        $this->assertSame('', $row['platePer2']);
    }

    public function test_export_all_pads_parts_with_fewer_layers_and_never_drops_a_part()
    {
        $this->actingAs($this->user)->postJson('/part', $this->partPayload())->assertOk();
        $this->actingAs($this->user)->postJson('/part', $this->partPayload([
            'description' => 'No plate',
            'plate_layers' => [],
        ]))->assertOk();

        $response = $this->actingAs($this->user)->get('/report/exportAll');

        $lines = array_map('str_getcsv', explode("\n", trim($response->getContent())));
        $this->assertCount(3, $lines);
        $this->assertCount(count($lines[0]), $lines[1]);
        $this->assertCount(count($lines[0]), $lines[2]);
        $second = array_combine($lines[0], $lines[2]);
        $this->assertSame('', $second['plateType1']);
        $this->assertSame('', $second['plateType2']);
    }

    public function test_the_plate_partial_lists_every_layer_with_its_thickness_and_unit()
    {
        $this->actingAs($this->user)->postJson('/part', $this->partPayload())->assertOk();
        $part = Part::with('plateLayers.plateType')->first();

        $html = preg_replace('/\s+/', ' ', view('partials.plate-layers', ['part' => $part])->render());

        $this->assertStringContainsString('Semi Bright Ni - 10 mils', $html);
        $this->assertStringContainsString('<br>', $html);
        $this->assertStringContainsString('Bright Ni - 6 microns', $html);
    }

    public function test_the_plate_partial_falls_back_to_the_legacy_plate_when_a_part_has_no_layers()
    {
        $part = Part::create([
            'run_id' => $this->run->id,
            'description' => 'Old part',
            'number' => 1,
            'plate_types_id' => $this->brightNi->id,
            'plateThick' => '4',
            'typePlateThick' => 1,
        ]);

        $html = preg_replace('/\s+/', ' ', view('partials.plate-layers', ['part' => $part->fresh()])->render());

        $this->assertStringContainsString('Bright Ni - 4 microns', $html);
    }

    public function test_the_run_pdf_reports_render_for_a_run_with_multi_layer_parts()
    {
        $this->actingAs($this->user)->postJson('/part', $this->partPayload())->assertOk();

        $this->actingAs($this->user)
            ->get('/run/download/' . $this->run->id . "/'America----Mexico_City'")
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->actingAs($this->user)
            ->get('/run/downloadPlus/' . $this->run->id . "/'America----Mexico_City'")
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_a_part_accepts_exactly_the_maximum_number_of_plate_layers()
    {
        $layers = array_fill(0, Part::MAX_PLATE_LAYERS, ['plate_type_id' => $this->brightNi->id, 'thickness' => '1']);

        $this->actingAs($this->user)->postJson('/part', $this->partPayload(['plate_layers' => $layers]))
            ->assertOk()->assertJson(['ok' => true]);

        $this->assertCount(Part::MAX_PLATE_LAYERS, Part::first()->plateLayers);
    }

    public function test_a_part_with_more_than_the_maximum_plate_layers_is_rejected_and_nothing_is_saved()
    {
        $layers = array_fill(0, Part::MAX_PLATE_LAYERS + 1, ['plate_type_id' => $this->brightNi->id, 'thickness' => '1']);

        $this->actingAs($this->user)->postJson('/part', $this->partPayload(['plate_layers' => $layers]))
            ->assertOk()->assertJson(['ok' => false]);

        $this->assertSame(0, Part::count());
        $this->assertSame(0, \App\Models\PartPlateLayer::count());
    }

    public function test_updating_a_part_over_the_maximum_keeps_its_existing_layers()
    {
        $this->actingAs($this->user)->postJson('/part', $this->partPayload())->assertOk();
        $part = Part::first();
        $layers = array_fill(0, Part::MAX_PLATE_LAYERS + 1, ['plate_type_id' => $this->brightNi->id]);

        $this->actingAs($this->user)->putJson('/part/' . $part->id, $this->partPayload(['id' => $part->id, 'plate_layers' => $layers]))
            ->assertOk()->assertJson(['ok' => false]);

        $this->assertCount(2, $part->fresh()->plateLayers);
    }
}
