<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6">
    
    {{-- Header Banner --}}
    <x-ui.card padding="spacious" class="bg-gradient-to-br from-rose-50/80 via-pink-50/40 to-white border-rose-200/80">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="space-y-1">
                <span class="text-[11px] font-black tracking-widest text-rose-600 uppercase block">
                    {{ $user->is_admin ? 'Administrator Account' : 'Member Workspace' }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Welcome back, {{ $user->name }}</h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">
                    Account Email: <strong class="text-slate-800">{{ $user->email }}</strong> • Member ID: <strong class="text-slate-800">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</strong>
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                @if ($isPremium && $activeSubscription)
                    <x-ui.badge variant="success" size="md">
                        👑 Premium Member ({{ $activeSubscription->membershipPlan->name }})
                    </x-ui.badge>
                @else
                    <x-ui.badge variant="default" size="md">
                        ⚪ Free Member
                    </x-ui.badge>
                    <x-ui.button :href="route('membership.index')" variant="primary" size="sm">
                        Upgrade to Premium
                    </x-ui.button>
                @endif

                @if($user->email_verified_at)
                    <x-ui.verification-badge show-text />
                @else
                    <x-ui.button :href="route('verification.notice')" variant="soft" size="sm">
                        ⚠️ Verify Email
                    </x-ui.button>
                @endif
            </div>
        </div>
    </x-ui.card>

    @if(!$user->is_admin)
        {{-- Stat Cards Overview Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <x-ui.stat-card title="Pending Proposals" :value="$pendingReceivedCount" :subtitle="$pendingReceivedCount . ' Received'" icon="📥" :href="route('member.interests.received')" />
            <x-ui.stat-card title="Connections" :value="$connectionsCount" icon="🤝" :href="route('member.connections')" />
            <x-ui.stat-card title="Unread Messages" :value="$unreadMessagesCount" icon="💬" :href="route('member.messages.index')" :badge="$unreadMessagesCount > 0 ? 'New' : null" />
            <x-ui.stat-card title="Sent Proposals" :value="$sentInterestsCount" :subtitle="$sentInterestsCount . ' Sent'" icon="📤" :href="route('member.interests.sent')" />
        </div>

        {{-- Profile Completeness & Quick Actions --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- Profile Completeness Card --}}
            <x-ui.card class="lg:col-span-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-extrabold text-slate-900">Profile Completeness</h3>
                    <span class="text-xs font-black text-rose-600">{{ $completionPercentage }}% Complete</span>
                </div>

                {{-- Progress Bar --}}
                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden border border-rose-100">
                    <div class="h-full bg-gradient-to-r from-rose-500 to-pink-600 transition-all duration-500" style="width: {{ $completionPercentage }}%;"></div>
                </div>

                <div class="space-y-2 text-xs divide-y divide-slate-100 pt-1">
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Profile Discoverability:</span>
                        @if($profile && $profile->is_profile_visible)
                            <span class="font-extrabold text-emerald-700">● Publicly Discoverable</span>
                        @else
                            <span class="font-extrabold text-red-600">○ Hidden / Private</span>
                        @endif
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Profile Photo Gallery:</span>
                        <span class="font-bold text-slate-800">📷 {{ $photoCount }} {{ Str::plural('Photo', $photoCount) }}</span>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-slate-500">Completeness Status:</span>
                        <span class="font-bold text-slate-800">{{ ($profile && $profile->is_profile_complete) ? 'Fully Complete' : 'Needs Details' }}</span>
                    </div>
                </div>

                <div class="pt-2 flex flex-col sm:flex-row gap-2">
                    <x-ui.button :href="route('member.profile')" variant="primary" class="flex-1">
                        Edit Profile
                    </x-ui.button>
                    <x-ui.button :href="route('member.profile.photos')" variant="outline" class="flex-1 text-rose-600 border-rose-300">
                        📷 Photos ({{ $photoCount }})
                    </x-ui.button>
                    @if ($profile && $profile->id)
                        <x-ui.button :href="route('members.show', $profile)" target="_blank" variant="soft" class="flex-1">
                            👁️ View Public
                        </x-ui.button>
                    @endif
                </div>
            </x-ui.card>

            {{-- Activity & Quick Links Card --}}
            <x-ui.card class="lg:col-span-6 space-y-4 flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 mb-3">Quick Navigation</h3>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('member.shortlists.index') }}" class="p-3 bg-slate-50 hover:bg-rose-50/60 border border-slate-200/80 hover:border-rose-200 rounded-2xl transition space-y-1 group">
                            <span class="text-lg block">⭐</span>
                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-rose-600 transition">Shortlists</h4>
                            <p class="text-[10px] text-slate-500">Saved profiles</p>
                        </a>
                        <a href="{{ route('member.visitors.index') }}" class="p-3 bg-slate-50 hover:bg-rose-50/60 border border-slate-200/80 hover:border-rose-200 rounded-2xl transition space-y-1 group">
                            <span class="text-lg block">👀</span>
                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-rose-600 transition">Visitors</h4>
                            <p class="text-[10px] text-slate-500">Profile viewers</p>
                        </a>
                        <a href="{{ route('member.notifications.index') }}" class="p-3 bg-slate-50 hover:bg-rose-50/60 border border-slate-200/80 hover:border-rose-200 rounded-2xl transition space-y-1 group">
                            <span class="text-lg block">🔔</span>
                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-rose-600 transition">Notifications</h4>
                            <p class="text-[10px] text-slate-500">Real-time alerts</p>
                        </a>
                        <a href="{{ route('member.settings.index') }}" class="p-3 bg-slate-50 hover:bg-rose-50/60 border border-slate-200/80 hover:border-rose-200 rounded-2xl transition space-y-1 group">
                            <span class="text-lg block">⚙️</span>
                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-rose-600 transition">Settings</h4>
                            <p class="text-[10px] text-slate-500">Account & Privacy</p>
                        </a>
                    </div>
                </div>

                <div class="flex gap-2 pt-2">
                    <x-ui.button :href="route('member.interests.received')" variant="outline" class="flex-1">
                        Proposals
                    </x-ui.button>
                    <x-ui.button :href="route('member.connections')" variant="primary" class="flex-1">
                        My Connections
                    </x-ui.button>
                </div>
            </x-ui.card>

        </div>
    @else
        {{-- Admin User Banner --}}
        <x-ui.card padding="spacious" class="text-center space-y-4">
            <h2 class="text-xl font-extrabold text-slate-900">Administrator Management Panel</h2>
            <p class="text-xs sm:text-sm text-slate-600 max-w-lg mx-auto leading-relaxed">
                You are logged in with an Administrator account. Manage member profiles, proposals, payment transactions, system settings, and CMS content from the Filament Admin area.
            </p>
            <div>
                <x-ui.button href="/admin" variant="primary" size="lg">
                    Open Filament Admin Panel &rarr;
                </x-ui.button>
            </div>
        </x-ui.card>
    @endif
</div>
