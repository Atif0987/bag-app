<?php

namespace App\Http\Controllers;
use App\Models\Subscription;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // Create a new subscription
    public function create(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:3_months,6_months,9_months',
        ]);

        $subscription = Subscription::create([
            'user_id' => auth()->id(),
            'plan' => $request->plan,
            'start_date' => now(),
            'end_date' => now()->addMonths($this->getPlanDuration($request->plan)),
        ]);

        return response()->json($subscription);
    }

    // Handle auto-renewal of the subscription
    public function autoRenew(Subscription $subscription)
    {
        $subscription->autoRenew();

        return response()->json($subscription);
    }

    // Cancel subscription
    public function cancel(Subscription $subscription)
    {
        $subscription->cancel();

        return response()->json(['message' => 'Subscription canceled successfully.']);
    }

    private function getPlanDuration($plan)
    {
        switch ($plan) {
            case '3_months':
                return 3;
            case '6_months':
                return 6;
            case '9_months':
                return 9;
            default:
                return 0;
        }
    }
}
