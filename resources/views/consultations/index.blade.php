@extends('layout.app')

@section('content')
<div style="padding: 20px; font-family: 'Plus Jakarta Sans', sans-serif;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #1e293b; margin: 0;">
            Teacher Consultation Scheduler
        </h2>
    </div>

    @if(session('message'))
        <div style="background: #FEF9c3; color: #854d0e; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid #fef08a; display: flex; align-items: center; gap: 10px;">
            <span>✅</span> {{ session('message') }}
        </div>
    @endif

    <div style="background: white; padding: 2rem; border-radius: 16px; border-top: 8px solid #ECC94B; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin-bottom: 3rem;">
        <h3 style="margin-top: 0; margin-bottom: 1.5rem; color: #334155; font-size: 1.25rem;">Request New Consultation</h3>
        
        <form action="{{ route('consultations.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <label style="display: block; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Teacher Initial</label>
                    <input type="text" name="teacher_initial" placeholder="e.g. ROKS" required 
                           style="width: 100%; padding: 0.75rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                </div>

                <div>
                    <label style="display: block; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Teacher's Email</label>
                    <input type="email" name="teacher_email" placeholder="teacher@university.edu" required 
                           style="width: 100%; padding: 0.75rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                </div>

                <div>
                    <label style="display: block; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Preferred Date & Time</label>
                    <input type="datetime-local" name="appointment_date" required 
                           style="width: 100%; padding: 0.75rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem;">
                </div>

                <div>
                    <label style="display: block; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">Consultation Topic</label>
                    <select name="topic" style="width: 100%; padding: 0.75rem; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; background: white;">
                        <option value="Thesis Guidance">Thesis Guidance</option>
                        <option value="Assignment Clarification">Assignment Clarification</option>
                        <option value="Exam Review">Exam Review</option>
                        <option value="Project Feedback">Project Feedback</option>
                        <option value="General Query">General Query</option>
                    </select>
                </div>
            </div>

            <button type="submit" style="margin-top: 2rem; width: 100%; background: #ECC94B; color: #1e293b; border: none; padding: 1rem; border-radius: 10px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                🚀 Book Slot & Notify Teacher
            </button>
        </form>
    </div>


    

    <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <div style="padding: 1.5rem; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
            <h3 style="margin: 0; color: #334155;">Your Appointments</h3>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;">Teacher</th>
                        <th style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;">Topic</th>
                        <th style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;">Scheduled Date</th>
                        <th style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;">Status</th>
                    </tr>
                </thead>
                <tbody style="color: #334155; font-size: 0.95rem;">
                    @forelse($consultations as $consultation)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-weight: 600;">{{ $consultation->teacher_name }}</div>
                                <div style="font-size: 0.8rem; color: #94a3b8;">{{ $consultation->teacher_email }}</div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">{{ $consultation->topic }}</td>
                            <td style="padding: 1.25rem 1.5rem;">
                                {{ \Carbon\Carbon::parse($consultation->appointment_date)->format('M d, Y') }}
                                <br>
                                <small style="color: #94a3b8;">at {{ \Carbon\Carbon::parse($consultation->appointment_date)->format('h:i A') }}</small>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <span style="display: inline-block; padding: 0.35rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; background: #e0f2fe; color: #0369a1; text-transform: uppercase;">
                                    {{ $consultation->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 3rem; text-align: center; color: #94a3b8;">
                                No consultation requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Hover effect for the submit button */
    button:hover {
        background: #d4af37 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }
    
    /* Table row hover effect */
    tbody tr:hover {
        background: #fffbeb;
    }
</style>
@endsection