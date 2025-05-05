<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class CommentController extends Controller
{
    // ایجاد کامنت جدید از سمت کاربر
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'last_name' => 'required|string',
            'mobile' => 'required|string',
            'email' => 'required|email',
            'comment' => 'required|string',
        ]);

        $comment = Comment::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'comment' => $request->comment,
            'user_id' => null,
            'parent_id' => null,
            'is_reply' => false,
            'status' => 'pending',
        ]);

        return response()->json($comment, 201);
    }

    // ایجاد پاسخ از طرف ادمین
    public function reply(Request $request, $commentId)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'Only admins can reply to comments.'], 403);
        }

        $reply = Comment::create([
            'comment' => $request->comment,
            'user_id' => Auth::id(),
            'parent_id' => $commentId,
            'is_reply' => true,
            'status' => 'approved',
            'name' => '',
            'last_name' => '',
            'mobile' => '',
            'email' => '',
        ]);

        return response()->json($reply, 201);
    }

    // لیست کامنت‌های قابل نمایش در سایت (approved فقط)
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $comments = Comment::with('replies')
            ->topLevel()
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($comments);
    }

    // لیست همه کامنت‌ها برای پنل مدیریت (با فیلتر و paginate داینامیک)
    public function getAllComments(Request $request)
    {
        $query = Comment::with('replies')->orderBy('created_at', 'desc');

        // فیلترها
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->has('mobile')) {
            $query->where('mobile', 'like', '%' . $request->mobile . '%');
        }
        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $perPage = $request->input('per_page', 10);

        $comments = $query->paginate($perPage);

        return response()->json($comments);
    }

    // دریافت پاسخ‌های یک کامنت خاص
    public function showReplies($commentId)
    {
        $comment = Comment::with('replies')->find($commentId);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found.'], 404);
        }

        return response()->json($comment->replies);
    }

    // تغییر وضعیت کامنت (approve, reject)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
        ]);

        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'Only admins can update comment status.'], 403);
        }

        $comment = Comment::findOrFail($id);
        $comment->status = $request->status;
        $comment->save();

        return response()->json(['message' => "Comment status updated to '{$comment->status}'"]);
    }
    public function destroy($id)
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'Only admins can delete comments.'], 403);
        }

        $comment = Comment::findOrFail($id);

        // حذف ریپلای‌ها اگر کامنت اصلی باشه
        if (!$comment->is_reply) {
            $comment->replies()->delete();
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully.']);
    }
}


