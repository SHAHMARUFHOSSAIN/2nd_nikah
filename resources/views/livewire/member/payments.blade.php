<div class="container" style="padding-top: 2rem; padding-bottom: 3rem;">
    <div style="max-width: 950px; margin: 0 auto;">
        
        {{-- Header --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; color: var(--bg-wine); margin-bottom: 0.25rem;">
                    Payment History
                </h1>
                <p style="color: var(--text-muted); font-size: 0.95rem;">
                    Your transaction history and membership subscription payment records.
                </p>
            </div>

            <div>
                <a href="{{ route('membership.index') }}" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.9rem;">
                    Membership Plans
                </a>
            </div>
        </div>

        @if ($transactions->total() > 0)
            <div class="card" style="border-radius: 1.5rem; padding: 0; overflow: hidden; background: #FFFFFF; box-shadow: var(--shadow-md);">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                        <thead>
                            <tr style="background: var(--bg-warm); border-bottom: 1px solid var(--border-warm); color: var(--bg-wine); font-weight: 700;">
                                <th style="padding: 1rem 1.25rem;">Transaction ID</th>
                                <th style="padding: 1rem 1.25rem;">Plan</th>
                                <th style="padding: 1rem 1.25rem;">Amount</th>
                                <th style="padding: 1rem 1.25rem;">Status</th>
                                <th style="padding: 1rem 1.25rem;">Date</th>
                                <th style="padding: 1rem 1.25rem;">Subscription Period</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $txn)
                                <tr style="border-bottom: 1px solid var(--border-warm);">
                                    <td style="padding: 1rem 1.25rem; font-family: monospace; font-size: 0.85rem; font-weight: 600;">
                                        {{ $txn->transaction_id }}
                                    </td>
                                    <td style="padding: 1rem 1.25rem; font-weight: 600; color: var(--bg-wine);">
                                        {{ $txn->membershipPlan->name ?? 'Membership' }}
                                    </td>
                                    <td style="padding: 1rem 1.25rem; font-weight: 700; color: var(--primary);">
                                        {{ $txn->currency === 'BDT' ? '৳' : '$' }}{{ number_format($txn->amount, 2) }}
                                    </td>
                                    <td style="padding: 1rem 1.25rem;">
                                        @if ($txn->status === 'paid')
                                            <span style="background: #DEF7EC; color: #03543F; font-size: 0.78rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 9999px;">
                                                ✓ Paid
                                            </span>
                                        @elseif ($txn->status === 'initiated' || $txn->status === 'pending')
                                            <span style="background: #FFE4E6; color: var(--primary); font-size: 0.78rem; font-weight: 600; padding: 0.25rem 0.65rem; border-radius: 9999px;">
                                                Pending
                                            </span>
                                        @else
                                            <span style="background: #FDE8E8; color: #9B1C1C; font-size: 0.78rem; font-weight: 600; padding: 0.25rem 0.65rem; border-radius: 9999px;">
                                                {{ ucfirst($txn->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem 1.25rem; color: var(--text-muted); font-size: 0.85rem;">
                                        {{ $txn->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td style="padding: 1rem 1.25rem; font-size: 0.85rem; color: var(--text-main);">
                                        @if ($txn->subscription && $txn->subscription->starts_at && $txn->subscription->ends_at)
                                            {{ $txn->subscription->starts_at->format('M d') }} - {{ $txn->subscription->ends_at->format('M d, Y') }}
                                        @else
                                            <span style="color: var(--text-light);">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">💳</div>
                <h3 class="empty-state-title">No payment history</h3>
                <p class="empty-state-desc">You have not initiated any membership transactions yet.</p>
                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('membership.index') }}" class="btn btn-primary">
                        Browse Membership Plans
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
