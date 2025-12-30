@extends('layout.app')

@section('content')
<div style="padding: 20px; font-family: sans-serif;">
    <h2 style="color: #2d3748;">Your To Do List</h2>

    @if(session('message'))
        <div style="background: #FFF9C4; color: #856404; padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #ffeeba;">
            {{ session('message') }}
        </div>
    @endif

    <form action="{{ route('todo.store') }}" method="POST" style="background: #f7fafc; padding: 20px; border-radius: 10px; border-left: 10px solid #ECC94B; margin-bottom: 30px;">
        @csrf
        <input type="text" name="title" placeholder="What is the task?" required style="padding: 10px; width: 250px;">
        
        <select name="quadrant" style="padding: 10px;">
            <option value="1">Priority 1: Urgent & Important</option>
            <option value="2">Priority 2: Important (Not Urgent)</option>
            <option value="3">Priority 3: Urgent (Not Important)</option>
            <option value="4">Priority 4: Low Priority</option>
        </select>

        <button type="submit" style="background: #ECC94B; border: none; padding: 10px 20px; font-weight: bold; cursor: pointer; border-radius: 5px;">+ Add Task</button>
    </form>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        @foreach([1, 2, 3, 4] as $q)
            <div style="border: 1px solid #e2e8f0; border-radius: 8px; min-height: 150px;">
                <div style="background: #ECC94B; padding: 8px; font-weight: bold; text-align: center;">
                    @if($q == 1) DO FIRST @elseif($q == 2) SCHEDULE @elseif($q == 3) DELEGATE @else ELIMINATE @endif
                </div>
                <ul style="padding: 10px; list-style: none;">
                    @foreach($tasks->where('quadrant', $q) as $task)
                        <li style="display: flex; justify-content: space-between; border-bottom: 1px solid #edf2f7; padding: 5px 0;">
                            {{ $task->title }}
                            <form action="{{ route('todo.destroy', $task->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" style="color: red; background: none; border: none; cursor: pointer;">[Delete]</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
@endsection