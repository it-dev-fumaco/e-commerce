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
        $categories = DB::table('fumaco_categories')->where('publish', 1)
            ->whereNotNull('slug')->where('slug', '!=', '')
            ->whereNull('external_link')->orderBy('last_modified_at', 'desc')
            ->select('slug', 'id', 'last_modified_at')->get();
            
        $sitemap_indexes = SitemapIndex::create()->add('/sitemap/page-sitemap.xml');
            // add index per category
            foreach ($categories as $category) {
                // get active products
                $products = DB::table('fumaco_items')->where('f_status', 1)
                    ->whereNotNull('slug')->where('slug', '!=', '')
                    ->where('f_cat_id', $category->id)->orderBy('last_modified_at', 'desc')
                    ->select('slug', 'last_modified_at')->get();
                if (count($products) > 0) {
                    $sitemap_indexes->add(SitemapTag::create('/sitemap/'.$category->slug.'.xml')
                        ->setLastModificationDate(Carbon::parse($category->last_modified_at)));
                }
            }

        $sitemap_indexes->add('/sitemap/blog-sitemap.xml')->writeToFile(public_path('sitemap.xml'));

        // get about us page last modified date
        $about = DB::table('fumaco_about')->first();
        $about_last_modified_at = ($about) ? Carbon::parse($about->last_modified_at) : Carbon::now();

        // get contact us page last modified date
        $contact_last_modified_at = DB::table('fumaco_contact')->max('update_date');
        $contact_last_modified_at = ($contact_last_modified_at) ? Carbon::parse($contact_last_modified_at) : Carbon::now();

        // get contact us page last modified date
        $homepage = DB::table('fumaco_pages')->where('is_homepage', 1)->first();
        $homepage_last_modified_at = ($homepage) ? Carbon::parse($homepage->date_updated) : Carbon::now();
        
        // sitemap for pages
        Sitemap::create()
            ->add(Url::create('/')->setLastModificationDate($homepage_last_modified_at)->setPriority(1.0))
            ->add(Url::create('/about')->setLastModificationDate($about_last_modified_at)->setPriority(1.0))
            ->add(Url::create('/contact')->setLastModificationDate($contact_last_modified_at)->setPriority(1.0))
            ->writeToFile(public_path('sitemap/page-sitemap.xml'));

        // sitemap for products per category
        foreach ($categories as $id => $category) {
            // get active products
            $products = DB::table('fumaco_items')->where('f_status', 1)
                ->whereNotNull('slug')->where('slug', '!=', '')
                ->where('f_cat_id', $category->id)->orderBy('last_modified_at', 'desc')
                ->select('slug', 'last_modified_at')->get();
            if (count($products) > 0) {
                $product_sitemap = Sitemap::create();
                foreach ($products as $product) {
                    $product_sitemap->add(Url::create('/product/'.$product->slug)->setLastModificationDate(Carbon::parse($product->last_modified_at))->setPriority(1.0));
                }
                $product_sitemap->writeToFile(public_path('sitemap/'.$category->slug.'.xml'));
            }
        }

       
        // get blogs
        $blogs = DB::table('fumaco_blog')->where('blog_enable', 1)
            ->whereNotNull('slug')->where('slug', '!=', '')->orderBy('last_modified_at', 'desc')
            ->select('slug', 'last_modified_at')->get();
        if (count($blogs) > 0) {
             // sitemap for blogs
            $blog_sitemap = Sitemap::create();
            foreach ($blogs as $blog) {
                $blog_sitemap->add(Url::create('/blog/'.$blog->slug)->setLastModificationDate(Carbon::parse($blog->last_modified_at)));
            }
            
            $blog_sitemap->writeToFile(public_path('sitemap/blog-sitemap.xml'));
        }
    }
}
