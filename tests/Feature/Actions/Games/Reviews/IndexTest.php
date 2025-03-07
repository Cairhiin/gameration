<?php

namespace Tests\Feature\Actions\Games\Reviews;

use Tests\TestCase;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Foundation\Testing\WithFaker;

class IndexTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
