<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Sermon;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicWebPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_landing_page_renders_successfully_with_aocc_data(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('AOCC');
        $response->assertSee('Welcome Home');
        $response->assertSee('Kejadian 1 : 1');
        $response->assertSee('Pakdhe Ardi');
        $response->assertSee('Filosofi Pelayanan');
        $response->assertSee('BETHEL');
        $response->assertSee('Ibadah Raya');
        $response->assertSee('Youth Community');
    }

    public function test_sermons_archive_renders_successfully(): void
    {
        $response = $this->get(route('sermons.index'));

        $response->assertStatus(200);
        $response->assertSee('Arsip Khotbah AOCC');
        $response->assertSee('Menemukan Kedamaian di Tengah Badai Hidup');
    }

    public function test_sermon_search_filters_results(): void
    {
        $response = $this->get(route('sermons.index', ['q' => 'Kedamaian']));

        $response->assertStatus(200);
        $response->assertSee('Menemukan Kedamaian di Tengah Badai Hidup');
    }

    public function test_sermon_detail_page_renders(): void
    {
        $sermon = Sermon::first();
        $this->assertNotNull($sermon);

        $response = $this->get(route('sermons.show', $sermon->slug));

        $response->assertStatus(200);
        $response->assertSee($sermon->title);
        $response->assertSee($sermon->speaker_name);
    }

    public function test_events_archive_renders_successfully(): void
    {
        $response = $this->get(route('events.index'));

        $response->assertStatus(200);
        $response->assertSee('Agenda Acara AOCC');
        $response->assertSee('Night of Worship');
    }

    public function test_event_detail_page_renders(): void
    {
        $event = Event::first();
        $this->assertNotNull($event);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertStatus(200);
        $response->assertSee($event->title);
        $response->assertSee($event->venue);
    }
}
