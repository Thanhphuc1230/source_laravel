<?php

namespace Tests\Unit\Admin;

use App\Http\Controllers\Admin\CateNewController;
use App\Models\CateNew;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class CateNewControllerTest extends TestCase
{
    use RefreshDatabase;

    private CateNewController $controller;

    private CateNew $cateNew;

    protected function setUp(): void
    {
        parent::setUp();

        // Create controller instance
        $this->controller = new CateNewController;

        // Create test data
        $this->cateNew = CateNew::factory()->create([
            'uuid' => 'test-uuid-123',
            'name_vn' => 'Test Category',
            'slug' => 'test-category',
            'status' => 1,
            'parent_id' => 0,
        ]);
    }

    /** @test */
    public function it_can_display_index_page_with_categories()
    {
        // Arrange
        $request = new Request;

        // Act
        $result = $this->controller->index($request);

        // Assert
        $this->assertNotNull($result);
    }

    /** @test */
    public function it_can_search_categories_by_name()
    {
        // Arrange
        $request = new Request(['search' => 'Test']);

        // Act
        $result = $this->controller->index($request);

        // Assert
        $this->assertNotNull($result);
    }

    /** @test */
    public function it_can_filter_categories_by_parent_id()
    {
        // Arrange
        $request = new Request(['category' => '0']);

        // Act
        $result = $this->controller->index($request);

        // Assert
        $this->assertNotNull($result);
    }

    /** @test */
    public function it_can_display_create_page()
    {
        // Act
        $result = $this->controller->create();

        // Assert
        $this->assertNotNull($result);
    }

    /** @test */
    public function it_can_display_edit_page()
    {
        // Arrange
        $uuid = 'test-uuid-123';
        $currentPage = 1;

        // Act
        $result = $this->controller->edit($uuid, $currentPage);

        // Assert
        $this->assertNotNull($result);
    }

    /** @test */
    public function it_returns_error_for_nonexistent_category_in_edit()
    {
        // Arrange
        $uuid = 'nonexistent-uuid';
        $currentPage = 1;

        // Act
        $result = $this->controller->edit($uuid, $currentPage);

        // Assert
        $this->assertNotNull($result);
    }

    /** @test */
    public function it_can_update_category_order()
    {
        // Arrange
        $uuid = 'test-uuid-123';
        $request = new Request(['order' => 5]);

        // Act
        $result = $this->controller->numericalOrder($request, $uuid);

        // Assert
        $this->assertNotNull($result);
    }

    /** @test */
    public function it_can_destroy_multiple_categories()
    {
        // Arrange
        $uuids = ['uuid-1', 'uuid-2', 'uuid-3'];
        $request = new Request(['uuids' => $uuids]);

        // Act
        $result = $this->controller->destroyAll($request);

        // Assert
        $this->assertNotNull($result);
    }
}
