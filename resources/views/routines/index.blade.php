@extends('layout.app')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: 700; color: white;">Class Routine</h1>
        <button onclick="openAddModal()" style="padding: 0.75rem 1.5rem; background: var(--accent-color, #FFD700); color: black; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: transform 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
            + Add Class
        </button>
    </div>

    @if(session('success'))
        <div style="padding: 1rem; background: #064e3b; color: #a7f3d0; border: 1px solid #10b981; border-radius: 8px; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: #1e1e1e; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.1); overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                <thead>
                    <tr style="background: #2d2d2d;">
                        <th style="width: 15%; padding: 1.25rem; text-align: left; color: #FFD700; font-weight: 700; border-bottom: 2px solid rgba(255,255,255,0.2); border-right: 1px solid rgba(255,255,255,0.1);">Time</th>
                        @foreach($days as $day)
                            <th style="padding: 1.25rem; text-align: left; color: white; font-weight: 700; border-bottom: 2px solid rgba(255,255,255,0.2); border-right: 1px solid rgba(255,255,255,0.1);">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeSlots as $start => $end)
                    @php
                        $displayStart = \Carbon\Carbon::parse($start)->format('h:i A');
                        $displayEnd = \Carbon\Carbon::parse($end)->format('h:i A');
                    @endphp
                        <tr>
                            <!-- Time Column -->
                            <td style="padding: 1rem; border-bottom: 1px solid rgba(255,255,255,0.2); border-right: 1px solid rgba(255,255,255,0.2); background: #262626; color: #e5e7eb; vertical-align: middle; height: 120px;">
                                <div style="font-weight: 700; color: #FFD700; font-size: 1.1rem;">{{ $displayStart }}</div>
                                <div style="font-size: 0.9rem;">to {{ $displayEnd }}</div>
                            </td>

                            <!-- Days Columns -->
                            @foreach($days as $day)
                                <td style="padding: 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.2); border-right: 1px solid rgba(255,255,255,0.2); vertical-align: top; height: 120px; background: #1e1e1e;">
                                    @if(isset($routineMap[$day][$start]))
                                        @php $routine = $routineMap[$day][$start]; @endphp
                                        <div class="class-card" style="background: linear-gradient(135deg, rgba(50,50,50,0.9), rgba(30,30,30,0.9)); border: 1px solid #FFD700; border-left: 4px solid #FFD700; border-radius: 6px; padding: 0.75rem; height: 100%; position: relative; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
                                            
                                            <div style="font-weight: 800; color: #FFD700; margin-bottom: 0.25rem; font-size: 1rem; letter-spacing: 0.5px;">{{ $routine->course }}</div>
                                            
                                            @if($routine->room)
                                                <div style="font-size: 0.85rem; color: #ffffff; font-weight: 500;">
                                                    <span style="opacity: 0.7;">Room:</span> {{ $routine->room }}
                                                </div>
                                            @endif
                                            
                                            <form action="{{ route('class-routines.destroy', $routine->id) }}" method="POST" style="position: absolute; top: 5px; right: 5px;" onsubmit="return confirm('Remove this class?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background: rgba(255,0,0,0.2); border: 1px solid rgba(255,0,0,0.3); color: #f87171; border-radius: 4px; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;" title="Remove Class">
                                                    &times;
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <button onclick="openAddModal('{{ $day }}', '{{ \Carbon\Carbon::parse($start)->format('H:i') }}')" 
                                            style="width: 100%; height: 100%; background: transparent; border: 1px dashed rgba(255,255,255,0.15); border-radius: 6px; color: rgba(255,255,255,0.3); cursor: pointer; display: flex; align-items: center; justify-content: center; font-weight: 600;" 
                                            class="add-btn-hover">
                                            <span>+</span>
                                        </button>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Class Modal --}}
<div id="addModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: #2d2d2d; padding: 2rem; border-radius: 12px; width: 100%; max-width: 450px; border: 1px solid #444; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
        <h2 style="color: #FFD700; margin-bottom: 0.5rem; font-size: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">Schedule Class</h2>
        
        <form action="{{ route('class-routines.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 1.25rem; margin-top: 1rem;">
                <label style="display: block; color: white; margin-bottom: 0.5rem; font-weight: 600;">Course Name</label>
                <input type="text" name="course" required placeholder="e.g. CSE470" 
                    style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #555; border-radius: 6px; color: white; font-size: 1rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="display: block; color: white; margin-bottom: 0.5rem; font-weight: 600;">Day</label>
                    <select name="day" id="modalDay" required 
                        style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #555; border-radius: 6px; color: white; font-size: 0.95rem;">
                        @foreach($days as $day)
                            <option value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; color: white; margin-bottom: 0.5rem; font-weight: 600;">Start Time</label>
                    <select name="start_time" id="modalTime" required 
                        style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #555; border-radius: 6px; color: white; font-size: 0.95rem;">
                        @foreach($timeSlots as $start => $end)
                            <option value="{{ \Carbon\Carbon::parse($start)->format('H:i') }}">
                                {{ \Carbon\Carbon::parse($start)->format('h:i A') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; color: white; margin-bottom: 0.5rem; font-weight: 600;">Room (Optional)</label>
                <input type="text" name="room" placeholder="e.g. UB20401" 
                    style="width: 100%; padding: 0.75rem; background: #1a1a1a; border: 1px solid #555; border-radius: 6px; color: white; font-size: 1rem;">
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="closeAddModal()" 
                    style="flex: 1; padding: 0.75rem; background: transparent; border: 1px solid #666; color: #ddd; border-radius: 6px; cursor: pointer; font-weight: 600; transition: background 0.2s;">
                    Cancel
                </button>
                <button type="submit" 
                    style="flex: 1; padding: 0.75rem; background: #FFD700; border: none; color: black; font-weight: 700; border-radius: 6px; cursor: pointer; transition: opacity 0.2s;">
                    Add Class
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Removed hover color changes as requested */
    .add-btn-hover:hover {
        /* No style */
    }
    button[type="submit"]:hover {
        opacity: 0.9;
    }
        opacity: 0.9;
    }
    button[type="button"]:hover {
        background: rgba(255,255,255,0.05);
    }
</style>

<script>
    function openAddModal(day = null, time = null) {
        document.getElementById('addModal').style.display = 'flex';
        if(day) document.getElementById('modalDay').value = day;
        if(time) document.getElementById('modalTime').value = time;
    }

    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }

    // Close modal on outside click
    document.getElementById('addModal').addEventListener('click', function(e) {
        if (e.target === this) closeAddModal();
    });
</script>
@endsection
