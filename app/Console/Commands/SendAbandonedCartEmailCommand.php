<?php

namespace App\Console\Commands;

use DB;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Http\Traits\ProductTrait;

class SendAbandonedCartEmailCommand extends Command
{
    use ProductTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:abandoned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to sales for abandoned cart more than 4 hours';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sales_admin = DB::table('fumaco_admin_user')->whereIn('user_type', ['System Admin', 'Sales Admin'])->where('xstatus', 1)->get();
        $abandoned_cart = DB::table('fumaco_cart as cart')->where('cart.user_type', 'member')
            ->join('fumaco_items as item', 'item.f_idcode', 'cart.item_code')
            ->whereDate('cart.last_modified_at', Carbon::now())
            ->select('cart.*', 'item.f_default_price', 'item.f_stock_uom')
            ->get();

        $abandoned_cart = collect($abandoned_cart)->map(function ($q){
            if($q->last_modified_at <= Carbon::now()->subHours(4)->toDateTimeString() && $q->last_modified_at >= Carbon::now()->subHours(5)->toDateTimeString()){
                return $q;
            }
        })->filter()->values();

        if($abandoned_cart){
            $sale = DB::table('fumaco_on_sale')
                ->whereDate('start_date', '<=', Carbon::now()->toDateString())
                ->whereDate('end_date', '>=', Carbon::today()->toDateString())
                ->where('status', 1)->where('apply_discount_to', 'All Items')
                ->select('discount_type', 'discount_rate')->first();

            $item_images = DB::table('fumaco_items_image_v1')->whereIn('idcode', collect($abandoned_cart)->pluck('item_code'))->get();
            $item_images = collect($item_images)->groupBy('idcode');

            $order_numbers = collect($abandoned_cart)->pluck('transaction_id');
            $item_codes = collect($abandoned_cart)->pluck('item_code');
            $on_sale_items = $this->onSaleItems($item_codes);

            $clearance_sale_items = $this->isIncludedInClearanceSale($item_codes);
            $sale_per_category = $this->getSalePerItemCategory(collect($abandoned_cart)->pluck('category_id'));

            $abandoned_cart = collect($abandoned_cart)->groupBy('user_email');

            $user_details = DB::table('fumaco_users')->whereIn('username', array_keys(collect($abandoned_cart)->toArray()))->select('id', 'username', 'f_name', 'f_lname')->get();
            $contact_details = DB::table('fumaco_user_add')->whereIn('user_idx', collect($user_details)->pluck('id'))->where('xdefault', 1)->where('address_class', 'Billing')->get();
            $contact_details = collect($contact_details)->groupBy('user_idx');

            $last_transaction_details = DB::table('fumaco_temp')->whereIn('xlogs', $order_numbers)->pluck('last_transaction_page', 'xemail');

            foreach($user_details as $user){
                $items = isset($abandoned_cart[$user->username]) ? $abandoned_cart[$user->username] : [];
                $items_arr = [];

                foreach ($items as $item) {
                    $image = isset($item_images[$item->item_code]) ? $item_images[$item->item_code][0]->imgprimayx : null;
                    $image = $image ? '/storage/item_images/'. $item->item_code.'/gallery/preview/'. $image : '/storage/no-photo-available.png';

                    $on_sale = false;
                    $discount_type = $discount_rate = null;
                    if (array_key_exists($item->item_code, $on_sale_items)) {
                        $on_sale = $on_sale_items[$item->item_code]['on_sale'];
                        $discount_type = $on_sale_items[$item->item_code]['discount_type'];
                        $discount_rate = $on_sale_items[$item->item_code]['discount_rate'];
                    }

                    $item_detail = [
                        'default_price' => $item->f_default_price,
                        'category_id' => $item->category_id,
                        'item_code' => $item->item_code,
                        'discount_type' => $discount_type,
                        'discount_rate' => $discount_rate,
                        'stock_uom' => $item->f_stock_uom,
                        'on_sale' => $on_sale
                    ];

                    $is_on_clearance_sale = false;
                    if (array_key_exists($item->item_code, $clearance_sale_items)) {
                        $item_detail['discount_type'] = $clearance_sale_items[$item->item_code][0]->discount_type;
                        $item_detail['discount_rate'] = $clearance_sale_items[$item->item_code][0]->discount_rate;
                        $is_on_clearance_sale = true;
                    }

                    // get item price, discounted price and discount rate
                    $item_price_data = $this->getItemPriceAndDiscount($item_detail, $sale, $sale_per_category, $is_on_clearance_sale);

                    $is_discounted = ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0;
                    $price = $is_discounted ? $item_price_data['discounted_price'] : $item->f_default_price;
    
                    $items_arr[] = [
                        'item_code' => $item->item_code,
                        'image' => $image,
                        'item_description' => $item->item_description,
                        'qty' => $item->qty,
                        'default_price' => $item->f_default_price,
                        'is_discounted' => ($item_price_data['discount_rate'] > 0) ? $item_price_data['is_on_sale'] : 0,
                        'discounted_price' => '₱ ' . number_format($item_price_data['discounted_price'], 2, '.', ','),
                        'discount_display' => $item_price_data['discount_display'],
                        'price' => $price
                    ];
                }
    
                $arr = [
                    'email' => $user->username,
                    'name' => $user->f_name.' '.$user->f_lname,
                    'contact_number' => isset($contact_details[$user->id]) ? $contact_details[$user->id][0]->xmobile_number : $user->f_mobilenumber,
                    'last_transaction_page' => isset($last_transaction_details[$user->username]) ? $last_transaction_details[$user->username] : 'Cart Page',
                    'last_transaction_date' => Carbon::parse(collect($items)->max('last_modified_at'))->format('M. d, Y h:i A'),
                    'items' => $items_arr
                ];

                foreach ($sales_admin as $admin) {
                    try {
                        Mail::send('emails.admin_abandoned_email', $arr, function($message) use($admin){
                            $message->to(trim($admin->username));
                            $message->subject('Abandoned Cart - FUMACO');
                        });
                    } catch (\Swift_TransportException  $e) {}
                }
            }
        }
    }
}
