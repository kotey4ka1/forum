<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\SupportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModerationController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['user', 'complaintable'])->orderBy('created_at', 'desc')->paginate(10);
        $supportRequests = SupportRequest::with('user')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.moderation.index', compact('complaints', 'supportRequests'));
    }

    // Принятие жалобы → удаляем пост или комментарий
    public function resolveComplaint(Request $request, Complaint $complaint)
    {
        $request->validate([
            'moderator_comment' => 'nullable|string|max:1000',
        ]);

        $complaintable = $complaint->complaintable;
        if ($complaintable) {
            $complaintable->delete();
        }

        $complaint->update([
            'status' => 'reviewed',
            'moderator_id' => Auth::id(),
            'moderator_comment' => $request->moderator_comment,
        ]);

        return back()->with('success', 'Жалоба принята, контент удалён.');
    }

    // Отклонение жалобы
    public function rejectComplaint(Request $request, Complaint $complaint)
    {
        $complaint->update([
            'status' => 'rejected',
            'moderator_id' => Auth::id(),
            'moderator_comment' => $request->moderator_comment,
        ]);

        return back()->with('success', 'Жалоба отклонена, контент оставлен.');
    }

    // Ответ на обращение в поддержку
    public function respondSupport(Request $request, SupportRequest $supportRequest)
    {
        $request->validate([
            'response' => 'required|string|min:5',
        ]);

        $supportRequest->update([
            'response' => $request->response,
            'status' => 'closed',
            'assigned_moderator_id' => Auth::id(),
            'responded_at' => now(),
            'closed_at' => now(),
        ]);

        return back()->with('success', 'Ответ отправлен');
    }
}
