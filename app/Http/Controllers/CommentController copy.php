<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        // $this->middleware(function ($request, $next) {
        //     if (!auth()->user()->hasAnyRole(['Admin', 'Super Admin'])) {
        //         session()->flash('akses_ditolak', true);
        //         return redirect()->route('dashboard');
        //     }
        //     return $next($request);
        // });
    }
    /**
     * Display comments management page
     */
    public function comments(Request $request)
    {
        $query = Comment::with('article')->latest();

        // Filter by approval status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'pending') {
                $query->where('approved', false);
            } elseif ($request->status == 'approved') {
                $query->where('approved', true);
            }
        }

        $comments = $query->paginate(15);
        $pendingCount = Comment::where('approved', false)->count();
        $pendingCommentsCount = $this->getPendingCommentsCount();

        return view('comments.index', compact('comments', 'pendingCount', 'pendingCommentsCount'));
    }

    /**
     * Approve a comment
     */
    public function approveComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['approved' => true]);

        return redirect()->back()->with('success', 'Komentar berhasil disetujui.');
    }
    public function unapproveComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->approved = false;
        $comment->save();

        return back()->with('success', 'Komentar berhasil dibatalkan approve.');
    }
    /**
     * Reject/delete a comment
     */
    public function rejectComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'news_id' => 'required|exists:news,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string|min:5|max:1000',
        ]);

        Comment::create([
            'news_id' => $request->news_id,
            'name' => $request->name,
            'email' => $request->email,
            'content' => $request->content,
            'approved' => false,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim dan menunggu persetujuan admin.');
    }
}
