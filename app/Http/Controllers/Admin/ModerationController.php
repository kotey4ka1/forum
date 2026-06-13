<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\SupportRequest;
use App\Models\KnowledgeBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ModerationController extends Controller
{
    public function index(Request $request)
    {
        $complaintsQuery = Complaint::with(['user', 'complaintable']);
        $supportQuery = SupportRequest::with('user');

        // Фильтры для жалоб
        if ($request->filled('complaint_status')) {
            $complaintsQuery->where('status', $request->complaint_status);
        }
        if ($request->filled('complaint_date')) {
            if ($request->complaint_date == 'today') {
                $complaintsQuery->whereDate('created_at', today());
            } elseif ($request->complaint_date == 'week') {
                $complaintsQuery->where('created_at', '>=', now()->subDays(7));
            } elseif ($request->complaint_date == 'month') {
                $complaintsQuery->where('created_at', '>=', now()->subMonth());
            }
        }
        if ($request->filled('complaint_search')) {
            $search = $request->complaint_search;
            $complaintsQuery->where(function ($q) use ($search) {
                $q->where('reason', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Фильтры для обращений
        if ($request->filled('support_status')) {
            $supportQuery->where('status', $request->support_status);
        }
        if ($request->filled('support_search')) {
            $search = $request->support_search;
            $supportQuery->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $complaints = $complaintsQuery->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());
        $supportRequests = $supportQuery->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

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

    // Восстановление контента после жалобы
    public function restoreComplaint(Complaint $complaint)
    {
        $complaintable = $complaint->complaintable;
        if ($complaintable && $complaintable->trashed()) {
            $complaintable->restore();
        }
        return back()->with('success', 'Контент восстановлен.');
    }

}
