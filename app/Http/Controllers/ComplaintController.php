<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'complaintable_id' => 'required|integer',
            'complaintable_type' => 'required|in:post,comment',
            'reason' => 'required|string|min:5|max:1000',
        ]);

        $type = $request->complaintable_type;
        $modelClass = $type === 'post' ? 'App\Models\Post' : 'App\Models\Comment';

        Complaint::create([
            'user_id' => Auth::id(),
            'complaintable_id' => $request->complaintable_id,
            'complaintable_type' => $modelClass,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Жалоба отправлена');
    }
}
