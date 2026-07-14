<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;

class AuditManagementController extends Controller
{
    /**
     * Display a listing of the categories and questions.
     */
    public function index()
    {
        $categories = Category::with('questions.options')->orderBy('sort_order', 'asc')->get();
        return view('admin.audit.index', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer',
        ]);

        Category::create($validated);

        return redirect()->back()->with('success', 'Category pillar added successfully!');
    }

    /**
     * Show the form for creating a new question.
     * THIS WAS MISSING AND FIXES YOUR ERROR.
     */
    public function createQuestion()
    {
        $categories = Category::orderBy('sort_order', 'asc')->get();
        return view('admin.audit.create', compact('categories'));
    }

    /**
     * Store a newly created question and its options.
     */
    public function storeQuestion(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'question_text' => 'required|string',
            'is_active' => 'required|boolean',
            'options' => 'required|array|min:1',
            'options.*.option_text' => 'required|string',
            'options.*.score' => 'required|integer',
            'options.*.xp' => 'required|integer',
            'options.*.improvement_suggestion' => 'nullable|string',
        ]);

        \DB::transaction(function () use ($request) {
            $question = Question::create([
                'category_id' => $request->category_id,
                'question_text' => $request->question_text,
                'is_active' => $request->is_active,
            ]);

            foreach ($request->options as $optionData) {
                $question->options()->create($optionData);
            }
        });

        return redirect()->route('admin.audit.index')->with('success', 'Assessment question and choice paths created successfully!');
    }

    /**
     * Show the form for editing the specified question.
     * THIS WAS ALSO NEEDED FOR YOUR EDIT ROUTE.
     */
    public function editQuestion($id)
    {
        $question = Question::with('options', 'category')->findOrFail($id);
        return view('admin.audit.edit', compact('question'));
    }

    /**
     * Update the specified question and its options in storage.
     */
    public function updateQuestion(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $request->validate([
            'question_text' => 'required|string',
            'is_active' => 'required|boolean',
            'options' => 'required|array',
            'options.*.id' => 'nullable|exists:options,id',
            'options.*.option_text' => 'required|string',
            'options.*.score' => 'required|integer',
            'options.*.xp' => 'required|integer',
            'options.*.improvement_suggestion' => 'nullable|string',
        ]);

        \DB::transaction(function () use ($request, $question) {
            $question->update([
                'question_text' => $request->question_text,
                'is_active' => $request->is_active,
            ]);

            foreach ($request->options as $optionData) {
                if (isset($optionData['id'])) {
                    Option::where('id', $optionData['id'])->update(\Arr::except($optionData, ['id']));
                } else {
                    $question->options()->create($optionData);
                }
            }
        });

        return redirect()->route('admin.audit.index')->with('success', 'Question layout configurations adjusted successfully!');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroyQuestion($id)
    {
        $question = Question::findOrFail($id);
        $question->delete(); // This cascade drops answers/options if foreign keys are configured properly

        return redirect()->back()->with('success', 'Question dropped successfully!');
    }

    /**
     * List all customer submitted audits
     */
    public function customerAudits()
    {
        $audits = \App\Models\Audit::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.audit.customer_audits', compact('audits'));
    }

    /**
     * Compile complex logic for final reports
     */
    private function getAuditReportData($id)
    {
        $audit = \App\Models\Audit::with(['answers.question.category', 'answers.question.options', 'answers.option'])
            ->findOrFail($id);

        $maxTotalScore = 0;
        $categoryScores = [];
        $strengths = [];
        $improvements = [];
        $recommendations = [];

        foreach ($audit->answers as $answer) {
            $catId = $answer->question->category_id;
            $catName = $answer->question->category->name;

            if (!isset($categoryScores[$catId])) {
                $categoryScores[$catId] = [
                    'name' => $catName,
                    'score' => 0,
                    'max' => 0,
                ];
            }

            // Find the highest possible score for this question
            $qMaxScore = $answer->question->options->max('score');
            
            $categoryScores[$catId]['score'] += $answer->option->score;
            $categoryScores[$catId]['max'] += $qMaxScore;
            $maxTotalScore += $qMaxScore;

            // Determine Strength vs Area to Improve
            if ($answer->option->score >= $qMaxScore && $qMaxScore > 0) {
                $strengths[] = $answer->question->question_text;
            } else {
                $improvements[] = $answer->question->question_text;
            }

            // Recommendations Priority Logic
            if ($answer->option->improvement_suggestion) {
                // High priority if 0 points, Medium if partial points
                $priority = ($answer->option->score == 0) ? 'HIGH' : 'MEDIUM';
                $recommendations[] = [
                    'category' => $catName,
                    'priority' => $priority,
                    'suggestion' => $answer->option->improvement_suggestion
                ];
            }
        }

        // Calculate Percentages
        foreach ($categoryScores as $key => $cat) {
            $categoryScores[$key]['percentage'] = $cat['max'] > 0 ? round(($cat['score'] / $cat['max']) * 100) : 0;
        }

        $overallPercentage = $maxTotalScore > 0 ? round(($audit->total_score / $maxTotalScore) * 100) : 0;

        // Grade Logic based on LaSirene standard
        if ($overallPercentage >= 90) {
            $grade = 'A — Excellent';
            $gradeDesc = 'Outstanding overall health. You are operating efficiently.';
        } elseif ($overallPercentage >= 70) {
            $grade = 'B+ — Very Good';
            $gradeDesc = 'Good overall health with a few areas needing attention. Focus on your weakest categories.';
        } elseif ($overallPercentage >= 50) {
            $grade = 'C — Needs Improvement';
            $gradeDesc = 'Average health. Core systems need structuring and documentation.';
        } else {
            $grade = 'D — Critical';
            $gradeDesc = 'Critical operational risks identified. Immediate action required.';
        }

        return compact('audit', 'maxTotalScore', 'overallPercentage', 'grade', 'gradeDesc', 'categoryScores', 'strengths', 'improvements', 'recommendations');
    }

    /**
     * View customer report
     */
    public function viewCustomerReport($id)
    {
        $data = $this->getAuditReportData($id);
        return view('customer.audit.report', $data);
    }

    /**
     * Download customer PDF
     */
    public function downloadCustomerPdf($id)
    {
        $data = $this->getAuditReportData($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('customer.audit.pdf', $data);
        return $pdf->download("Business_Audit_Report_{$data['audit']->id}.pdf");
    }
}