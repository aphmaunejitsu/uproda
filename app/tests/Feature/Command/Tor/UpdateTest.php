<?php

namespace Tests\Feature\Command\Tor;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @group tor:update
 * @group Command
 * @group Tor
 * @group UpdateTest
 */
class UpdateTest extends TestCase
{
    public function testNotFound()
    {
        Http::fake([
            'check.torproject.org/exit-addresses' => Http::response("aaaaaaaa2345", 200),
        ]);

        $this->artisan('tor:update')
             ->expectsOutput('Start update tor exit address')
             ->expectsOutput('no update tor exit address')
             ->expectsOutput('End update tor exit address')
             ->assertExitCode(1);
    }

    public function testUpdate()
    {
        Http::fake([
            'check.torproject.org/exit-addresses' => Http::response("ExitAddress 10.11.12.13 1234\nExitAddress 20.21.22.23 2345", 200),
        ]);

        $this->artisan('tor:update')
             ->expectsOutput('Start update tor exit address')
             ->expectsOutput('update 2 tor exit addresses')
             ->expectsOutput('End update tor exit address')
             ->assertExitCode(0);
    }
}
