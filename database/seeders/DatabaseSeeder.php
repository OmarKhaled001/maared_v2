<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Branch;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'عمر خالد',
                'phone' => '01068778340',
                'is_admin' =>1,
                'email' => 'omarkhaled@maared.com',
                'password' => Hash::make('omarkhaled@maared.com')
            ],
            [
                'name' => 'محمد السيد اسماعيل',
                'phone' => '01018986369',
                'email' => 'mohamedelsayed@maared.com',
                'password' => Hash::make('mohamedelsayed@maared.com')
            ],
            [
                'name' => 'وفاء اشرف',
                'is_admin' =>1,

                'phone' => '01155046151',
                'email' => 'wafaaashraf@maared.com',
                'password' => Hash::make('wafaaashraf@maared.com')
            ],
            [
                'name' => 'عبد الرحمن خالد شعبان',
                'is_admin' =>1,

                'phone' => '01110578328',
                'email' => 'abdelrahman@maared.com',
                'password' => Hash::make('abdelrahman@maared.com')
            ],
            [
                'name' => 'زياد ياسر محمود',
                'phone' => '01119915912',
                'email' => 'ziadyasser@maared.com',
                'password' => Hash::make('ziadyasser@maared.com')
            ],
            [
                'name' => 'بسمله ايهاب',
                'phone' => '01022821650',
                'email' => 'basmaehab@maared.com',
                'password' => Hash::make('basmaehab@maared.com')
            ],
            [
                'name' => 'محمد عواد',
                'phone' => '01101802067',
                'email' => 'mohamedawad@maared.com',
                'password' => Hash::make('mohamedawad@maared.com')
            ],
            [
                'name' => 'سكينه عبدالحميد',
                'phone' => '01126336111',
                'email' => 'sakinaabdelhameed@maared.com',
                'password' => Hash::make('sakinaabdelhameed@maared.com')
            ],
            [
                'name' => 'اسامه ابراهيم',
                'phone' => '01032593295',
                'email' => 'osamaibrahim@maared.com',
                'password' => Hash::make('osamaibrahim@maared.com')
            ],
            [
                'name' => 'همس وائل',
                'phone' => '01288302022',
                'email' => 'hamaswael@maared.com',
                'password' => Hash::make('hamaswael@maared.com')
            ],
            [
                'name' => 'احمد اسامة',
                'phone' => '01129826208',
                'email' => 'ahmedosama@maared.com',
                'password' => Hash::make('ahmedosama@maared.com')
            ],
            [
                'name' => 'هايدى محمد',
                'phone' => '01557139029',
                'email' => 'haidymohamed@maared.com',
                'password' => Hash::make('haidymohamed@maared.com')
            ],
            [
                'name' => 'احمد السيد',
                'phone' => '01276415322',
                'email' => 'ahmedelsayed@maared.com',
                'password' => Hash::make('ahmedelsayed@maared.com')
            ],
            [
                'name' => 'هدي اياد',
                'phone' => '01007878365',
                'is_admin' =>1,

                'email' => 'hodaeyead@maared.com',
                'password' => Hash::make('hodayeid@maared.com')
            ],
            [
                'name' => 'بسملة عادل',
                'phone' => '01158828623',
                'email' => 'basmaadel@maared.com',
                'password' => Hash::make('basmaadel@maared.com')
            ],
            [
                'name' => 'مصطفى عبداللطيف',
                'phone' => '01097688118',
                'email' => 'mostafaabdellatif@maared.com',
                'password' => Hash::make('mostafaabdellatif@maared.com')
            ],
            [
                'name' => 'هاجر عادل',
                'phone' => '01033613378',
                'email' => 'hagaradel@maared.com',
                'password' => Hash::make('hagaradel@maared.com')
            ],
            [
                'name' => 'ميرنا سيد',
                'phone' => '01555446907',
                'email' => 'mirenasayed@maared.com',
                'password' => Hash::make('mirenasayed@maared.com')
            ],
            [
                'name' => 'هاجر ناصر',
                'phone' => '01066919302',
                'email' => 'hagarnasser@maared.com',
                'password' => Hash::make('hagarnasser@maared.com')
            ],
            [
                'name' => 'يوسف سامح محمد',
                'phone' => '01104338976',
                'email' => 'youssefsameh@maared.com',
                'password' => Hash::make('youssefsameh@maared.com')
            ],
            [
                'name' => 'يوسف منتصر',
                'phone' => '01118284686',
                'email' => 'youselfmontaser@maared.com',
                'password' => Hash::make('youselfmontaser@maared.com')
            ],
            [
                'name' => 'محمد فرج',
                'phone' => '01027698409',
                'email' => 'mohamedfarag@maared.com',
                'password' => Hash::make('mohamedfarag@maared.com')
            ],
            [
                'name' => 'ياسمين بيومي',
                'phone' => '01017661373',
                'email' => 'yasminbayoumi@maared.com',
                'password' => Hash::make('yasminbayoumi@maared.com')
            ],
            [
                'name' => 'سهيلة ايمن',
                'phone' => '01142989368',
                'email' => 'sohaylaayman@maared.com',
                'password' => Hash::make('sohaylaayman@maared.com')
            ],
            [
                'name' => 'حسناء هشام حسن',
                'phone' => '01119374566',
                'email' => 'hasnaahesham@maared.com',
                'password' => Hash::make('hasnaahesham@maared.com')
            ],
            [
                'name' => 'مريم عادل عبد الحفيظ',
                'phone' => '01118826316',
                'email' => 'mariamadel@maared.com',
                'password' => Hash::make('mariamadel@maared.com')
            ],
            [
                'name' => 'ريم ياسر',
                'phone' => '01149960387',
                'email' => 'reemyasser@maared.com',
                'password' => Hash::make('reemyasser@maared.com')
            ],
            [
                'name' => 'ملك شريف كمال',
                'phone' => '01010074917',
                'email' => 'malakshareef@maared.com',
                'password' => Hash::make('malakshareef@maared.com')
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
            if ($userData['is_admin'] ?? false) {
                $superAdminRole = Role::firstOrCreate([
                    'name' => 'super_admin',
                    'guard_name' => 'filament'
                ]);
                
                $superAdminRole->givePermissionTo(
                    config('filament-shield.permission_prefix') . 'all'
                );
                
                
            }
        }


 
    }
}
