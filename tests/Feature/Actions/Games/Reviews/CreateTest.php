<?php

namespace Tests\Feature\Actions\Games\Reviews;

use Tests\TestCase;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class CreateTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
