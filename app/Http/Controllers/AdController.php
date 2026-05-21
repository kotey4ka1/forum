<?php

namespace App\Http\Controllers;

use App\Models\AdMaterial;
use App\Models\AdStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdController extends Controller
{
    public function impression(Request $request, $materialId)
    {
        try {
            $material = AdMaterial::findOrFail($materialId);
            $userId = Auth::id();
            $ip = $request->ip();

            // Проверяем, был ли показ за последние 24 часа
            $existing = AdStats::where('material_id', $material->id)
                ->where('event_type', 'impression')
                ->where(function($query) use ($userId, $ip) {
                    if ($userId) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->where('ip_address', $ip);
                    }
                })
                ->where('created_at', '>=', now()->subHours(24))
                ->exists();

            if (!$existing) {
                AdStats::create([
                    'material_id' => $material->id,
                    'user_id' => $userId,
                    'event_type' => 'impression',
                    'ip_address' => $ip,
                ]);
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Ad impression error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function click($materialId)
    {
        try {
            $material = AdMaterial::findOrFail($materialId);
            AdStats::create([
                'material_id' => $material->id,
                'user_id' => Auth::id(),
                'event_type' => 'click',
                'ip_address' => request()->ip(),
            ]);
            return redirect($material->target_url);
        } catch (\Exception $e) {
            Log::error('Ad click error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ошибка перехода');
        }
    }
}
