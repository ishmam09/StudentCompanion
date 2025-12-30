@extends('layout.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="font-family: var(--font-heading); color: var(--primary-color); margin: 0;">Smart Notifications</h1>
        
        @if(Auth::user()->unreadNotifications->count() > 0)
            <a href="{{ route('notifications.mark-all-read') }}" class="btn" style="background: var(--accent-color); color: var(--primary-color);">Mark All as Read</a>
        @endif
    </div>

    @if($notifications->count() > 0)
        <div style="background: white; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden;">
            @foreach($notifications as $notification)
                <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}" 
                     style="display: flex; gap: 1rem; padding: 1.5rem; border-bottom: 1px solid #e2e8f0; transition: background 0.2s; background: {{ $notification->read_at ? 'white' : '#f0fdf4' }};">
                    
                    <div style="font-size: 2rem; background: #f8fafc; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 12px; border: 1px solid #cbd5e1;">
                        {{ $notification->data['icon'] ?? '📢' }}
                    </div>

                    <div style="flex: 1;">
                        <div style="font-size: 1.1rem; color: var(--primary-color); margin-bottom: 0.5rem; font-weight: {{ $notification->read_at ? '400' : '600' }};">
                            {{ $notification->data['message'] }}
                        </div>
                        <div style="font-size: 0.85rem; color: #64748b; display: flex; align-items: center; gap: 0.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $notification->created_at->diffForHumans() }}
                        </div>

                         @if(isset($notification->data['type']) && $notification->data['type'] === 'consultation_request')
                            @php
                                $consultation = \App\Models\Consultation::find($notification->data['consultation_id'] ?? null);
                            @endphp
                            
                            @if($consultation)
                                @if($consultation->status === 'pending')
                                    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                                        <a href="{{ $notification->data['action_url_accept'] }}" style="padding: 0.5rem 1rem; background: #22c55e; color: white; border-radius: 8px; font-weight: 600; text-decoration: none; border: none; font-size: 0.9rem;">Accept Request</a>
                                        <a href="{{ $notification->data['action_url_reject'] }}" style="padding: 0.5rem 1rem; background: #ef4444; color: white; border-radius: 8px; font-weight: 600; text-decoration: none; border: none; font-size: 0.9rem;">Reject Request</a>
                                    </div>
                                @elseif($consultation->status === 'approved')
                                    <div style="margin-top: 0.5rem; display: inline-block; padding: 0.25rem 0.75rem; background: #dcfce7; color: #15803d; border-radius: 6px; font-size: 0.85rem; font-weight: 600; border: 1px solid #bbf7d0;">
                                        ✓ Accepted
                                    </div>
                                @elseif($consultation->status === 'rejected')
                                    <div style="margin-top: 0.5rem; display: inline-block; padding: 0.25rem 0.75rem; background: #fee2e2; color: #b91c1c; border-radius: 6px; font-size: 0.85rem; font-weight: 600; border: 1px solid #fecaca;">
                                        ✕ Rejected
                                    </div>
                                @endif
                            @else
                                <div style="margin-top: 0.5rem; font-size: 0.85rem; color: #94a3b8; font-style: italic;">
                                    Request no longer available.
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 2rem;">
            {{ $notifications->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 4rem; background: white; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🔕</div>
            <h3 style="color: #334155; margin-bottom: 0.5rem;">No Notifications</h3>
            <p style="color: #64748b;">You're all caught up!</p>
        </div>
    @endif
</div>
@endsection
