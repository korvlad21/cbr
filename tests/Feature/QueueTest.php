<?php

namespace Tests\Feature;

use App\Jobs\GenerateExchangesJob;
use App\Models\Exchange;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueueTest extends AbstractTest
{

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }


    public function test_generate_exchange_job_is_queued(): void
    {
        GenerateExchangesJob::dispatch();

        Queue::assertPushed(GenerateExchangesJob::class);

    }
}
