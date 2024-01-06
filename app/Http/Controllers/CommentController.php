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

    public function addCommentToFormSubmission(Request $request, $formSubmissionId)
    {
        $this->validate($request, ['comment_text' => 'required|string']);

        $roleAs = auth()->user()->role_as;
        $userId = auth()->user()->id;

        $commentData = [
            'commentable_id' => $formSubmissionId,
            'commentable_type' => Form_submission::class,
            'comment_text' => $request->comment_text,
        ];

        if ($roleAs == 0 || $roleAs == 1) {
            $commentData['lecturer_id'] = $userId;
        } elseif ($roleAs == 2) {
            $commentData['student_id'] = $userId;
        }

        Comment::create($commentData);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }



    public function deleteStuThesisComment($commentId)
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

    // public function addStuCommentToFormSubmission(Request $request, $formSubmissionId)
    // {
    //     $this->validate($request, ['comment_text' => 'required|string']);

    //     Comment::create([
    //         'commentable_id' => $formSubmissionId,
    //         'commentable_type' => Form_submission::class,
    //         'student_id' => auth()->user()->id,
    //         'comment_text' => $request->comment_text,
    //     ]);

    //     return redirect()->back()->with('success', 'Comment added successfully.');
    // }

    // public function addLectCommentToFormSubmission(Request $request, $formSubmissionId)
    // {
    //     $this->validate($request, ['comment_text' => 'required|string']);

    //     Comment::create([
    //         'commentable_id' => $formSubmissionId,
    //         'commentable_type' => Form_submission::class,
    //         'lecturer_id' => auth()->user()->id,
    //         'comment_text' => $request->comment_text,
    //     ]);

    //     return redirect()->back()->with('success', 'Comment added successfully.');
    // }

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

    public function deleteLectComment($commentId)
    {
        // Find the comment
        $comment = Comment::findOrFail($commentId);

        // Check if the logged-in user is the author of the comment
        if (Auth::id() == $comment->lecturer_id) {
            // Delete the comment
            $comment->delete();
            return redirect()->back()->with('success', 'Comment deleted successfully.');
        }

        // If the logged-in user is not the author, you might want to handle this case differently
        return redirect()->back()->with('error', 'You do not have permission to delete this comment.');
    }

    public function deleteLect2Comment($commentId)
    {
        // Find the comment
        $comment = Comment::findOrFail($commentId);

        // Check if the logged-in user is the author of the comment
        if (Auth::id() == $comment->lecturer_id) {
            // Delete the comment
            $comment->delete();
            return redirect()->back()->with('success', 'Comment deleted successfully.');
        }

        // If the logged-in user is not the author, you might want to handle this case differently
        return redirect()->back()->with('error', 'You do not have permission to delete this comment.');
    }

    // ---------------------------------------------------------------------------------------------------------------------------
    // public function addStuCommentToThesisSubmission(Request $request, $thesisSubmissionId)
    // {
    //     $this->validate($request, ['comment_text' => 'required|string']);

    //     Comment::create([
    //         'commentable_id' => $thesisSubmissionId,
    //         'commentable_type' => Thesis_submission::class,
    //         'student_id' => auth()->user()->id,
    //         'comment_text' => $request->comment_text,
    //     ]);

    //     return redirect()->back()->with('success', 'Comment added successfully.');
    // }

    public function addCommentToThesisSubmission(Request $request, $thesisSubmissionId)
    {
        $this->validate($request, ['comment_text' => 'required|string']);
        $roleAs = auth()->user()->role_as;
        $userId = auth()->user()->id;

        $commentData = [
            'commentable_id' => $thesisSubmissionId,
            'commentable_type' => Thesis_submission::class,
            'comment_text' => $request->comment_text,
        ];

        if ($roleAs == 0 || $roleAs == 1) {
            $commentData['lecturer_id'] = $userId;
        } elseif ($roleAs == 2) {
            $commentData['student_id'] = $userId;
        }

        Comment::create($commentData);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }
    public function addCommentToProposalSubmission(Request $request, $proposalSubmissionId)
    {
        // dd($proposalSubmissionId);
        $this->validate($request, ['comment_text' => 'required|string']);
        $roleAs = auth()->user()->role_as;
        $userId = auth()->user()->id;

        $commentData = [
            'commentable_id' => $proposalSubmissionId,
            'commentable_type' => Proposal_submission::class,
            'comment_text' => $request->comment_text,
        ];

        if ($roleAs == 0 || $roleAs == 1) {
            $commentData['lecturer_id'] = $userId;
        } elseif ($roleAs == 2) {
            $commentData['student_id'] = $userId;
        }

        Comment::create($commentData);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }



    public function addCommentToSlideSubmission(Request $request, $slideSubmissionId)
    {
        // dd($thesisSubmissionId);
        $this->validate($request, ['comment_text' => 'required|string']);
        $roleAs = auth()->user()->role_as;
        $userId = auth()->user()->id;

        $commentData = [
            'commentable_id' => $slideSubmissionId,
            'commentable_type' => Slide_submission::class,
            'comment_text' => $request->comment_text,
        ];

        if ($roleAs == 0 || $roleAs == 1) {
            $commentData['lecturer_id'] = $userId;
        } elseif ($roleAs == 2) {
            $commentData['student_id'] = $userId;
        }

        Comment::create($commentData);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

}
