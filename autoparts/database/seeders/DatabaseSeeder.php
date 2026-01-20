<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Driver;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Wallet;
use App\Models\LoyaltyPoints;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@autoparts.sa',
            'phone' => '0500000000',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'status' => 'active',
            'is_verified' => true,
        ]);
        Wallet::create(['user_id' => $admin->id]);
        LoyaltyPoints::create(['user_id' => $admin->id]);

        // Create Store Owner
        $storeOwner = User::create([
            'name' => 'صاحب المتجر',
            'email' => 'store@autoparts.sa',
            'phone' => '0500000001',
            'password' => Hash::make('password'),
            'user_type' => 'store_individual',
            'status' => 'active',
            'is_verified' => true,
        ]);
        Wallet::create(['user_id' => $storeOwner->id]);
        LoyaltyPoints::create(['user_id' => $storeOwner->id]);

        // Create Store
        $store = Store::create([
            'user_id' => $storeOwner->id,
            'name' => 'متجر قطع الغيار الأصلية',
            'slug' => 'original-parts-store',
            'description' => 'متجر متخصص في بيع قطع غيار السيارات الأصلية',
            'phone' => '0500000001',
            'email' => 'store@autoparts.sa',
            'city' => 'الرياض',
            'region' => 'الرياض',
            'address' => 'حي العليا، شارع الملك فهد',
            'status' => 'active',
            'is_verified' => true,
            'rating' => 4.5,
        ]);

        // Create Driver
        $driverUser = User::create([
            'name' => 'أحمد الموصل',
            'email' => 'driver@autoparts.sa',
            'phone' => '0500000002',
            'password' => Hash::make('password'),
            'user_type' => 'driver_individual',
            'status' => 'active',
            'is_verified' => true,
        ]);
        Wallet::create(['user_id' => $driverUser->id]);
        LoyaltyPoints::create(['user_id' => $driverUser->id]);

        Driver::create([
            'user_id' => $driverUser->id,
            'license_number' => '1234567890',
            'status' => 'available',
            'is_verified' => true,
            'rating' => 4.8,
        ]);

        // Create Buyer
        $buyer = User::create([
            'name' => 'محمد المشتري',
            'email' => 'buyer@autoparts.sa',
            'phone' => '0500000003',
            'password' => Hash::make('password'),
            'user_type' => 'buyer_individual',
            'status' => 'active',
            'is_verified' => true,
        ]);
        Wallet::create(['user_id' => $buyer->id]);
        LoyaltyPoints::create(['user_id' => $buyer->id]);

        // Create Categories
        $categories = [
            ['name' => 'المحرك', 'name_en' => 'Engine', 'slug' => 'engine', 'icon' => 'bi-gear'],
            ['name' => 'الفرامل', 'name_en' => 'Brakes', 'slug' => 'brakes', 'icon' => 'bi-disc'],
            ['name' => 'الكهرباء', 'name_en' => 'Electrical', 'slug' => 'electrical', 'icon' => 'bi-lightning'],
            ['name' => 'التعليق', 'name_en' => 'Suspension', 'slug' => 'suspension', 'icon' => 'bi-arrow-down-up'],
            ['name' => 'التبريد', 'name_en' => 'Cooling', 'slug' => 'cooling', 'icon' => 'bi-thermometer-snow'],
            ['name' => 'الفلاتر', 'name_en' => 'Filters', 'slug' => 'filters', 'icon' => 'bi-funnel'],
            ['name' => 'الزيوت والسوائل', 'name_en' => 'Oils & Fluids', 'slug' => 'oils', 'icon' => 'bi-droplet'],
            ['name' => 'الإضاءة', 'name_en' => 'Lighting', 'slug' => 'lighting', 'icon' => 'bi-lightbulb'],
        ];

        foreach ($categories as $cat) {
            Category::create(array_merge($cat, ['is_active' => true, 'is_featured' => true]));
        }

        // Create Brands
        $brands = [
            ['name' => 'تويوتا', 'name_en' => 'Toyota', 'slug' => 'toyota'],
            ['name' => 'هيونداي', 'name_en' => 'Hyundai', 'slug' => 'hyundai'],
            ['name' => 'نيسان', 'name_en' => 'Nissan', 'slug' => 'nissan'],
            ['name' => 'فورد', 'name_en' => 'Ford', 'slug' => 'ford'],
            ['name' => 'شيفروليه', 'name_en' => 'Chevrolet', 'slug' => 'chevrolet'],
            ['name' => 'هوندا', 'name_en' => 'Honda', 'slug' => 'honda'],
            ['name' => 'بوش', 'name_en' => 'Bosch', 'slug' => 'bosch'],
            ['name' => 'دينسو', 'name_en' => 'Denso', 'slug' => 'denso'],
        ];

        foreach ($brands as $brand) {
            Brand::create(array_merge($brand, ['is_active' => true, 'is_featured' => true]));
        }

        // Create Car Makes
        $makes = [
            ['name' => 'تويوتا', 'name_en' => 'Toyota', 'slug' => 'toyota', 'country_of_origin' => 'Japan'],
            ['name' => 'هيونداي', 'name_en' => 'Hyundai', 'slug' => 'hyundai', 'country_of_origin' => 'South Korea'],
            ['name' => 'نيسان', 'name_en' => 'Nissan', 'slug' => 'nissan', 'country_of_origin' => 'Japan'],
            ['name' => 'فورد', 'name_en' => 'Ford', 'slug' => 'ford', 'country_of_origin' => 'USA'],
            ['name' => 'شيفروليه', 'name_en' => 'Chevrolet', 'slug' => 'chevrolet', 'country_of_origin' => 'USA'],
        ];

        foreach ($makes as $make) {
            $carMake = CarMake::create(array_merge($make, ['is_active' => true]));
            
            // Add models for each make
            $models = [
                ['name' => 'كامري', 'slug' => 'camry'],
                ['name' => 'كورولا', 'slug' => 'corolla'],
                ['name' => 'هايلكس', 'slug' => 'hilux'],
            ];
            
            foreach ($models as $model) {
                CarModel::create([
                    'car_make_id' => $carMake->id,
                    'name' => $model['name'],
                    'slug' => $model['slug'],
                    'is_active' => true,
                ]);
            }
        }

        // Create Products
        $products = [
            [
                'name' => 'فلتر زيت تويوتا أصلي',
                'description' => 'فلتر زيت أصلي من تويوتا مناسب لجميع موديلات كامري وكورولا',
                'price' => 45.00,
                'quantity' => 100,
                'category_id' => 6, // Filters
                'brand_id' => 1, // Toyota
            ],
            [
                'name' => 'بطارية بوش 70 أمبير',
                'description' => 'بطارية بوش ألمانية الصنع عالية الجودة',
                'price' => 450.00,
                'sale_price' => 399.00,
                'quantity' => 50,
                'category_id' => 3, // Electrical
                'brand_id' => 7, // Bosch
            ],
            [
                'name' => 'تيل فرامل أمامي هيونداي',
                'description' => 'تيل فرامل أمامي أصلي لسيارات هيونداي',
                'price' => 180.00,
                'quantity' => 75,
                'category_id' => 2, // Brakes
                'brand_id' => 2, // Hyundai
            ],
            [
                'name' => 'مضخة ماء نيسان',
                'description' => 'مضخة ماء أصلية لمحركات نيسان',
                'price' => 350.00,
                'quantity' => 30,
                'category_id' => 5, // Cooling
                'brand_id' => 3, // Nissan
            ],
            [
                'name' => 'زيت محرك موبيل 1 5W-30',
                'description' => 'زيت محرك صناعي بالكامل من موبيل 1',
                'price' => 120.00,
                'quantity' => 200,
                'category_id' => 7, // Oils
                'brand_id' => 8, // Denso
            ],
            [
                'name' => 'شمعات إشعال دينسو إيريديوم',
                'description' => 'شمعات إشعال إيريديوم عالية الأداء',
                'price' => 85.00,
                'quantity' => 150,
                'category_id' => 1, // Engine
                'brand_id' => 8, // Denso
            ],
            [
                'name' => 'مصابيح LED أمامية',
                'description' => 'مصابيح LED عالية الإضاءة للسيارات',
                'price' => 250.00,
                'sale_price' => 199.00,
                'quantity' => 60,
                'category_id' => 8, // Lighting
                'brand_id' => 7, // Bosch
            ],
            [
                'name' => 'مساعدين أمامي شيفروليه',
                'description' => 'مساعدين أمامي أصلي لسيارات شيفروليه',
                'price' => 800.00,
                'quantity' => 25,
                'category_id' => 4, // Suspension
                'brand_id' => 5, // Chevrolet
            ],
        ];

        foreach ($products as $index => $productData) {
            Product::create([
                'store_id' => $store->id,
                'name' => $productData['name'],
                'slug' => 'product-' . ($index + 1) . '-' . uniqid(),
                'sku' => 'SKU-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'description' => $productData['description'],
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'] ?? null,
                'quantity' => $productData['quantity'],
                'category_id' => $productData['category_id'],
                'brand_id' => $productData['brand_id'],
                'status' => 'active',
                'is_featured' => $index < 4,
                'is_new_arrival' => $index >= 4,
                'condition' => 'new',
            ]);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Test Accounts:');
        $this->command->info('Admin: admin@autoparts.sa / password');
        $this->command->info('Store: store@autoparts.sa / password');
        $this->command->info('Driver: driver@autoparts.sa / password');
        $this->command->info('Buyer: buyer@autoparts.sa / password');
    }
}
