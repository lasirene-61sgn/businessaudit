<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Business Health Report</title>
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #111827; 
            font-size: 11px; 
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        
        /* Layout Utilities */
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        .page-break { page-break-before: always; }

        /* Typography */
        h1 { font-size: 24px; margin: 0; font-weight: bold; color: #111827; }
        h2 { font-size: 18px; margin: 0 0 5px 0; font-weight: bold; }
        h3 { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; border-bottom: 2px solid #f97316; padding-bottom: 4px; color: #111827; display: inline-block; font-weight: bold;}
        .text-orange { color: #f97316; }
        .text-blue { color: #2563eb; }
        .text-gray { color: #6b7280; }
        .text-green { color: #15803d; }

        /* Header block */
        .brand-header {
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .brand-title { font-size: 20px; font-weight: bold; margin: 0; }
        .brand-subtitle { font-size: 9px; color: #9ca3af; text-transform: uppercase; letter-spacing: 2px; margin: 0; }

        /* Business Info Box */
        .info-box {
            border: 1px solid #fcd34d;
            background-color: #fffbeb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .info-box h2 { font-size: 18px; margin-bottom: 10px; }
        .info-list { color: #4b5563; }
        .info-list span { margin-right: 15px; }
        .info-list strong { color: #111827; }

        /* Grade Box */
        .grade-container { margin-bottom: 40px; }
        .grade-box-left {
            border: 1px solid #fcd34d;
            border-radius: 8px;
            text-align: center;
            padding: 20px;
            width: 35%;
        }
        .grade-score { font-size: 45px; font-weight: bold; color: #2563eb; line-height: 1; margin-bottom: 5px; }
        .grade-max { font-size: 14px; font-weight: bold; color: #9ca3af; margin-bottom: 10px; }
        .grade-text { font-size: 12px; font-weight: bold; color: #111827; }
        .grade-percent { font-size: 9px; color: #6b7280; }

        .grade-box-right {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            width: 60%;
        }
        .grade-desc { font-size: 12px; color: #4b5563; margin-top: 10px; margin-bottom: 20px; }
        
        .xp-bar-bg {
            background-color: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 12px;
            height: 24px;
            width: 100%;
            position: relative;
        }
        .xp-bar-fill {
            background-color: #fef3c7;
            height: 100%;
            border-radius: 12px;
        }
        .xp-text {
            position: absolute;
            top: 5px;
            left: 15px;
            font-size: 10px;
            font-weight: bold;
            color: #d97706;
        }

        /* Categories Progress */
        .cat-row { margin-bottom: 15px; }
        .cat-name { font-weight: bold; font-size: 11px; }
        .cat-score { text-align: right; font-weight: bold; font-size: 10px; color: #2563eb; }
        .bar-bg {
            background-color: #f3f4f6;
            height: 8px;
            border-radius: 4px;
            margin-top: 4px;
            width: 100%;
        }
        .bar-fill { height: 100%; border-radius: 4px; }
        .bg-green { background-color: #16a34a; }
        .bg-orange { background-color: #f97316; }
        .bg-red { background-color: #dc2626; }

        /* Strengths & Areas to Improve */
        .list-title { font-size: 12px; font-weight: bold; border-bottom: 1px solid #f3f4f6; padding-bottom: 5px; margin-bottom: 10px; }
        .strengths-title { color: #15803d; }
        .areas-title { color: #ea580c; }
        ul.custom-list { list-style: none; padding: 0; margin: 0; }
        ul.custom-list li { margin-bottom: 8px; font-size: 10px; color: #4b5563; padding-left: 12px; position: relative; }
        .bullet-green { color: #16a34a; font-weight: bold; position: absolute; left: 0; }
        .bullet-orange { color: #f97316; font-weight: bold; position: absolute; left: 0; }

        /* Recommendations */
        .rec-card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 12px;
            border-left-width: 4px;
            border-left-style: solid;
        }
        .border-red { border-left-color: #ef4444; }
        .border-orange { border-left-color: #fbd38d; }
        .rec-cat { font-size: 9px; font-weight: bold; color: #6b7280; text-transform: uppercase; margin-right: 10px; }
        .rec-badge { 
            font-size: 8px; font-weight: bold; text-transform: uppercase; 
            padding: 2px 6px; border-radius: 3px; 
        }
        .badge-high { background-color: #fee2e2; color: #dc2626; }
        .badge-medium { background-color: #ffedd5; color: #ea580c; }
        .rec-text { font-size: 11px; color: #4b5563; margin-top: 8px; margin-bottom: 0; }

    </style>
</head>
<body>

    <!-- BRAND HEADER -->
    <div class="brand-header">
        <table>
            <tr>
                <td style="width: 50px; vertical-align: middle;">
                    <img src="{{ public_path('logo.png') }}" alt="La Sirene Logo" style="height: 35px;">
                </td>
                <td style="vertical-align: middle;">
                    <div class="brand-title">La Sirene.</div>
                    <div class="brand-subtitle">Business Advisory Services</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- MAIN TITLE -->
    <h1 style="margin-bottom: 2px;"><span class="text-orange">Business Health Checkup</span></h1>
    <div style="margin-top: 40px; padding: 20px; text-align: center; font-size: 14px; font-weight: bold; border-top: 1px solid #e5e7eb; color: #111827;">
       For any queries, please contact 7418111000.
    </div>
    

    <!-- BUSINESS INFO -->
    <div class="info-box">
        <h2>{{ $audit->business_name }}</h2>
        <div class="info-list" style="margin-bottom: 6px;">
            <span>{{ $audit->owner_name }}</span>
            <span>&bull; {{ $audit->business_type ?? 'Business' }}</span>
            <span>&bull; {{ $audit->industry ?? 'Sector' }}</span>
        </div>
        <div class="info-list" style="font-size: 10px;">
            <span>Audit Date: <strong>{{ $audit->audit_date }}</strong></span>
            <span>&bull; Auditor: <strong>{{ $audit->auditor_name ?? 'N/A' }}</strong></span>
            <br>
            <span style="margin-top: 4px; display:inline-block;">Years: <strong>{{ $audit->years_in_operation ?? 'N/A' }}</strong></span>
            <span style="margin-top: 4px; display:inline-block;">&bull; Employees: <strong>{{ $audit->no_of_employees ?? 'N/A' }}</strong></span>
        </div>
    </div>

    <!-- GRADE SECTION -->
    <div class="grade-container">
        <table>
            <tr>
                <td class="grade-box-left">
                    <div class="grade-score">{{ $audit->total_score }}</div>
                    <div class="grade-max">/{{ $maxTotalScore }}</div>
                    <div class="grade-text">{{ $grade }}</div>
                    <div class="grade-percent">{{ $overallPercentage }}% overall</div>
                </td>
                <td style="width: 5%;"></td>
                <td class="grade-box-right">
                    <div class="grade-text text-blue" style="font-size: 16px;">{{ $grade }}</div>
                    <div class="grade-desc">{{ $gradeDesc }}</div>
                    
                    <div class="xp-bar-bg">
                        <div class="xp-bar-fill" style="width: {{ $overallPercentage }}%;"></div>
                        <div class="xp-text">⚡ {{ $audit->total_xp }} XP Earned</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- PAGE BREAK -->
    <div class="page-break"></div>

    <!-- CATEGORIES SUMMARY -->
    <div style="margin-bottom: 40px;">
        <h3>📊 Score Summary</h3>
        <div style="padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px;">
            @foreach($categoryScores as $cat)
                <div class="cat-row">
                    <table>
                        <tr>
                            <td class="cat-name">{{ $cat['name'] }}</td>
                            <td class="cat-score">{{ $cat['score'] }}/{{ $cat['max'] }} ({{ $cat['percentage'] }}%)</td>
                        </tr>
                    </table>
                    <div class="bar-bg">
                        <div class="bar-fill {{ $cat['percentage'] >= 70 ? 'bg-green' : ($cat['percentage'] >= 40 ? 'bg-orange' : 'bg-red') }}" style="width: {{ $cat['percentage'] }}%;"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- STRENGTHS & AREAS TO IMPROVE -->
    <div style="margin-bottom: 40px;">
        <h3>⚡ Strengths & Areas to Improve</h3>
        
        <table>
            <tr>
                <td style="width: 48%; padding-right: 10px;">
                    <div class="list-title strengths-title">Strengths</div>
                    <ul class="custom-list">
                        @forelse($strengths as $strength)
                            <li><span class="bullet-green">&bull;</span> {{ $strength }}</li>
                        @empty
                            <li style="color: #9ca3af; font-style: italic;">No major strengths identified yet.</li>
                        @endforelse
                    </ul>
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 48%; padding-left: 10px;">
                    <div class="list-title areas-title">Areas to Improve</div>
                    <ul class="custom-list">
                        @forelse($improvements as $improvement)
                            <li><span class="bullet-orange">&bull;</span> {{ $improvement }}</li>
                        @empty
                            <li style="color: #9ca3af; font-style: italic;">No major weaknesses!</li>
                        @endforelse
                    </ul>
                </td>
            </tr>
        </table>
    </div>

    <!-- KEY RECOMMENDATIONS -->
    <div>
        <h3>💡 Key Recommendations</h3>
        
        @forelse($recommendations as $rec)
            <div class="rec-card {{ $rec['priority'] === 'HIGH' ? 'border-red' : 'border-orange' }}">
                <div>
                    <span class="rec-cat">{{ $rec['category'] }}</span>
                    <span class="rec-badge {{ $rec['priority'] === 'HIGH' ? 'badge-high' : 'badge-medium' }}">{{ $rec['priority'] }}</span>
                </div>
                <p class="rec-text">{{ $rec['suggestion'] }}</p>
            </div>
        @empty
            <div style="padding: 15px; text-align: center; color: #9ca3af; border: 1px solid #e5e7eb; border-radius: 6px; font-style: italic;">
                No actionable recommendations found based on current responses.
            </div>
        @endforelse
    </div>

    <!-- PAGE BREAK -->
    <div class="page-break"></div>

    <!-- DETAILED AUDIT RESPONSES -->
    <div>
        <h3>📋 Detailed Audit Responses</h3>
        
        @php
            $groupedAnswers = $audit->answers->groupBy('question.category.name');
            $questionNumber = 1;
        @endphp

        @foreach($groupedAnswers as $categoryName => $answers)
            <div style="margin-bottom: 20px;">
                <div style="font-size: 14px; font-weight: bold; color: #111827; margin-bottom: 10px; border-left: 4px solid #f97316; padding-left: 8px;">
                    {{ $categoryName }}
                </div>
                
                <table style="font-size: 10px; border-top: 1px solid #e5e7eb; margin-bottom: 20px;">
                    <thead style="background-color: #f3f4f6; color: #6b7280; text-align: left;">
                        <tr>
                            <th style="padding: 8px; width: 5%;">#</th>
                            <th style="padding: 8px; width: 55%;">QUESTION</th>
                            <th style="padding: 8px; width: 15%;">RESPONSE</th>
                            <th style="padding: 8px; width: 25%;">NOTES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($answers as $answer)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 8px; color: #9ca3af; font-weight: bold;">{{ $questionNumber++ }}</td>
                                <td style="padding: 8px; color: #374151;">{{ $answer->question->question_text }}</td>
                                <td style="padding: 8px;">
                                    @php
                                        $isMax = $answer->option->score > 0 && $answer->option->score >= $answer->question->options->max('score');
                                    @endphp
                                    <span style="display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; {{ $isMax ? 'background-color: #dcfce7; color: #15803d;' : 'background-color: #ffedd5; color: #c2410c;' }}">
                                        {{ $answer->option->option_text }}
                                    </span>
                                </td>
                                <td style="padding: 8px; color: #6b7280; font-style: italic;">{{ $answer->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 40px; padding: 20px; text-align: center; font-size: 14px; font-weight: bold; border-top: 1px solid #e5e7eb; color: #111827;">
       For any queries, please contact 7418111000.
    </div>

</body>
</html>