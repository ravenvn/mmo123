<?php

namespace App\Http\Controllers;

use Auth;
use App\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function video1s()
    {
        
        return view('warehouse.video1s');
    }

    public function getWarehousesVideo1s()
    {
        $warehouses = Auth::user()->warehouses()->with('videos')->whereType(Warehouse::VIDEO_1S)->latest()->get();

        return response()->json(['warehouses' => $warehouses]);
    }

    public function storeWarehouseVideo1s(Request $request)
    {
        try {
            $videoIds = array_unique(explode("\n", $request->videoIds));
            $existWarehouse = Auth::user()->warehouses()->whereName(trim($request->name))->first();
            if ($existWarehouse) {
                return response()->json(['status' => 'error', 'message' => 'Tên kho đã tồn tại.']);
            }

            $warehouse = Auth::user()->warehouses()->create([
                'name' => trim($request->name),
                'type' => Warehouse::VIDEO_1S,
            ]);

            foreach ($videoIds as $videoId) {
                $warehouse->videos()->create([
                    'video_id' => $videoId,
                ]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }
    
    public function updateWarehouseVideo1s(Request $request)
    {
        try {
            $videoIds = array_unique(explode("\n", $request->videoIds));
            $warehouse = Auth::user()->warehouses()->find($request->id);
            if ($warehouse->name != $request->name) {
                $warehouse->update([
                    'name' => $request->name,
                ]);
            }
            $currentVideoIds = $warehouse->videos()->pluck('video_id')->toArray();
            $shouldDeleteVideoIds = array_diff($currentVideoIds, $videoIds);
            $newVideoIds = array_diff($videoIds, $currentVideoIds);
            $warehouse->videos()->whereIn('video_id', $shouldDeleteVideoIds)->delete();
            foreach ($newVideoIds as $videoId) {
                $warehouse->videos()->create([
                    'video_id' => $videoId,
                ]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    public function deleteWarehouseVideo1s(Request $request)
    {
        try {
            $warehouse = Auth::user()->warehouses()->find($request->id);
            $warehouse->delete();

            return response()->json(['status' => 'success']);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }
    
    public function massDeleteWarehouseVideo1s(Request $request)
    {
        try {
            $warehouses = Auth::user()->warehouses()->whereIn('id', $request->ids);
            $warehouses->delete();

            return response()->json(['status' => 'success']);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }
}
