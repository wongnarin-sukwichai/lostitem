<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Location;
use App\Models\SystemSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Category::truncate();
        Location::truncate();
        SystemSetting::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Category::insert([
            ['category_id' => 1,  'category_name' => 'อุปกรณ์อิเล็กทรอนิกส์', 'status' => 1],
            ['category_id' => 2,  'category_name' => 'กระเป๋าและสิ่งของมีค่า', 'status' => 1],
            ['category_id' => 3,  'category_name' => 'กุญแจและเครื่องใช้', 'status' => 1],
            ['category_id' => 4, 'category_name' => 'เสื้อผ้าและของใช้ส่วนตัว', 'status' => 1],
            ['category_id' => 5, 'category_name' => 'เอกสารและบัตรต่างๆ', 'status' => 1],
        ]);

        Location::insert([
            ['location_id' => 1, 'location_name' => 'สำนักวิทยบริการ (ตึก A)', 'status' => 1],
            ['location_id' => 2, 'location_name' => 'Digital Learning Park (ตึก B)', 'status' => 1],
            ['location_id' => 3, 'location_name' => 'MSU Space', 'status' => 1],
        ]);

        SystemSetting::insert([
            ['setting_key' => 'contact_info', 'setting_value' => '<strong>หากทรัพย์สินนี้เป็นของคุณ</strong> <br>โปรดนำหลักฐานแสดงความเป็นเจ้าของ ติดต่อขอรับคืนได้ที่ <br><u>เคาน์เตอร์บริการ สำนักวิทยบริการ (ชั้น 2)</u>'],
        ]);

        $this->call(AdminUserSeeder::class);
    }
}
