<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportRequestController extends Controller
{
    public function index()
    {
        $requests = SupportRequest::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('support.index', compact('requests'));
    }

    public function create()
    {
        return view('support.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'type' => 'required|in:consultation,other',
            'content' => 'required|string|min:5',
        ]);

        SupportRequest::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'type' => $request->type,
            'content' => $request->content,
            'status' => 'new', // если в таблице есть поле status
        ]);

        return redirect()->route('support.index')->with('success', 'Обращение отправлено');
    }

    public function show(SupportRequest $support)
    {
        if ($support->user_id != Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isModerator()) {
            abort(403);
        }
        return view('support.show', compact('support'));
    }
    public function destroy(SupportRequest $supportRequest)
    {
        if ($supportRequest->user_id != Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $supportRequest->delete();
        return redirect()->route('support.index')->with('success', 'Обращение удалено');
    }
    
    public function __construct()
{
    $this->middleware(['auth', 'verified'])->except(['index', 'show']);
}
}
