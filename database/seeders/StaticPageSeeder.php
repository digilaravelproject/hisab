<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'content' => 'Please update privacy policy content.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'terms-and-conditions',
                'title' => 'Terms and Conditions',
                'content' => 'Please update terms and conditions content.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'faq',
                'title' => 'FAQs',
                'content' => 'Please update FAQs content.',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($pages as $page) {
            StaticPage::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
