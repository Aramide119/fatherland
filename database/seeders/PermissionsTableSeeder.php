<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'member_management_access',
            ],
            [
                'id'    => 18,
                'title' => 'member_edit',
            ],
            [
                'id'    => 19,
                'title' => 'member_show',
            ],
            [
                'id'    => 20,
                'title' => 'member_delete',
            ],
            [
                'id'    => 21,
                'title' => 'member_access',
            ],
            [
                'id'    => 22,
                'title' => 'restaurants_management_access',
            ],
            [
                'id'    => 23,
                'title' => 'advert_management_access',
            ],
            [
                'id'    => 24,
                'title' => 'advert_create',
            ],
            [
                'id'    => 25,
                'title' => 'advert_edit',
            ],
            [
                'id'    => 26,
                'title' => 'advert_show',
            ],
            [
                'id'    => 27,
                'title' => 'advert_delete',
            ],
            [
                'id'    => 28,
                'title' => 'advert_access',
            ],
            [
                'id'    => 29,
                'title' => 'restaurant_create',
            ],
            [
                'id'    => 30,
                'title' => 'restaurant_edit',
            ],
            [
                'id'    => 31,
                'title' => 'restaurant_show',
            ],
            [
                'id'    => 32,
                'title' => 'restaurant_delete',
            ],
            [
                'id'    => 33,
                'title' => 'restaurant_access',
            ],
            [
                'id'    => 34,
                'title' => 'advert_category_create',
            ],
            [
                'id'    => 35,
                'title' => 'advert_category_edit',
            ],
            [
                'id'    => 36,
                'title' => 'advert_category_show',
            ],
            [
                'id'    => 37,
                'title' => 'advert_category_delete',
            ],
            [
                'id'    => 38,
                'title' => 'advert_category_access',
            ],
            [
                'id'    => 39,
                'title' => 'news_management_access',
            ],
            [
                'id'    => 40,
                'title' => 'resources_management_access',
            ],
            [
                'id'    => 41,
                'title' => 'news_create',
            ],
            [
                'id'    => 42,
                'title' => 'news_edit',
            ],
            [
                'id'    => 43,
                'title' => 'news_show',
            ],
            [
                'id'    => 44,
                'title' => 'news_delete',
            ],
            [
                'id'    => 45,
                'title' => 'news_access',
            ],
            [
                'id'    => 46,
                'title' => 'resource_category_create',
            ],
            [
                'id'    => 47,
                'title' => 'resource_category_edit',
            ],
            [
                'id'    => 48,
                'title' => 'resource_category_show',
            ],
            [
                'id'    => 49,
                'title' => 'resource_category_delete',
            ],
            [
                'id'    => 50,
                'title' => 'resource_category_access',
            ],
            [
                'id'    => 51,
                'title' => 'news_category_create',
            ],
            [
                'id'    => 52,
                'title' => 'news_category_edit',
            ],
            [
                'id'    => 53,
                'title' => 'news_category_show',
            ],
            [
                'id'    => 54,
                'title' => 'news_category_delete',
            ],
            [
                'id'    => 55,
                'title' => 'news_category_access',
            ],
            [
                'id'    => 56,
                'title' => 'event_management_access',
            ],
            [
                'id'    => 57,
                'title' => 'resource_create',
            ],
            [
                'id'    => 58,
                'title' => 'resource_edit',
            ],
            [
                'id'    => 59,
                'title' => 'resource_show',
            ],
            [
                'id'    => 60,
                'title' => 'resource_delete',
            ],
            [
                'id'    => 61,
                'title' => 'resource_access',
            ],
            [
                'id'    => 62,
                'title' => 'travels_and_spirituality_access',
            ],
            [
                'id'    => 63,
                'title' => 'event_create',
            ],
            [
                'id'    => 64,
                'title' => 'event_edit',
            ],
            [
                'id'    => 65,
                'title' => 'event_show',
            ],
            [
                'id'    => 66,
                'title' => 'event_delete',
            ],
            [
                'id'    => 67,
                'title' => 'event_access',
            ],
            [
                'id'    => 68,
                'title' => 'event_category_create',
            ],
            [
                'id'    => 69,
                'title' => 'event_category_edit',
            ],
            [
                'id'    => 70,
                'title' => 'event_category_show',
            ],
            [
                'id'    => 71,
                'title' => 'event_category_delete',
            ],
            [
                'id'    => 72,
                'title' => 'event_category_access',
            ],
            [
                'id'    => 73,
                'title' => 'travel_create',
            ],
            [
                'id'    => 74,
                'title' => 'travel_edit',
            ],
            [
                'id'    => 75,
                'title' => 'travel_show',
            ],
            [
                'id'    => 76,
                'title' => 'travel_delete',
            ],
            [
                'id'    => 77,
                'title' => 'travel_access',
            ],
            [
                'id'    => 78,
                'title' => 'store_management_access',
            ],
            [
                'id'    => 79,
                'title' => 'travel_order_create',
            ],
            [
                'id'    => 80,
                'title' => 'travel_order_edit',
            ],
            [
                'id'    => 81,
                'title' => 'travel_order_show',
            ],
            [
                'id'    => 82,
                'title' => 'travel_order_delete',
            ],
            [
                'id'    => 83,
                'title' => 'travel_order_access',
            ],
            [
                'id'    => 84,
                'title' => 'product_create',
            ],
            [
                'id'    => 85,
                'title' => 'product_edit',
            ],
            [
                'id'    => 86,
                'title' => 'product_show',
            ],
            [
                'id'    => 87,
                'title' => 'product_delete',
            ],
            [
                'id'    => 88,
                'title' => 'product_access',
            ],
            [
                'id'    => 89,
                'title' => 'product_category_create',
            ],
            [
                'id'    => 90,
                'title' => 'product_category_edit',
            ],
            [
                'id'    => 91,
                'title' => 'product_category_show',
            ],
            [
                'id'    => 92,
                'title' => 'product_category_delete',
            ],
            [
                'id'    => 93,
                'title' => 'product_category_access',
            ],
            [
                'id'    => 94,
                'title' => 'product_sub_category_create',
            ],
            [
                'id'    => 95,
                'title' => 'product_sub_category_edit',
            ],
            [
                'id'    => 96,
                'title' => 'product_sub_category_show',
            ],
            [
                'id'    => 97,
                'title' => 'product_sub_category_delete',
            ],
            [
                'id'    => 98,
                'title' => 'product_sub_category_access',
            ],
            [
                'id'    => 99,
                'title' => 'product_variation_create',
            ],
            [
                'id'    => 100,
                'title' => 'product_variation_edit',
            ],
            [
                'id'    => 101,
                'title' => 'product_variation_show',
            ],
            [
                'id'    => 102,
                'title' => 'product_variation_delete',
            ],
            [
                'id'    => 103,
                'title' => 'product_variation_access',
            ],
            [
                'id'    => 104,
                'title' => 'product_rating_show',
            ],
            [
                'id'    => 105,
                'title' => 'product_rating_delete',
            ],
            [
                'id'    => 106,
                'title' => 'product_rating_access',
            ],
            [
                'id'    => 107,
                'title' => 'explore_cultural_learning_access',
            ],
            [
                'id'    => 108,
                'title' => 'course_create',
            ],
            [
                'id'    => 109,
                'title' => 'course_edit',
            ],
            [
                'id'    => 110,
                'title' => 'course_show',
            ],
            [
                'id'    => 111,
                'title' => 'course_delete',
            ],
            [
                'id'    => 112,
                'title' => 'course_access',
            ],
            [
                'id'    => 113,
                'title' => 'event_order_create',
            ],
            [
                'id'    => 114,
                'title' => 'event_order_edit',
            ],
            [
                'id'    => 115,
                'title' => 'event_order_show',
            ],
            [
                'id'    => 116,
                'title' => 'event_order_delete',
            ],
            [
                'id'    => 117,
                'title' => 'event_order_access',
            ],
            [
                'id'    => 118,
                'title' => 'advert_inquiry_create',
            ],
            [
                'id'    => 119,
                'title' => 'advert_inquiry_edit',
            ],
            [
                'id'    => 120,
                'title' => 'advert_inquiry_show',
            ],
            [
                'id'    => 121,
                'title' => 'advert_inquiry_delete',
            ],
            [
                'id'    => 122,
                'title' => 'advert_inquiry_access',
            ],
            [
                'id'    => 124,
                'title' => 'payment_management_access',
            ],
            [
                'id'    => 125,
                'title' => 'post_management_access',
            ],
            [
                'id'    => 126,
                'title' => 'family_management_access',
            ],
            [
                'id'    => 127,
                'title' => 'dynasty_management_access',
            ],
            [
                'id'    => 128,
                'title' => 'events_management_access',
            ],
            [
                'id'    => 129,
                'title' => 'comments_management_access',
            ],
            [
                'id'    => 130,
                'title' => 'profile_password_edit',
            ],
            [
                'id'    => 131,
                'title' => 'content_type_create',
            ],
            [
                'id'    => 132,
                'title' => 'content_type_edit',
            ],
            [
                'id'    => 133,
                'title' => 'content_type_show',
            ],
            [
                'id'    => 134,
                'title' => 'content_type_delete',
            ],
            [
                'id'    => 135,
                'title' => 'content_type_access',
            ],
            [
                'id'    => 136,
                'title' => 'content_category_create',
            ],
            [
                'id'    => 137,
                'title' => 'content_category_edit',
            ],
            [
                'id'    => 138,
                'title' => 'content_category_show',
            ],
            [
                'id'    => 139,
                'title' => 'content_category_delete',
            ],
            [
                'id'    => 140,
                'title' => 'content_category_access',
            ],
            [
                'id'    => 141,
                'title' => 'content_create',
            ],
            [
                'id'    => 142,
                'title' => 'content_edit',
            ],
            [
                'id'    => 143,
                'title' => 'content_show',
            ],
            [
                'id'    => 144,
                'title' => 'content_delete',
            ],
            [
                'id'    => 145,
                'title' => 'content_access',
            ],
            [
                'id'    => 146,
                'title' => 'records_management_access',
            ],
            [
                'id'    => 147,
                'title' => 'record_create',
            ],
            [
                'id'    => 148,
                'title' => 'record_edit',
            ],
            [
                'id'    => 149,
                'title' => 'record_show',
            ],
            [
                'id'    => 150,
                'title' => 'record_delete',
            ],
            [
                'id'    => 151,
                'title' => 'record_access',
            ],
            [
                'id'    => 152,
                'title' => 'interests_management_access',
            ],
            [
                'id'    => 153,
                'title' => 'interest_create',
            ],
            [
                'id'    => 154,
                'title' => 'interest_edit',
            ],
            [
                'id'    => 155,
                'title' => 'interest_show',
            ],
            [
                'id'    => 156,
                'title' => 'interest_delete',
            ],
            [
                'id'    => 157,
                'title' => 'interest_access',
            ],
            [
                'id'    => 158,
                'title' => 'families_create',
            ],
            [
                'id'    => 159,
                'title' => 'colors_access',
            ],
            [
                'id'    => 160,
                'title' => 'colors_create',
            ],
            [
                'id'    => 161,
                'title' => 'colors_edit',
            ],
            [
                'id'    => 162,
                'title' => 'colors_show',
            ],
            [
                'id'    => 163,
                'title' => 'colors_delete',
            ],

        ];


        Permission::insert($permissions);
    }
}
