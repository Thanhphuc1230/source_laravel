<?php

namespace App\Services;

use Illuminate\Support\Facades\Response;

class StatusManagementService
{
    public function updateStatus($uuid, $status, $name, $model)
    {
        $updated = $model::where('uuid', $uuid)->update([$name => $status]);
        
        if ($updated) {
            $mess = $status == 1 ? 'Kích hoạt' : 'Tắt';
            toast($mess . ' thành công');
            return response()->json(['message' => $mess . ' ' . $name . ' thành công']);
        }
        
        toast('Không tìm thấy ' . $name, 'error');
        return response()->json(['message' => 'Không có bản ghi nào được cập nhật'], 400);
    }

    public function updateStt($request, $uuid, $model)
    {
        $item = $model::where('uuid', $uuid)->first();

        if ($item) {
            $item->stt = $request->input('stt');
            $item->stt = $request->input('stt');
            $item->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
