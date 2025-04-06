<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;


// class Subscription extends Model
// {
    
// }

// app/Models/Subscription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'plan', 'start_date', 'end_date', 'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the logic for auto-renewal
    public function autoRenew()
    {
        if ($this->is_active && now()->greaterThan($this->end_date)) {
            $this->update([
                'start_date' => now(),
                'end_date' => now()->addMonths($this->getPlanDuration()),
            ]);
        }
    }

    // Get the number of months for the selected plan
    private function getPlanDuration()
    {
        switch ($this->plan) {
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

    // Logic to cancel the subscription
    public function cancel()
    {
        $this->update(['is_active' => false]);
    }
}
