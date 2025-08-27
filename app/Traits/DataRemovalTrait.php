<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait DataRemovalTrait
{
    /**
     * Xóa một record
     */
    public function destroy(string $uuid)
    {
        return $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);
    }

    /**
     * Xóa nhiều records
     */
    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);

        return $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
    }
}
