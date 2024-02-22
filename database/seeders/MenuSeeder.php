<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::insert([
            [
                'name' => 'Dashboard',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'home',
                'route' => 'cms.dashboard',
                'ordering' => '1',
            ],
            // Master Data
            [
                'name' => 'Barang',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'box',
                'route' => 'cms.goods',
                'ordering' => '2',
            ],
            [
                'name' => 'Pembelian',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'box',
                'route' => 'cms.purchase',
                'ordering' => '3',
            ],
            [
                'name' => 'Pembelian Persetujuan',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'alert-triangle',
                'route' => 'cms.purchase.approval',
                'ordering' => '4',
            ],
            // Settings
            [
                'name' => 'Settings',
                'on' => 'cms',
                'type' => 'header',
                'icon' => '#',
                'route' => '#',
                'ordering' => '30',
            ],
            [
                'name' => 'Menu',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'menu',
                'route' => 'cms.management.menu',
                'ordering' => '31',
            ],
            [
                'name' => 'Role',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'lock',
                'route' => 'cms.management.role',
                'ordering' => '33',
            ],
            [
                'name' => 'User',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'user',
                'route' => 'cms.management.user',
                'ordering' => '34',
            ],
            [
                'name' => 'Website',
                'on' => 'cms',
                'type' => 'item',
                'icon' => 'settings',
                'route' => 'cms.management.setting',
                'ordering' => '35',
            ],
        ]);
    }
}
