<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CGPA Calculator</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .course-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 15px;
            margin-bottom: 15px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            font-size: 0.9em;
        }

        input[type="text"],
        input[type="number"] {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-remove {
            background: #ff4757;
            color: white;
            align-self: end;
        }

        .btn-remove:hover {
            background: #ee5a6f;
            transform: translateY(-2px);
        }

        .btn-add {
            background: #48dbfb;
            color: white;
            margin-top: 10px;
        }

        .btn-add:hover {
            background: #0abde3;
        }

        .btn-calculate {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 100%;
            padding: 15px;
            font-size: 1.1em;
            margin-top: 20px;
        }

        .btn-calculate:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-reset {
            background: #dfe6e9;
            color: #2d3436;
            width: 100%;
            margin-top: 10px;
        }

        .btn-reset:hover {
            background: #b2bec3;
        }

        .results {
            margin-top: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            border: 2px solid #dee2e6;
        }

        .result-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .result-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .result-card h3 {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .result-card .value {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        .details-table th,
        .details-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .details-table th {
            background: #667eea;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
        }

        .details-table tr:last-child td {
            border-bottom: none;
        }

        .details-table tr:hover {
            background: #f8f9fa;
        }

        .grade-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9em;
        }

        .grade-A-plus {
            background: #00b894;
            color: white;
        }

        .grade-A {
            background: #00cec9;
            color: white;
        }

        .grade-A-minus {
            background: #0984e3;
            color: white;
        }

        .grade-B-plus {
            background: #6c5ce7;
            color: white;
        }

        .grade-B {
            background: #fdcb6e;
            color: #333;
        }

        .grade-B-minus {
            background: #e17055;
            color: white;
        }

        .grade-C-plus {
            background: #fd79a8;
            color: white;
        }

        .grade-C {
            background: #e84393;
            color: white;
        }

        .grade-D {
            background: #d63031;
            color: white;
        }

        .grade-F {
            background: #2d3436;
            color: white;
        }

        @media (max-width: 768px) {
            .course-row {
                grid-template-columns: 1fr;
            }

            .btn-remove {
                width: 100%;
            }

            .header h1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📊 CGPA Calculator</h1>
            <p>Calculate your Grade Point Average with ease</p>
        </div>

        <div class="content">
            <form method="POST" action="{{ route('cgpa.calculate') }}" id="cgpaForm">
                @csrf
                <div id="coursesContainer">
                    @php $courses = $courses ?? [['name' => '', 'credits' => '', 'marks' => '']]; @endphp
                    @foreach($courses as $index => $course)
                        <div class="course-row">
                            <div class="form-group">
                                <label>Course Name</label>
                                <input type="text" name="courses[{{$index}}][name]" placeholder="e.g., Data Structures"
                                    value="{{ $course['name'] ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label>Credits</label>
                                <input type="number" name="courses[{{$index}}][credits]" step="0.5" min="0"
                                    placeholder="3.0" value="{{ $course['credits'] ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label>Marks</label>
                                <input type="number" name="courses[{{$index}}][marks]" min="0" max="100" placeholder="85"
                                    value="{{ $course['marks'] ?? '' }}" required>
                            </div>
                            <button type="submit" name="action" value="remove_course_{{$index}}" class="btn btn-remove"
                                formnovalidate>Remove</button>
                        </div>
                    @endforeach
                </div>

                <button type="submit" name="action" value="add_course" class="btn btn-add" formnovalidate>+ Add
                    Course</button>
                <button type="submit" name="action" value="calculate" class="btn btn-calculate">Calculate CGPA</button>
                <button type="submit" name="action" value="reset" class="btn btn-reset" formnovalidate>Reset</button>
            </form>

            @if(isset($results))
                <div class="results">
                    <div class="result-summary">
                        <div class="result-card">
                            <h3>Total Credits</h3>
                            <div class="value">{{ number_format($results['totalCredits'], 1) }}</div>
                        </div>
                        <div class="result-card">
                            <h3>Your CGPA</h3>
                            <div class="value">{{ number_format($results['gpa'], 2) }}</div>
                        </div>
                    </div>

                    @if(count($results['details']) > 0)
                        <table class="details-table">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Credits</th>
                                    <th>Marks</th>
                                    <th>Grade Point</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results['details'] as $detail)
                                    <tr>
                                        <td>{{ $detail['name'] }}</td>
                                        <td>{{ number_format($detail['credits'], 1) }}</td>
                                        <td>{{ number_format($detail['marks'], 0) }}</td>
                                        <td>{{ number_format($detail['point'], 2) }}</td>
                                        <td>
                                            <span
                                                class="grade-badge grade-{{ str_replace(['+', '-'], ['-plus', '-minus'], $detail['grade']) }}">
                                                {{ $detail['grade'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endif
        </div>
    </div>
</body>

</html>