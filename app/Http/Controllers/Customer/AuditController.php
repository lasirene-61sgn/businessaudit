<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Question;
use App\Models\Option;
use App\Models\Audit;
use App\Models\AuditAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AuditController extends Controller
{
    /**
     * Show Customer Audit Dashboard
     */
    public function dashboard()
    {
        // Get all dynamic categories to render total counts
        $categories = Category::withCount('questions')->orderBy('sort_order', 'asc')->get();

        // Find existing draft to allow 'Load Saved Draft' feature
        $savedDraft = Audit::where('user_id', auth()->id())
            ->where('status', 'draft')
            ->latest()
            ->first();

        // Get completed audit history lists
        $completedAudits = Audit::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->latest()
            ->get();

        return view('customer.audit.dashboard', compact('categories', 'savedDraft', 'completedAudits'));
    }

    /**
     * Step 1: Initialize/Start Audit (Business Information Form)
     */
    public function startAudit(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'business_type' => 'nullable|string',
            'industry' => 'nullable|string',
            'years_in_operation' => 'nullable|integer',
            'no_of_employees' => 'nullable|integer',
            'auditor_name' => 'nullable|string',
            'audit_date' => 'required|date',
            'additional_notes' => 'nullable|string',
        ]);

        // Create initial draft row session trace
        $audit = Audit::create(array_merge($validated, [
            'user_id' => auth()->id(),
            'status' => 'draft'
        ]));

        // Direct to Step 2 (First active category sequence)
        $firstCategory = Category::orderBy('sort_order', 'asc')->first();
        return redirect()->route('customer.audit.step', [$audit->id, $firstCategory->id]);
    }

    /**
     * Render evaluation steps sequentially
     */
    public function showStep($auditId, $categoryId)
    {
        $audit = Audit::where('user_id', auth()->id())->findOrFail($auditId);
        $category = Category::with('questions.options')->findOrFail($categoryId);

        // Get total steps framework tracking lists
        $allCategories = Category::orderBy('sort_order', 'asc')->get();
        $currentStepIndex = $allCategories->pluck('id')->search($category->id) + 2; // Step 1 is Business Info
        $totalSteps = $allCategories->count() + 1;

        // Fetch existing answers if user is reloading a draft step
        $savedAnswers = AuditAnswer::where('audit_id', $audit->id)
            ->pluck('option_id', 'question_id')
            ->toArray();

        $savedNotes = AuditAnswer::where('audit_id', $audit->id)
            ->pluck('notes', 'question_id')
            ->toArray();

        return view('customer.audit.step', compact(
            'audit',
            'category',
            'allCategories',
            'currentStepIndex',
            'totalSteps',
            'savedAnswers',
            'savedNotes'
        ));
    }

    public function saveStep(Request $request, $auditId, $categoryId)
    {
        $audit = Audit::where('user_id', auth()->id())->findOrFail($auditId);
        $category = \App\Models\Category::with('questions')->findOrFail($categoryId);

        $request->validate([
            'answers' => $category->questions->count() > 0 ? 'required|array' : 'nullable|array',
            'answers.*' => 'required|exists:options,id',
            'notes' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $audit) {
            if ($request->has('answers') && is_array($request->answers)) {
                foreach ($request->answers as $questionId => $optionId) {
                    \App\Models\AuditAnswer::updateOrCreate(
                        ['audit_id' => $audit->id, 'question_id' => $questionId],
                        ['option_id' => $optionId, 'notes' => $request->notes[$questionId] ?? null]
                    );
                }
            }
        });

        // Find the next sequential category
        $allCategories = \App\Models\Category::orderBy('sort_order', 'asc')->get();
        $currentIndex = $allCategories->pluck('id')->search((int)$categoryId);

        if (isset($allCategories[$currentIndex + 1])) {
            // SUCCESS: Redirect to next CUSTOMER step route
            return redirect()->route('customer.audit.step', [$audit->id, $allCategories[$currentIndex + 1]->id]);
        }

        // Wrap up metrics and head to the customer report dashboard
        $answers = \App\Models\AuditAnswer::with('option')->where('audit_id', $audit->id)->get();
        $totalScore = $answers->sum('option.score');
        $totalXp = $answers->sum('option.xp');

        $audit->update([
            'status' => 'completed',
            'total_score' => $totalScore,
            'total_xp' => $totalXp
        ]);

        return redirect()->route('customer.audit.report', $audit->id)->with('success', 'Audit compiled successfully!');
    }

    /**
     * Final calculations processing pipeline
     */
    private function finalizeAudit(Audit $audit)
    {
        DB::transaction(function () use ($audit) {
            // Sum points metrics directly via relation loops mapping matrix
            $answers = AuditAnswer::with('option')->where('audit_id', $audit->id)->get();

            $totalScore = $answers->sum('option.score');
            $totalXp = $answers->sum('option.xp');

            $audit->update([
                'status' => 'completed',
                'total_score' => $totalScore,
                'total_xp' => $totalXp
            ]);
        });

        return redirect()->route('customer.audit.report', $audit->id)->with('success', 'Audit compiled successfully!');
    }

    /**
     * Compile complex logic for final reports (Grades, Strengths, Categories)
     */
    private function getAuditReportData($id)
    {
        $audit = Audit::with(['answers.question.category', 'answers.question.options', 'answers.option'])
            ->where('user_id', auth()->id())
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
     * View final dynamic summary reports view page inside panel
     */
    public function showReport($id)
    {
        $data = $this->getAuditReportData($id);
        return view('customer.audit.report', $data);
    }

    /**
     * Download Document PDF Stream Output
     */
    public function downloadPdf($id)
    {
        $data = $this->getAuditReportData($id);
        $pdf = Pdf::loadView('customer.audit.pdf', $data);
        return $pdf->download("Business_Audit_Report_{$data['audit']->id}.pdf");
    }
}
