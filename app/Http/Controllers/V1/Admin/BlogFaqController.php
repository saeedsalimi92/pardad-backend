<?php

namespace App\Http\Controllers\V1\Admin;

use App\Models\BlogFaq;
use Controller;
use Illuminate\Http\Request;

class BlogFaqController extends Controller
{
    public function getAllFaq(Request $request)
    {
        $query = BlogFaq::with('blog');

        if ($request->has('parent_id')) {
            $query->where('parent_id', $request->blog_id);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $perPage = $request->input('per_page', 10);
        return response()->json($query->paginate($perPage));
    }

    public function index(Request $request)
    {
        $faqs = BlogFaq::where('parent_id', $request->parent_id)->where('is_active', true)->get();
        return response()->json($faqs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'required|exists:blogs,id',
            'question' => 'required|string',
            'answer' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $faq = BlogFaq::create($validated);
        return response()->json($faq, 201);
    }

    public function update(Request $request, $id)
    {
        $faq = BlogFaq::findOrFail($id);

        $validated = $request->validate([
            'question' => 'string',
            'answer' => 'string',
            'is_active' => 'boolean',
        ]);

        $faq->update($validated);
        return response()->json($faq);
    }

    public function destroy($id)
    {
        $faq = BlogFaq::findOrFail($id);
        $faq->delete();
        return response()->json(['message' => 'FAQ deleted successfully.']);
    }

    public function show($id)
    {

        $faq = BlogFaq::with('blog')->findOrFail($id);
        return response()->json($faq);
    }
}
