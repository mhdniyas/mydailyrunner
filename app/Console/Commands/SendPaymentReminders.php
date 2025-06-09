<?php

namespace App\Console\Commands;

use App\Mail\PaymentReminder;
use App\Models\Customer;
use App\Models\Shop;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-payment-reminders {--days=7 : Number of days overdue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminders to customers with pending payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = now()->subDays($days)->format('Y-m-d');
        
        $shops = Shop::all();
        
        foreach ($shops as $shop) {
            // Get customers with pending payments
            $customers = Customer::where('shop_id', $shop->id)
                ->where('is_walk_in', false)
                ->whereHas('sales', function ($query) use ($cutoffDate) {
                    $query->where('status', '!=', 'paid')
                        ->where('sale_date', '<=', $cutoffDate);
                })
                ->get();
                
            $this->info("Found {$customers->count()} customers with pending payments for {$shop->name}.");
            
            foreach ($customers as $customer) {
                // Skip customers without email
                if (!$customer->email) {
                    $this->warn("Skipping customer {$customer->name} (ID: {$customer->id}) - no email address.");
                    continue;
                }
                
                // Get pending sales for this customer
                $pendingSales = $customer->sales()
                    ->where('status', '!=', 'paid')
                    ->where('sale_date', '<=', $cutoffDate)
                    ->get();
                    
                $totalDue = $pendingSales->sum('due_amount');
                
                // Send reminder
                Mail::to($customer->email)->send(new PaymentReminder($shop, $customer, $pendingSales, $totalDue));
                
                $this->info("Sent payment reminder to {$customer->name} for {$pendingSales->count()} sales totaling {$totalDue}.");
            }
        }
        
        return Command::SUCCESS;
    }
}