<?php

namespace App\Services;

use Illuminate\Support\Facades\Response;

class ModelToggleService
{
    public function toggleModelStatus($uuid, $status, $fieldName, $model)
    {
        $updated = $model::where('uuid', $uuid)->update([$fieldName => $status]);
        
        if ($updated) {
            $mess = $status == 1 ? 'Kích hoạt' : 'Tắt';
            toast($mess . ' thành công');
            return response()->json(['message' => $mess . ' ' . $fieldName . ' thành công']);
        }
        
        toast('Không tìm thấy ' . $fieldName, 'error');
        return response()->json(['message' => 'Không có bản ghi nào được cập nhật'], 400);
    }

    public function updateModelOrder($request, $uuid, $model)
    {
        $item = $model::where('uuid', $uuid)->first();

        if ($item) {
            $item->stt = $request->input('stt');
            $item->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
