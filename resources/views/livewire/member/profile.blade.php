<div class="container">
    <div style="max-width: 900px; margin: 0 auto;">
        
        {{-- My Profile Header Card --}}
        <div class="card" style="border-radius: 1.5rem; margin-bottom: 2rem; background: linear-gradient(135deg, #FFFFFF 0%, #FFF5F7 100%);">
            <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;">
                {{-- User Main Photo Avatar --}}
                <div style="width: 90px; height: 90px; border-radius: 1.25rem; overflow: hidden; background: var(--bg-warm); border: 2px solid var(--primary-light); flex-shrink: 0; position: relative; display: flex; align-items: center; justify-content: center;">
                    @if ($profile && $profile->photo_url)
                        <img src="{{ $profile->photo_url }}" alt="{{ $profile->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="font-size: 2.2rem; font-weight: 700; color: var(--primary);">
                            {{ strtoupper(substr(auth()->user()->name ?? 'M', 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- Profile Info & Badges --}}
                <div style="flex: 1; min-width: 250px;">
                    <div style="display: flex; items-center: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.25rem;">
                        <h1 style="font-size: 1.6rem; font-weight: 700; color: var(--bg-wine); margin: 0;">
                            {{ $profile->full_name ?: auth()->user()->name }}
                        </h1>
                        @if (auth()->user()->email_verified_at)
                            <span style="background: #DEF7EC; color: #03543F; border: 1px solid #BCF0DA; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                ✓ Email Verified
                            </span>
                        @endif
                        @if ($is_profile_visible)
                            <span style="background: #E0F2FE; color: #0369A1; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                👁️ Public
                            </span>
                        @else
                            <span style="background: #FEF3C7; color: #92400E; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                🔒 Hidden
                            </span>
                        @endif
                        <span style="background: #F3ECE9; color: var(--text-main); padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                            📷 {{ $photoCount }} {{ Str::plural('Photo', $photoCount) }}
                        </span>
                    </div>

                    {{-- Completion Progress --}}
                    <div style="margin-top: 0.75rem; max-width: 380px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 600; color: var(--bg-wine); margin-bottom: 0.3rem;">
                            <span>Profile Completion</span>
                            <span>{{ $completionPercentage }}%</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: #E5E0DC; border-radius: 9999px; overflow: hidden;">
                            <div style="width: {{ $completionPercentage }}%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--secondary)); transition: width 0.4s ease;"></div>
                        </div>
                    </div>
                </div>

                {{-- Action CTAs --}}
                <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: flex-end; min-width: 180px;">
                    <a href="{{ route('member.profile.photos') }}" class="btn btn-outline" style="width: 100%; text-align: center; padding: 0.5rem 1rem; font-size: 0.85rem; color: var(--primary); border-color: var(--primary);">
                        📷 Manage Photos ({{ $photoCount }})
                    </a>
                    @if ($profile && $profile->id)
                        <a href="{{ route('members.show', $profile) }}" target="_blank" class="btn btn-outline" style="width: 100%; text-align: center; padding: 0.5rem 1rem; font-size: 0.85rem; color: var(--bg-wine);">
                            👁️ View Public Profile
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if (session()->has('status'))
            <div class="alert-success" style="margin-bottom: 1.5rem;">
                {{ session('status') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert-error" style="margin-bottom: 1.5rem;">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="saveProfile">
            
            {{-- Section A: Basic Information --}}
            <div class="card" style="border-radius: 1.5rem; margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm); color: var(--bg-wine);">
                    A. Basic Information
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name <span style="color: var(--primary);">*</span></label>
                        <input type="text" id="first_name" wire:model.defer="first_name" class="form-input" placeholder="e.g. Abdullah" required>
                        @error('first_name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name <span style="color: var(--primary);">*</span></label>
                        <input type="text" id="last_name" wire:model.defer="last_name" class="form-input" placeholder="e.g. Khan" required>
                        @error('last_name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth" class="form-label">Date of Birth <span style="color: var(--primary);">*</span></label>
                        <input type="date" id="date_of_birth" wire:model.defer="date_of_birth" class="form-input" required>
                        @error('date_of_birth') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="gender" class="form-label">Gender <span style="color: var(--primary);">*</span></label>
                        <select id="gender" wire:model.defer="gender" class="form-input" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        @error('gender') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="marital_status" class="form-label">Marital Status <span style="color: var(--primary);">*</span></label>
                        <select id="marital_status" wire:model.defer="marital_status" class="form-input" required>
                            <option value="">Select Status</option>
                            <option value="Unmarried">Unmarried (অবিবাহিত)</option>
                            <option value="Married (Seeking 2nd Marriage)">Married - Seeking 2nd Marriage (বিবাহিত - ২য় বিবাহ করতে চান)</option>
                            <option value="Divorced">Divorced (ডিভোর্সড)</option>
                            <option value="Widowed">Widowed (বিধবা / বিপত্নীক)</option>
                            <option value="Single Parent">Single Parent (সন্তানসহ অবিবাহিত/ডিভোর্সড)</option>
                        </select>
                        @error('marital_status') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="religion" class="form-label">Religion <span style="color: var(--primary);">*</span></label>
                        <select id="religion" wire:model.defer="religion" class="form-input" required>
                            <option value="">Select Religion</option>
                            <option value="Islam">Islam</option>
                            <option value="Hinduism">Hinduism</option>
                            <option value="Christianity">Christianity</option>
                            <option value="Buddhism">Buddhism</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('religion') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" id="phone" wire:model.defer="phone" class="form-input" placeholder="+1 234 567 890">
                        @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" wire:model.defer="city" class="form-input" placeholder="e.g. Dhaka / London / New York">
                        @error('city') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" wire:model.defer="country" class="form-input" placeholder="e.g. Bangladesh / United States">
                        @error('country') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 0.5rem;">
                    <label for="location" class="form-label">Full Address / Location Details</label>
                    <input type="text" id="location" wire:model.defer="location" class="form-input" placeholder="Specific location details">
                    @error('location') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Section B: Matrimonial Information --}}
            <div class="card" style="border-radius: 1.5rem; margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm); color: var(--bg-wine);">
                    B. Matrimonial Information
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
                    <div class="form-group">
                        <label for="height" class="form-label">Height (উচ্চতা - ফুট ও ইঞ্চি)</label>
                        <select id="height" wire:model.defer="height" class="form-input">
                            <option value="">Select Height</option>
                            @php
                                $heightOptions = [
                                    56 => '4\' 8" (56 in)',
                                    57 => '4\' 9" (57 in)',
                                    58 => '4\' 10" (58 in)',
                                    59 => '4\' 11" (59 in)',
                                    60 => '5\' 0" (60 in)',
                                    61 => '5\' 1" (61 in)',
                                    62 => '5\' 2" (62 in)',
                                    63 => '5\' 3" (63 in)',
                                    64 => '5\' 4" (64 in)',
                                    65 => '5\' 5" (65 in)',
                                    66 => '5\' 6" (66 in)',
                                    67 => '5\' 7" (67 in)',
                                    68 => '5\' 8" (68 in)',
                                    69 => '5\' 9" (69 in)',
                                    70 => '5\' 10" (70 in)',
                                    71 => '5\' 11" (71 in)',
                                    72 => '6\' 0" (72 in)',
                                    73 => '6\' 1" (73 in)',
                                    74 => '6\' 2" (74 in)',
                                    75 => '6\' 3" (75 in)',
                                    76 => '6\' 4" (76 in)',
                                    77 => '6\' 5" (77 in)',
                                    78 => '6\' 6" (78 in)',
                                    79 => '6\' 7" (79 in)',
                                    80 => '6\' 8" (80 in)',
                                ];
                            @endphp
                            @foreach ($heightOptions as $inches => $label)
                                <option value="{{ $inches }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('height') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="children_count" class="form-label">Number of Children</label>
                        <input type="number" id="children_count" wire:model.defer="children_count" class="form-input" min="0" max="20">
                        @error('children_count') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="education" class="form-label">Education / Qualification</label>
                        <input type="text" id="education" wire:model.defer="education" class="form-input" placeholder="e.g. B.Sc. in Computer Science">
                        @error('education') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="occupation" class="form-label">Occupation / Profession</label>
                        <input type="text" id="occupation" wire:model.defer="occupation" class="form-input" placeholder="e.g. Software Engineer / Teacher / Business">
                        @error('occupation') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Section C: About Me --}}
            <div class="card" style="border-radius: 1.5rem; margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm); color: var(--bg-wine);">
                    C. About Me
                </h3>

                <div class="form-group">
                    <label for="about_me" class="form-label">Personal Statement / Bio (নিজের সম্পর্কে কিছু কথা)</label>
                    <textarea id="about_me" wire:model.defer="about_me" class="form-input" rows="4" placeholder="আপনার পরিবার, ব্যক্তিত্ব, ধর্মীয় মূল্যবোধ ও নিজের সম্পর্কে কিছু কথা লিখুন..."></textarea>
                    @error('about_me') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Section D: Partner Preference & Interest --}}
            <div class="card" style="border-radius: 1.5rem; margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm); color: var(--bg-wine);">
                    D. Partner Preference & Interest (কেমন পাত্র / পাত্রী খুঁজছেন)
                </h3>

                <div class="form-group">
                    <label for="partner_expectation" class="form-label">Partner Preference / Expectations (প্রত্যাশিত জীবনসঙ্গী ও আগ্রহ)</label>
                    <textarea id="partner_expectation" wire:model.defer="partner_expectation" class="form-input" rows="4" placeholder="কেমন পাত্র বা পাত্রী খুঁজছেন তা বিস্তারিত লিখুন (যেমন: শিক্ষাগত যোগ্যতা, দ্বীনদারিতা, পারিবারিক ব্যাকগ্রাউন্ড, অবস্থান, ২য় বিবাহ সংক্রান্ত প্রত্যাশা ইত্যাদি)..."></textarea>
                    <span style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">
                        এখানে আপনার কাঙ্ক্ষিত পাত্র/পাত্রীর গুণাবলি ও প্রত্যাশা লিখুন। এটি আপনার পাবলিক প্রোফাইলে সবার জন্য স্পষ্টভাবে প্রদর্শিত হবে।
                    </span>
                    @error('partner_expectation') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Section E: Profile Photo --}}
            <div class="card" style="border-radius: 1.5rem; margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm); color: var(--bg-wine);">
                    E. Profile Photo
                </h3>

                <div style="display: flex; gap: 2rem; align-items: center; flex-wrap: wrap;">
                    {{-- Photo Preview or Current Photo or Empty State --}}
                    <div style="width: 120px; height: 120px; border-radius: 1rem; overflow: hidden; background: var(--bg-warm); border: 2px dashed var(--border-warm); display: flex; align-items: center; justify-content: center; position: relative;">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        @elseif ($profile && $profile->photo_url)
                            <img src="{{ $profile->photo_url }}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="text-align: center; color: var(--text-muted); font-size: 2rem;">
                                👤
                            </div>
                        @endif
                    </div>

                    <div style="flex: 1; min-width: 250px;">
                        <div class="form-group">
                            <label for="photo" class="form-label">Upload New Photo</label>
                            <input type="file" id="photo" wire:model="photo" class="form-input" accept="image/*">
                            <span style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">
                                Formats: JPG, PNG, WEBP, HEIC (Max size: 10MB)
                            </span>
                            @error('photo') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        @if ($profile && $profile->profile_photo_path)
                            <button type="button" wire:click="removePhoto" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.85rem; color: var(--primary); border-color: var(--primary-light);">
                                Remove Current Photo
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Section F: Profile Visibility & Action --}}
            <div class="card" style="border-radius: 1.5rem; margin-bottom: 2.5rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-warm); color: var(--bg-wine);">
                    F. Profile Visibility
                </h3>

                <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem;">
                    <input type="checkbox" id="is_profile_visible" wire:model="is_profile_visible" style="width: 20px; height: 20px; accent-color: var(--primary);">
                    <label for="is_profile_visible" style="font-weight: 600; font-size: 0.95rem; cursor: pointer;">
                        Make my profile visible on the platform
                    </label>
                </div>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin-left: 2rem;">
                    When enabled, your verified profile will be discoverable on 2nd Nikah.
                </p>

                <div style="margin-top: 2rem; border-top: 1px solid var(--border-warm); padding-top: 1.5rem; text-align: right;">
                    <button type="submit" wire:loading.attr="disabled" class="btn btn-primary" style="padding: 0.85rem 2.5rem; font-size: 1rem;">
                        <span wire:loading.remove>Save Profile Information</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
