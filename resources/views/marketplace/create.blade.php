@extends('layout.app')

@section('content')
    <div class="container" style="max-width: 800px; margin: 0 auto; padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="font-size: 2rem; font-weight: 700; color: white;">Sell an Item</h1>
            <a href="{{ route('marketplace.index') }}"
                style="padding: 0.75rem 1.5rem; background: #FFD700; color: black; border: none; border-radius: 8px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
                Back
            </a>
        </div>

        <div
            style="background: #1e1e1e; padding: 2.5rem; border-radius: 16px; border: 1px solid rgba(255, 215, 0, 0.2); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);">

            @if(session('error'))
                <div
                    style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('marketplace.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Image Upload -->
                <div style="margin-bottom: 2rem; text-align: center;">
                    <label for="image"
                        style="display: inline-block; width: 100%; max-width: 400px; height: 200px; border: 2px dashed rgba(255,255,255,0.4); border-radius: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-direction: column; color: rgba(255,255,255,0.8); background: #262626; transition: border-color 0.2s;">
                        <svg width="40" height="40" stroke="currentColor" fill="none" viewBox="0 0 24 24"
                            style="margin-bottom: 0.5rem; color: #FFD700;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span style="font-weight: 500;">Click to upload product image</span>
                        <input type="file" name="image" id="image" accept="image/*" required style="display: none;"
                            onchange="previewImage(this)">
                    </label>
                    <img id="preview"
                        style="display: none; width: 100%; max-width: 400px; height: 200px; object-fit: cover; border-radius: 12px; margin: 0 auto; margin-top: -200px; position: relative; pointer-events: none; border: 1px solid rgba(255,255,255,0.2);">
                    @error('image') <span
                        style="color: #ef4444; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; color: white; margin-bottom: 0.5rem; font-weight: 700;">Title</label>
                        <input type="text" name="title" required placeholder="e.g. Calculus Textbook"
                            value="{{ old('title') }}"
                            style="width: 100%; padding: 0.75rem; background: #262626; border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; color: white; font-size: 1rem;">
                        @error('title') <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display: block; color: white; margin-bottom: 0.5rem; font-weight: 700;">Price
                            (BDT)</label>
                        <input type="number" step="0.01" name="price" required placeholder="0.00" value="{{ old('price') }}"
                            style="width: 100%; padding: 0.75rem; background: #262626; border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; color: white; font-size: 1rem;">
                        @error('price') <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; color: white; margin-bottom: 0.5rem; font-weight: 700;">Category</label>
                    <select name="category" required
                        style="width: 100%; padding: 0.75rem; background: #262626; border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; color: white; font-size: 1rem;">
                        <option value="" disabled selected>Select Category</option>
                        <option value="Books">Books</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Notes">Notes</option>
                        <option value="Services">Services</option>
                        <option value="Accessories">Accessories</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('category') <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <div style="margin-bottom: 2rem;">
                    <label
                        style="display: block; color: white; margin-bottom: 0.5rem; font-weight: 700;">Description</label>
                    <textarea name="description" required rows="4" placeholder="Describe your item..."
                        style="width: 100%; padding: 0.75rem; background: #262626; border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; color: white; font-size: 1rem;">{{ old('description') }}</textarea>
                    @error('description') <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; gap: 1rem;">
                    <a href="{{ route('marketplace.index') }}"
                        style="flex: 1; text-align: center; padding: 0.75rem; background: transparent; border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 8px; text-decoration: none;">Cancel</a>
                    <button type="submit"
                        style="flex: 2; padding: 0.75rem; background: #FFD700; color: black; border: none; font-weight: 700; border-radius: 8px; cursor: pointer;">List
                        Item</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('preview').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection