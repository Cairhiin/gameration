<?php

namespace Tests\Feature\Actions\Games\Image;

use Tests\TestCase;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;

class UpdateTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
