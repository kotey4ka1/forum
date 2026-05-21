<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdMaterial;
use App\Models\AdStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAdController extends Controller
{
    public function index(Request $request)
    {
        $query = AdMaterial::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('placement_key')) {
            $query->where('placement_key', $request->placement_key);
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        $ads = $query->orderBy('placement_key')->orderBy('weight', 'desc')->paginate(20);
        return view('admin.ads.index', compact('ads'));
    }

    public function create()
    {
        return view('admin.ads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'content' => 'required|image|mimes:jpeg,png,jpg,gif|max:9120',
            'target_url' => 'required|url',
            'placement_key' => 'required|in:sidebar,between_posts',
            'weight' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $file = $request->file('content');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('banners', $filename, 'public');

        AdMaterial::create([
            'name' => $request->name,
            'type' => 'banner',
            'content' => $path,
            'target_url' => $request->target_url,
            'placement_key' => $request->placement_key,
            'weight' => $request->weight,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.ads.index')->with('success', 'Баннер создан');
    }

    public function edit(AdMaterial $ad)
    {
        return view('admin.ads.edit', compact('ad'));
    }

    public function update(Request $request, AdMaterial $ad)
    {
        $request->validate([
            'name' => 'required|max:255',
            'content' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:9120',
            'target_url' => 'required|url',
            'placement_key' => 'required|in:sidebar,between_posts',
            'weight' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $ad->name = $request->name;
        $ad->target_url = $request->target_url;
        $ad->placement_key = $request->placement_key;
        $ad->weight = $request->weight;
        $ad->is_active = $request->has('is_active');

        if ($request->hasFile('content')) {
            if ($ad->content && Storage::disk('public')->exists($ad->content)) {
                Storage::disk('public')->delete($ad->content);
            }
            $file = $request->file('content');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('banners', $filename, 'public');
            $ad->content = $path;
        }

        $ad->save();

        return redirect()->route('admin.ads.index')->with('success', 'Баннер обновлён');
    }

    public function destroy(AdMaterial $ad)
    {
        if ($ad->content && Storage::disk('public')->exists($ad->content)) {
            Storage::disk('public')->delete($ad->content);
        }
        $ad->delete();
        return back()->with('success', 'Баннер удалён');
    }

    public function stats(AdMaterial $ad)
    {
        $impressions = AdStats::where('material_id', $ad->id)->where('event_type', 'impression')->count();
        $clicks = AdStats::where('material_id', $ad->id)->where('event_type', 'click')->count();
        $ctr = $impressions ? round($clicks / $impressions * 100, 2) : 0;
        return view('admin.ads.stats', compact('ad', 'impressions', 'clicks', 'ctr'));
    }
}
