<?php

namespace App\Traits\Admin;

use Illuminate\Http\Request;

/**
 * CRUD Operations Trait for Admin Controllers
 * 
 * Provides common CRUD functionality to reduce code duplication
 * across admin controllers that follow similar patterns.
 */
trait CrudOperationsTrait
{
    /**
     * Show create form
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem ?? 'mục';
        
        return $this->view_admin('detail', $data);
    }

    /**
     * Store a newly created resource
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function performStore(Request $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');

        // Handle image if saveImage method exists
        if (method_exists($this, 'saveImage')) {
            $data['image'] = $this->saveImage($request);
        }

        $item = $this->repository->create($data);
        toast('Thêm ' . ($this->nameItem ?? 'mục') . ' thành công', 'success');

        // Dispatch event if available
        if (property_exists($this, 'eventClass') && class_exists($this->eventClass)) {
            event(new $this->eventClass($item, 'created'));
        }

        return $this->handleRedirect($request);
    }

    /**
     * Show edit form
     * 
     * @param string $uuid
     * @param mixed $currentPage
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    protected function performEdit($uuid, $currentPage = null)
    {
        $item = $this->repository->findByUuid($uuid);

        if (!$item) {
            toast('Không tìm thấy ' . ($this->nameItem ?? 'mục'), 'error');
            return back();
        }

        $data = [
            'page' => $item,
            'action' => 'edit',
            'nameItem' => $this->nameItem ?? 'mục',
            'currentPage' => $currentPage,
        ];

        // Add imageFolder if exists
        if (isset($this->imageFolder)) {
            $data['imageFolder'] = $this->imageFolder;
        }

        return $this->view_admin('detail', $data);
    }

    /**
     * Update the specified resource
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function performUpdate(Request $request, string $uuid)
    {
        $current = $this->repository->findByUuid($uuid);
        
        if (!$current) {
            toast('Không tìm thấy ' . ($this->nameItem ?? 'mục'), 'error');
            return back();
        }

        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');

        // Handle image update if updateImage method exists
        if (method_exists($this, 'updateImage')) {
            $data['image'] = $this->updateImage($request, $current);
        }

        $this->repository->update($data, $uuid);
        toast('Cập nhật ' . ($this->nameItem ?? 'mục') . ' thành công', 'success');

        // Dispatch event if available
        if (property_exists($this, 'eventClass') && class_exists($this->eventClass)) {
            event(new $this->eventClass($current, 'updated'));
        }

        return $this->handleRedirect($request, $request->currentPage ?? null);
    }

    /**
     * Remove the specified resource
     * 
     * @param string $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function performDestroy(string $uuid)
    {
        $item = $this->repository->findByUuid($uuid);

        if (!$item) {
            toast('Không tìm thấy ' . ($this->nameItem ?? 'mục'), 'error');
            return back();
        }

        // Handle image deletion if deleteImage method exists
        if (method_exists($this, 'deleteImage')) {
            $this->deleteImage($item);
        }

        $this->repository->delete($uuid);
        toast('Xóa ' . ($this->nameItem ?? 'mục') . ' thành công', 'success');

        // Dispatch event if available
        if (property_exists($this, 'eventClass') && class_exists($this->eventClass)) {
            event(new $this->eventClass($item, 'deleted'));
        }

        return $this->route_admin('index');
    }

    /**
     * Delete multiple resources
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function performDestroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);

        if (empty($uuids)) {
            toast('Không có mục nào được chọn để xóa.', 'error');
            return back();
        }

        $items = $this->repository->findByUuids($uuids);

        // Handle image deletion for each item
        if (method_exists($this, 'deleteImage')) {
            foreach ($items as $item) {
                $this->deleteImage($item);
            }
        }

        $this->repository->deleteByUuids($uuids);
        toast('Xóa ' . count($uuids) . ' ' . ($this->nameItem ?? 'mục') . ' thành công', 'success');

        return $this->route_admin('index');
    }

    /**
     * Handle redirect after store/update
     * 
     * @param \Illuminate\Http\Request $request
     * @param mixed $currentPage
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleRedirect(Request $request, $currentPage = null)
    {
        if ($request->has('return_back')) {
            return back();
        }
        
        if ($request->has('return_list')) {
            return $this->route_admin('index');
        }

        // Default behavior
        return back();
    }
}
