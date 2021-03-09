<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%country}}`.
 */
class m201109_172533_create_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $tableOptions = null;
      if ($this->db->driverName === 'mysql') {
          // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
          $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci	 ENGINE=InnoDB';
      }


        $this->createTable('{{%country}}', [
            'country_id' => $this->primaryKey(),
            'country_name' => $this->string(80),
            'country_name_ar' => $this->string(80),
            'iso' => $this->char(2),
            'emoji' => $this->char(3),
            'country_code' => $this->integer(3),
        ], $tableOptions);

        $sql = "
        INSERT INTO `country` (`country_id`, `country_name`, `country_name_ar`, `iso`, `emoji`, `country_code`) VALUES
        (1, 'Afghanistan', 'أفغانستان', 'AF', '🇦🇫', 93),
        (2, 'Albania', 'ألبانيا', 'AL', '🇦🇱', 355),
        (3, 'Algeria', 'الجزائر', 'DZ', '🇩🇿', 213),
        (4, 'Andorra', 'أندورا', 'AD', '🇦🇩', 376),
        (5, 'Angola', 'أنغولا', 'AO', '🇦🇴', 244),
        (6, 'Argentina', 'الأرجنتين', 'AR', '🇦🇷', 54),
        (7, 'Armenia', 'أرمينيا', 'AM', '🇦🇲', 374),
        (8, 'Australia', 'أستراليا', 'AU', '🇦🇺', 61),
        (9, 'Austria', 'النمسا', 'AT', '🇦🇹', 43),
        (10, 'Azerbaijan', 'أذربيجان', 'AZ', '🇦🇿', 994),
        (11, 'Bahamas', 'جزر البهاما', 'BS', '🇧🇸', 1242),
        (12, 'Bahrain', 'البحرين', 'BH', '🇧🇭', 973),
        (13, 'Bangladesh', 'بنغلاديش', 'BD', '🇧🇩', 973),
        (14, 'Barbados', 'بربادوس', 'BB', '🇧🇧', 1246),
        (15, 'Belarus', 'روسيا البيضاء', 'BY', '🇧🇾', 375),
        (16, 'Belgium', 'بلجيكا', 'BE', '🇧🇪', 32),
        (17, 'Belize', 'بليز', 'BZ', '🇧🇿', 501),
        (18, 'Benin', 'بنين', 'BJ', '🇧🇯', 229),
        (19, 'Bhutan', 'بوتان', 'BT', '🇧🇹', 975),
        (20, 'Bolivia', 'بوليفيا', 'BO', '🇧🇴', 591),
        (21, 'Bosnia-Herzegovina', 'البوسنة والهرسك', 'BA', '🇧🇦', 387),
        (22, 'Botswana', 'بوتسوانا', 'BW', '🇧🇼', 267),
        (23, 'Brazil', 'البرازيل', 'BR', '🇧🇷', 55),
        (24, 'Britain', 'بريطانيا', 'GB', '🇬🇧', 44),
        (25, 'Brunei', 'بروناي', 'BN', '🇧🇳', 673),
        (26, 'Bulgaria', 'بلغاريا', 'BG', '🇧🇬', 359),
        (27, 'Burkina', 'وركينا فاسو', 'BF', '🇧🇫', 226),
        (28, 'Burma', 'بورما', 'MM', '🇲🇲', 95),
        (29, 'Burundi', 'بوروندي', 'BI', '🇧🇮', 257),
        (30, 'Cambodia', 'كمبوديا', 'KH', '🇰🇭', 855),
        (31, 'Cameroon', 'الكاميرون', 'CM', '🇨🇲', 237),
        (32, 'Canada', 'كندا', 'CA', '🇨🇦', 1),
        (33, 'Cape Verde Islands', 'جزر الرأس الأخضر', 'CV', '🇨🇻', 238),
        (34, 'Chad', 'تشاد', 'TD', '🇹🇩', 235),
        (35, 'Chile', 'تشيلي', 'CL', '🇨🇱', 56),
        (36, 'China', 'الصين', 'CN', '🇨🇳', 86),
        (37, 'Colombia', 'كولومبيا', 'CO', '🇨🇴', 57),
        (38, 'Congo', 'الكونغو', 'CG', '🇨🇬', 243),
        (39, 'Costa Rica', 'كوستا ريكا', 'CR', '🇨🇷', 506),
        (40, 'Croatia', 'كرواتيا', 'HR', '🇭🇷', 385),
        (41, 'Cuba', 'كوبا', 'CU', '🇨🇺', 53),
        (42, 'Cyprus', 'قبرص', 'CY', '🇨🇾', 357),
        (43, 'Czech Republic', 'جمهورية التشيك', 'CZ', '🇨🇿', 420),
        (44, 'Denmark', 'الدنمارك', 'DK', '🇩🇰', 45),
        (45, 'Djibouti', 'جيبوتي', 'DJ', '🇩🇯', 253),
        (46, 'Dominica', 'دومينيكا', 'DM', '🇩🇲', 767),
        (47, 'Dominican Republic', 'جمهورية الدومينيكان', 'DO', '🇩🇴', 809),
        (48, 'Ecuador', 'الإكوادور', 'EC', '🇪🇨', 593),
        (49, 'Egypt', 'مصر', 'EG', '🇪🇬', 20),
        (50, 'El Salvador', 'السلفادور', 'SV', '🇸🇻', 503),
        (52, 'Eritrea', 'إريتريا', 'ER', '🇪🇷', 291),
        (53, 'Estonia', 'استونيا', 'EE', '🇪🇪', 372),
        (54, 'Ethiopia', 'أثيوبيا', 'ET', '🇪🇹', 251),
        (55, 'Fiji', 'فيجي', 'FJ', '🇫🇯', 679),
        (56, 'Finland', 'فنلندا', 'FI', '🇫🇮', 358),
        (57, 'France', 'فرنسا', 'FR', '🇫🇷', 33),
        (58, 'Gabon', 'الغابون', 'GA', '🇬🇦', 241),
        (59, 'Gambia', 'غامبيا', 'GM', '🇬🇲', 220),
        (60, 'Georgia', 'جورجيا', 'GE', '🇬🇪', 995),
        (61, 'Germany', 'ألمانيا', 'DE', '🇩🇪', 49),
        (62, 'Ghana', 'غانا', 'GH', '🇬🇭', 233),
        (63, 'Greece', 'يونان', 'GR', '🇬🇷', 30),
        (64, 'Grenada', 'غرينادا', 'GD', '🇬🇩', 473),
        (65, 'Guatemala', 'غواتيمالا', 'GT', '🇬🇹', 502),
        (66, 'Guinea', 'غينيا', 'GN', '🇬🇳', 224),
        (67, 'Guyana', 'غيانا', 'GY', '🇬🇾', 592),
        (68, 'Haiti', 'هايتي', 'HT', '🇭🇹', 509),
        (70, 'Honduras', 'هندوراس', 'HN', '🇭🇳', 504),
        (71, 'Hungary', 'هنغاريا', 'HU', '🇭🇺', 36),
        (72, 'Iceland', 'أيسلندا', 'IS', '🇮🇸', 354),
        (73, 'India', 'الهند', 'IN', '🇮🇳', 91),
        (74, 'Indonesia', 'أندونيسيا', 'ID', '🇮🇩', 62),
        (75, 'Iran', 'إيران', 'IR', '🇮🇷', 98),
        (76, 'Iraq', 'العراق', 'IQ', '🇮🇶', 964),
        (77, 'Ireland', 'ايرلندا', 'IE', '🇮🇪', 353),
        (78, 'Italy', 'إيطاليا', 'IT', '🇮🇹', 39),
        (79, 'Jamaica', 'جامايكا', 'JM', '🇯🇲', 876),
        (80, 'Japan', 'اليابان', 'JP', '🇯🇵', 81),
        (81, 'Jordan', 'الأردن', 'JO', '🇯🇴', 962),
        (82, 'Kazakhstan', 'كازاخستان', 'KZ', '🇰🇿', 7),
        (83, 'Kenya', 'كينيا', 'KE', '🇰🇪', 254),
        (84, 'Kuwait', 'الكويت', 'KW', '🇰🇼', 965),
        (85, 'Laos', 'لاوس', 'LA', '🇱🇦', 853),
        (86, 'Latvia', 'لاتفيا', 'LV', '🇱🇻', 371),
        (87, 'Lebanon', 'لبنان', 'LB', '🇱🇧', 961),
        (88, 'Liberia', 'ليبيريا', 'LR', '🇱🇷', 231),
        (89, 'Libya', 'ليبيا', 'LY', '🇱🇾', 281),
        (90, 'Lithuania', 'ليتوانيا', 'LT', '🇱🇹', 370),
        (91, 'Macedonia', 'مقدونيا', 'MK', '🇲🇰', 389),
        (92, 'Madagascar', 'مدغشقر', 'MG', '🇲🇬', 261),
        (93, 'Malawi', 'ملاوي', 'MW', '🇲🇼', 265),
        (94, 'Malaysia', 'ماليزيا', 'MY', '🇲🇾', 60),
        (95, 'Maldives', 'جزر المالديف', 'MV', '🇲🇻', 960),
        (96, 'Mali', 'مالي', 'ML', '🇲🇱', 223),
        (97, 'Malta', 'مالطا', 'MT', '🇲🇹', 356),
        (98, 'Mauritania', 'موريتانيا', 'MR', '🇲🇷', 222),
        (99, 'Mauritius', 'موريشيوس', 'MU', '🇲🇺', 230),
        (100, 'Mexico', 'المكسيك', 'MX', '🇲🇽', 52),
        (101, 'Moldova', 'مولدوفا', 'MD', '🇲🇩', 373),
        (102, 'Monaco', 'موناكو', 'MC', '🇲🇨', 377),
        (103, 'Mongolia', 'منغوليا', 'MN', '🇲🇳', 976),
        (104, 'Montenegro', 'الجبل الأسود', 'ME', '🇲🇪', 382),
        (105, 'Morocco', 'بلاد المغرب', 'MA', '🇲🇦', 212),
        (106, 'Mozambique', 'موزمبيق', 'MZ', '🇲🇿', 258),
        (107, 'Namibia', 'ناميبيا', 'NA', '🇳🇦', 264),
        (108, 'Nepal', 'نيبال', 'NP', '🇳🇵', 977),
        (109, 'Netherlands', 'هولندا', 'nl', '🇳🇱', 31),
        (110, 'New Zealand', 'نيوزيلندا', 'NZ', '🇳🇿', 64),
        (111, 'Nicaragua', 'نيكاراغوا', 'NI', '🇳🇮', 505),
        (112, 'Niger', 'النيجر', 'NE', '🇳🇪', 227),
        (113, 'Nigeria', 'نيجيريا', 'NG', '🇳🇬', 234),
        (114, 'North Korea', 'كوريا الشمالية', 'KP', '🇰🇵', 850),
        (115, 'Norway', 'النرويج', 'NO', '🇳🇴', 47),
        (116, 'Oman', 'عمان', 'OM', '🇴🇲', 968),
        (117, 'Pakistan', 'باكستان', 'PK', '🇵🇰', 92),
        (118, 'Panama', 'بناما', 'PA', '🇵🇦', 507),
        (119, 'Papua New Guinea', 'بابوا غينيا الجديدة', 'PG', '🇵🇬', 675),
        (120, 'Paraguay', 'باراغواي', 'PY', '🇵🇾', 595),
        (121, 'Peru', 'بيرو', 'PE', '🇵🇪', 51),
        (122, 'the Philippines', 'الفلبين', 'PH', '🇵🇭', 63),
        (123, 'Poland', 'بولندا', 'PL', '🇵🇱', 48),
        (124, 'Portugal', 'البرتغال', 'PT', '🇵🇹', 351),
        (125, 'Qatar', 'دولة قطر', 'QA', '🇶🇦', 974),
        (126, 'Romania', 'رومانيا', 'RO', '🇷🇴', 40),
        (127, 'Russia', 'روسيا', 'RU', '🇷🇺', 7),
        (128, 'Rwanda', 'رواندا', 'RW', '🇷🇼', 250),
        (129, 'Saudi Arabia', 'السعودية', 'SA', '🇸🇦', 966),
        (131, 'Senegal', 'السنغال', 'SN', '🇸🇳', 221),
        (132, 'Serbia', 'صربيا', 'RS', '🇷🇸', 381),
        (133, 'Seychelles', 'سيشيل', 'SC', '🇸🇨', 248),
        (134, 'Sierra Leone', 'سيرا ليون', 'SL', '🇸🇱', 232),
        (135, 'Singapore', 'سنغافورة', 'SG', '🇸🇬', 65),
        (136, 'Slovakia', 'سلوفاكيا', 'SK', '🇸🇰', 421),
        (137, 'Slovenia', 'سلوفينيا', 'SI', '🇸🇮', 386),
        (138, 'Somalia', 'الصومال', 'SO', '🇸🇴', 252),
        (139, 'South Africa', 'جنوب أفريقيا', 'ZA', '🇿🇦', 27),
        (140, 'South Korea', 'كوريا الجنوبية', 'KR', '🇰🇷', 82),
        (141, 'Spain', 'إسبانيا', 'ES', '🇪🇸', 34),
        (142, 'Sri Lanka', 'سيريلانكا', 'LK', '🇱🇰', 94),
        (143, 'Sudan', 'سودان', 'SD', '🇸🇩', 249),
        (144, 'Suriname', 'سورينام', 'SR', '🇸🇷', 597),
        (145, 'Swaziland', 'سوازيلاند', 'SZ', '🇸🇿', 268),
        (146, 'Sweden', 'السويد', 'SE', '🇸🇪', 46),
        (147, 'Switzerland', 'سويسرا', 'xa', '🇨🇭', 41),
        (148, 'Syria', 'سوريا', 'SY', '🇸🇾', 963),
        (149, 'Taiwan', 'تايوان', 'TW', '🇹🇼', 886),
        (150, 'Tajikistan', 'طاجيكستان', 'TJ', '🇹🇯', 992),
        (151, 'Tanzania', 'تنزانيا', 'TZ', '🇹🇿', 255),
        (152, 'Thailand', 'تايلاند', 'TH', '🇹🇭', 66),
        (153, 'Togo', 'توغو', 'TG', '🇹🇬', 228),
        (154, 'Trinidad and Tobago', 'ترينداد وتوباغو', 'TT', '🇹🇹', 868),
        (155, 'Tobagonian', 'Tobagonian', 'tt', NULL, 868),
        (156, 'Tunisia', 'تونس', 'TN', '🇹🇳', 216),
        (157, 'Turkey', 'الديك الرومي', 'TR', '🇹🇷', 90),
        (158, 'Turkmenistan', 'تركمانستان', 'TM', '🇹🇲', 993),
        (159, 'Tuvalu', 'توفالو', 'TV', '🇹🇻', 688),
        (160, 'Uganda', 'أوغندا', 'UG', '🇺🇬', 256),
        (161, 'Ukraine', 'أوكرانيا', 'UA', '🇺🇦', 380),
        (162, 'United Arab Emirates', 'الإمارات العربية المتحدة', 'AE', '🇦🇪', 971),
        (163, 'United Kingdom', 'المملكة المتحدة', 'gb', '🇬🇧', 44),
        (164, 'United States of America', 'الولايات المتحدة الأمريكية', 'US', '🇺🇸', 1),
        (165, 'Uruguay', 'أوروغواي', 'UY', '🇺🇾', 598),
        (166, 'Uzbekistan', 'أوزبكستان', 'UZ', '🇺🇿', 998),
        (167, 'Vanuatu', 'فانواتو', 'VU', '🇻🇺', 678),
        (168, 'Venezuela', 'فنزويلا', 'VE', '🇻🇪', 58),
        (169, 'Vietnam', 'فيتنام', 'VN', '🇻🇳', 84),
        (170, 'Wales', 'ويلز', 'xw', NULL, 681),
        (171, 'Western Samoa', 'ساموا الغربية', 'WS', '🇼🇸', 685),
        (172, 'Yemen', 'يمني', 'YE', '🇾🇪', 967),
        (174, 'Zaire', 'زائير', 'dr', NULL, 243),
        (175, 'Zambia', 'زامبيا', 'ZM', '🇿🇲', 260),
        (176, 'Zimbabwe', 'زيمبابوي', 'ZW', '🇿🇼', 263);
        ";

        Yii::$app->db->createCommand($sql)->execute();



        $this->addColumn('restaurant', 'country_id', $this->integer()->defaultValue(84)->notNull()->after('restaurant_uuid'));


        // creates index for column `country_id`
        $this->createIndex(
                'idx-restaurant-country_id',
                'restaurant',
                'country_id'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
                'fk-restaurant-country_id',
                'restaurant',
                'country_id',
                'country',
                'country_id',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

      $this->dropForeignKey('fk-restaurant-country_id', 'restaurant');
      $this->dropIndex('idx-restaurant-country_id', 'restaurant');

      $this->dropColumn('restaurant', 'country_id');

      $this->dropTable('{{%country}}');
    }
}
