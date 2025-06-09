<?php

namespace App\Console\Commands;

use App\Mail\LowStockAlert;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendLowStockAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-low-stock-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email alerts for products with low stock';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $shops = Shop::all();
        
        foreach ($shops as $shop) {
            // Get low stock products for this shop
            $lowStockProducts = Product::where('shop_id', $shop->id)
                ->where('current_stock', '>', 0)
                ->where('current_stock', '<=', \DB::raw('min_stock_level'))
                ->get();
                
            if ($lowStockProducts->count() > 0) {
                // Get shop owner and managers
                $recipients = User::whereHas('shopUsers', function ($query) use ($shop) {
                    $query->where('shop_id', $shop->id)
                        ->whereIn('role', ['owner', 'manager', 'stock']);
                })->get();
                
                foreach ($recipients as $recipient) {
                    Mail::to($recipient->email)->send(new LowStockAlert($shop, $lowStockProducts));
                }
                
                $this->info("Sent low stock alerts for {$shop->name} to {$recipients->count()} recipients.");
            } else {
                $this->info("No low stock products found for {$shop->name}.");
            }
        }
        
        return Command::SUCCESS;
    }
}