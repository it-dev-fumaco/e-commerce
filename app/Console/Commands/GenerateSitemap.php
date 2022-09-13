<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\Tags\Sitemap as SitemapTag;
use Spatie\Sitemap\SitemapIndex;
use Carbon\Carbon;
use DB;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap';

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
        // get about us page last modified date
        $about = DB::table('fumaco_about')->first();
        $about_last_modified_at = ($about) ? Carbon::parse($about->last_modified_at) : Carbon::now();

        // get contact us page last modified date
        $contact_last_modified_at = DB::table('fumaco_contact')->max('update_date');
        $contact_last_modified_at = ($contact_last_modified_at) ? Carbon::parse($contact_last_modified_at) : Carbon::now();
        
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0))
            ->add(Url::create('/about')->setLastModificationDate($about_last_modified_at)->setPriority(1.0))
            ->add(Url::create('/contact')->setLastModificationDate($contact_last_modified_at)->setPriority(1.0));

        $products = DB::table('fumaco_items')->where('f_status', 1)
            ->whereNotNull('slug')->where('slug', '!=', '')
            ->select('slug', 'last_modified_at')
            ->orderBy('last_modified_at', 'desc')->get();
        if (count($products) > 0) {
            foreach ($products as $product) {
                $sitemap->add(Url::create('/product/'.$product->slug)->setLastModificationDate(Carbon::parse($product->last_modified_at))->setPriority(1.0));
            }
        }

        $categories = DB::table('fumaco_categories')->where('publish', 1)
            ->whereNotNull('slug')->where('slug', '!=', '')->select('slug', 'last_modified_at')
            ->orderBy('last_modified_at', 'desc')->get();

        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $sitemap->add(Url::create('/products/'.$category->slug)->setLastModificationDate(Carbon::parse($category->last_modified_at))->setPriority(1.0));
            }
        }
        
        $blogs = DB::table('fumaco_blog')->where('blog_enable', 1)
            ->whereNotNull('slug')->where('slug', '!=', '')->orderBy('last_modified_at', 'desc')
            ->select('slug', 'last_modified_at')->get();
        if (count($blogs) > 0) {
            foreach ($blogs as $blog) {
                $sitemap->add(Url::create('/blog/'.$blog->slug)->setLastModificationDate(Carbon::parse($blog->last_modified_at)));
            }
            
            $sitemap->writeToFile(public_path('sitemap.xml'));
        }
    }
}
