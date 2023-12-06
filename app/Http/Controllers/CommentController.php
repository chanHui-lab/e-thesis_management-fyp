<?php

namespace App\Http\Controllers;
use App\Models\Comment;
// use App\Models\Thesis;
use App\Models\Form_submission;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // public function addCommentToThesis(Request $request, $thesisId)
    // {
    //     $this->validate($request, ['comment_text' => 'required|string']);

    //     Comment::create([
    //         'commentable_id' => $thesisId,
    //         'commentable_type' => Thesis::class,
    //         'student_id' => auth()->user()->id,
    //         'comment_text' => $request->comment_text,
    //     ]);

    //     return redirect()->back()->with('success', 'Comment added successfully.');
    // }

    public function addStuCommentToFormSubmission(Request $request, $formSubmissionId)
    {
        $this->validate($request, ['comment_text' => 'required|string']);

        Comment::create([
            'commentable_id' => $formSubmissionId,
            'commentable_type' => Form_submission::class,
            'student_id' => auth()->user()->id,
            'comment_text' => $request->comment_text,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    public function deleteStuComment($commentId)
    {
        // Find the comment
        $comment = Comment::findOrFail($commentId);

        // Check if the logged-in user is the author of the comment
        if (Auth::id() == $comment->student_id) {
            // Delete the comment
            $comment->delete();
            return redirect()->back()->with('success', 'Comment deleted successfully.');
        }

        // If the logged-in user is not the author, you might want to handle this case differently
        return redirect()->back()->with('error', 'You do not have permission to delete this comment.');
    }

    // public function showThesis($thesisId)
    // {
    //     $thesis = Thesis::findOrFail($thesisId);
    //     $comments = $thesis->comments;

    //     return view('thesis.show', compact('thesis', 'comments'));
    // }

}
