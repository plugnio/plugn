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
          $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
      }


        $this->createTable('{{%country}}', [
            'country_id' => $this->primaryKey(),
            'country_name' => $this->string(80),
            'iso' => $this->char(2),
            'iso3' => $this->char(3),
            'country_code' => $this->integer(3),
        ], $tableOptions);

        $sql = "
        INSERT INTO `country` (`country_id`, `iso`, `iso3`, `country_name`, `country_code`) VALUES
        (1, 'AF', 'AFG', 'Afghanistan', 93),
        (2, 'AL', 'ALB', 'Albania', 355),
        (3, 'DZ', 'DZA', 'Algeria', 213),
        (4, 'AS', 'ASM', 'American Samoa', 1684),
        (5, 'AD', 'AND', 'Andorra', 376),
        (6, 'AO', 'AGO', 'Angola', 244),
        (7, 'AI', 'AIA', 'Anguilla', 1264),
        (8, 'AQ', NULL, 'Antarctica', 0),
        (9, 'AG', 'ATG', 'Antigua and Barbuda', 1268),
        (10, 'AR', 'ARG', 'Argentina', 54),
        (11, 'AM', 'ARM', 'Armenia', 374),
        (12, 'AW', 'ABW', 'Aruba', 297),
        (13, 'AU', 'AUS', 'Australia', 61),
        (14, 'AT', 'AUT', 'Austria', 43),
        (15, 'AZ', 'AZE', 'Azerbaijan', 994),
        (16, 'BS', 'BHS', 'Bahamas', 1242),
        (17, 'BH', 'BHR', 'Bahrain', 973),
        (18, 'BD', 'BGD', 'Bangladesh', 880),
        (19, 'BB', 'BRB', 'Barbados', 1246),
        (20, 'BY', 'BLR', 'Belarus', 375),
        (21, 'BE', 'BEL', 'Belgium', 32),
        (22, 'BZ', 'BLZ', 'Belize', 501),
        (23, 'BJ', 'BEN', 'Benin', 229),
        (24, 'BM', 'BMU', 'Bermuda', 1441),
        (25, 'BT', 'BTN', 'Bhutan', 975),
        (26, 'BO', 'BOL', 'Bolivia', 591),
        (27, 'BA', 'BIH', 'Bosnia and Herzegovina', 387),
        (28, 'BW', 'BWA', 'Botswana', 267),
        (29, 'BV', NULL, 'Bouvet Island', 0),
        (30, 'BR', 'BRA', 'Brazil', 55),
        (31, 'IO', NULL, 'British Indian Ocean Territory', 246),
        (32, 'BN', 'BRN', 'Brunei Darussalam', 673),
        (33, 'BG', 'BGR', 'Bulgaria', 359),
        (34, 'BF', 'BFA', 'Burkina Faso', 226),
        (35, 'BI', 'BDI', 'Burundi', 257),
        (36, 'KH', 'KHM', 'Cambodia', 855),
        (37, 'CM', 'CMR', 'Cameroon', 237),
        (38, 'CA', 'CAN', 'Canada', 1),
        (39, 'CV', 'CPV', 'Cape Verde', 238),
        (40, 'KY', 'CYM', 'Cayman Islands', 1345),
        (41, 'CF', 'CAF', 'Central African Republic', 236),
        (42, 'TD', 'TCD', 'Chad', 235),
        (43, 'CL', 'CHL', 'Chile', 56),
        (44, 'CN', 'CHN', 'China', 86),
        (45, 'CX', NULL, 'Christmas Island', 61),
        (46, 'CC', NULL, 'Cocos (Keeling) Islands', 672),
        (47, 'CO', 'COL', 'Colombia', 57),
        (48, 'KM', 'COM', 'Comoros', 269),
        (49, 'CG', 'COG', 'Congo', 242),
        (50, 'CD', 'COD', 'Congo, the Democratic Republic of the', 242),
        (51, 'CK', 'COK', 'Cook Islands', 682),
        (52, 'CR', 'CRI', 'Costa Rica', 506),
        (53, 'CI', 'CIV', 'Cote D\'Ivoire', 225),
        (54, 'HR', 'HRV', 'Croatia', 385),
        (55, 'CU', 'CUB', 'Cuba', 53),
        (56, 'CY', 'CYP', 'Cyprus', 357),
        (57, 'CZ', 'CZE', 'Czech Republic', 420),
        (58, 'DK', 'DNK', 'Denmark', 45),
        (59, 'DJ', 'DJI', 'Djibouti', 253),
        (60, 'DM', 'DMA', 'Dominica', 1767),
        (61, 'DO', 'DOM', 'Dominican Republic', 1809),
        (62, 'EC', 'ECU', 'Ecuador', 593),
        (63, 'EG', 'EGY', 'Egypt', 20),
        (64, 'SV', 'SLV', 'El Salvador', 503),
        (65, 'GQ', 'GNQ', 'Equatorial Guinea', 240),
        (66, 'ER', 'ERI', 'Eritrea', 291),
        (67, 'EE', 'EST', 'Estonia', 372),
        (68, 'ET', 'ETH', 'Ethiopia', 251),
        (69, 'FK', 'FLK', 'Falkland Islands (Malvinas)', 500),
        (70, 'FO', 'FRO', 'Faroe Islands', 298),
        (71, 'FJ', 'FJI', 'Fiji', 679),
        (72, 'FI', 'FIN', 'Finland', 358),
        (73, 'FR', 'FRA', 'France', 33),
        (74, 'GF', 'GUF', 'French Guiana', 594),
        (75, 'PF', 'PYF', 'French Polynesia', 689),
        (76, 'TF', NULL, 'French Southern Territories', 0),
        (77, 'GA', 'GAB', 'Gabon', 241),
        (78, 'GM', 'GMB', 'Gambia', 220),
        (79, 'GE', 'GEO', 'Georgia', 995),
        (80, 'DE', 'DEU', 'Germany', 49),
        (81, 'GH', 'GHA', 'Ghana', 233),
        (82, 'GI', 'GIB', 'Gibraltar', 350),
        (83, 'GR', 'GRC', 'Greece', 30),
        (84, 'GL', 'GRL', 'Greenland', 299),
        (85, 'GD', 'GRD', 'Grenada', 1473),
        (86, 'GP', 'GLP', 'Guadeloupe', 590),
        (87, 'GU', 'GUM', 'Guam', 1671),
        (88, 'GT', 'GTM', 'Guatemala', 502),
        (89, 'GN', 'GIN', 'Guinea', 224),
        (90, 'GW', 'GNB', 'Guinea-Bissau', 245),
        (91, 'GY', 'GUY', 'Guyana', 592),
        (92, 'HT', 'HTI', 'Haiti', 509),
        (93, 'HM', NULL, 'Heard Island and Mcdonald Islands', 0),
        (94, 'VA', 'VAT', 'Holy See (Vatican City State)', 39),
        (95, 'HN', 'HND', 'Honduras', 504),
        (96, 'HK', 'HKG', 'Hong Kong', 852),
        (97, 'HU', 'HUN', 'Hungary', 36),
        (98, 'IS', 'ISL', 'Iceland', 354),
        (99, 'IN', 'IND', 'India', 91),
        (100, 'ID', 'IDN', 'Indonesia', 62),
        (101, 'IR', 'IRN', 'Iran, Islamic Republic of', 98),
        (102, 'IQ', 'IRQ', 'Iraq', 964),
        (103, 'IE', 'IRL', 'Ireland', 353),
        (104, 'IL', 'ISR', 'Israel', 972),
        (105, 'IT', 'ITA', 'Italy', 39),
        (106, 'JM', 'JAM', 'Jamaica', 1876),
        (107, 'JP', 'JPN', 'Japan', 81),
        (108, 'JO', 'JOR', 'Jordan', 962),
        (109, 'KZ', 'KAZ', 'Kazakhstan', 7),
        (110, 'KE', 'KEN', 'Kenya', 254),
        (111, 'KI', 'KIR', 'Kiribati', 686),
        (112, 'KP', 'PRK', 'Korea, Democratic People\'s Republic of', 850),
        (113, 'KR', 'KOR', 'Korea, Republic of', 82),
        (114, 'KW', 'KWT', 'Kuwait', 965),
        (115, 'KG', 'KGZ', 'Kyrgyzstan', 996),
        (116, 'LA', 'LAO', 'Lao People\'s Democratic Republic', 856),
        (117, 'LV', 'LVA', 'Latvia', 371),
        (118, 'LB', 'LBN', 'Lebanon', 961),
        (119, 'LS', 'LSO', 'Lesotho', 266),
        (120, 'LR', 'LBR', 'Liberia', 231),
        (121, 'LY', 'LBY', 'Libyan Arab Jamahiriya', 218),
        (122, 'LI', 'LIE', 'Liechtenstein', 423),
        (123, 'LT', 'LTU', 'Lithuania', 370),
        (124, 'LU', 'LUX', 'Luxembourg', 352),
        (125, 'MO', 'MAC', 'Macao', 853),
        (126, 'MK', 'MKD', 'Macedonia, the Former Yugoslav Republic of', 389),
        (127, 'MG', 'MDG', 'Madagascar', 261),
        (128, 'MW', 'MWI', 'Malawi', 265),
        (129, 'MY', 'MYS', 'Malaysia', 60),
        (130, 'MV', 'MDV', 'Maldives', 960),
        (131, 'ML', 'MLI', 'Mali', 223),
        (132, 'MT', 'MLT', 'Malta', 356),
        (133, 'MH', 'MHL', 'Marshall Islands', 692),
        (134, 'MQ', 'MTQ', 'Martinique', 596),
        (135, 'MR', 'MRT', 'Mauritania', 222),
        (136, 'MU', 'MUS', 'Mauritius', 230),
        (137, 'YT', NULL, 'Mayotte', 269),
        (138, 'MX', 'MEX', 'Mexico', 52),
        (139, 'FM', 'FSM', 'Micronesia, Federated States of', 691),
        (140, 'MD', 'MDA', 'Moldova, Republic of', 373),
        (141, 'MC', 'MCO', 'Monaco', 377),
        (142, 'MN', 'MNG', 'Mongolia', 976),
        (143, 'MS', 'MSR', 'Montserrat', 1664),
        (144, 'MA', 'MAR', 'Morocco', 212),
        (145, 'MZ', 'MOZ', 'Mozambique', 258),
        (146, 'MM', 'MMR', 'Myanmar', 95),
        (147, 'NA', 'NAM', 'Namibia', 264),
        (148, 'NR', 'NRU', 'Nauru', 674),
        (149, 'NP', 'NPL', 'Nepal', 977),
        (150, 'NL', 'NLD', 'Netherlands', 31),
        (151, 'AN', 'ANT', 'Netherlands Antilles', 599),
        (152, 'NC', 'NCL', 'New Caledonia', 687),
        (153, 'NZ', 'NZL', 'New Zealand', 64),
        (154, 'NI', 'NIC', 'Nicaragua', 505),
        (155, 'NE', 'NER', 'Niger', 227),
        (156, 'NG', 'NGA', 'Nigeria', 234),
        (157, 'NU', 'NIU', 'Niue', 683),
        (158, 'NF', 'NFK', 'Norfolk Island', 672),
        (159, 'MP', 'MNP', 'Northern Mariana Islands', 1670),
        (160, 'NO', 'NOR', 'Norway', 47),
        (161, 'OM', 'OMN', 'Oman', 968),
        (162, 'PK', 'PAK', 'Pakistan', 92),
        (163, 'PW', 'PLW', 'Palau', 680),
        (164, 'PS', NULL, 'Palestinian Territory, Occupied', 970),
        (165, 'PA', 'PAN', 'Panama', 507),
        (166, 'PG', 'PNG', 'Papua New Guinea', 675),
        (167, 'PY', 'PRY', 'Paraguay', 595),
        (168, 'PE', 'PER', 'Peru', 51),
        (169, 'PH', 'PHL', 'Philippines', 63),
        (170, 'PN', 'PCN', 'Pitcairn', 0),
        (171, 'PL', 'POL', 'Poland', 48),
        (172, 'PT', 'PRT', 'Portugal', 351),
        (173, 'PR', 'PRI', 'Puerto Rico', 1787),
        (174, 'QA', 'QAT', 'Qatar', 974),
        (175, 'RE', 'REU', 'Reunion', 262),
        (176, 'RO', 'ROM', 'Romania', 40),
        (177, 'RU', 'RUS', 'Russian Federation', 70),
        (178, 'RW', 'RWA', 'Rwanda', 250),
        (179, 'SH', 'SHN', 'Saint Helena', 290),
        (180, 'KN', 'KNA', 'Saint Kitts and Nevis', 1869),
        (181, 'LC', 'LCA', 'Saint Lucia', 1758),
        (182, 'PM', 'SPM', 'Saint Pierre and Miquelon', 508),
        (183, 'VC', 'VCT', 'Saint Vincent and the Grenadines', 1784),
        (184, 'WS', 'WSM', 'Samoa', 684),
        (185, 'SM', 'SMR', 'San Marino', 378),
        (186, 'ST', 'STP', 'Sao Tome and Principe', 239),
        (187, 'SA', 'SAU', 'Saudi Arabia', 966),
        (188, 'SN', 'SEN', 'Senegal', 221),
        (189, 'CS', NULL, 'Serbia and Montenegro', 381),
        (190, 'SC', 'SYC', 'Seychelles', 248),
        (191, 'SL', 'SLE', 'Sierra Leone', 232),
        (192, 'SG', 'SGP', 'Singapore', 65),
        (193, 'SK', 'SVK', 'Slovakia', 421),
        (194, 'SI', 'SVN', 'Slovenia', 386),
        (195, 'SB', 'SLB', 'Solomon Islands', 677),
        (196, 'SO', 'SOM', 'Somalia', 252),
        (197, 'ZA', 'ZAF', 'South Africa', 27),
        (198, 'GS', NULL, 'South Georgia and the South Sandwich Islands', 0),
        (199, 'ES', 'ESP', 'Spain', 34),
        (200, 'LK', 'LKA', 'Sri Lanka', 94),
        (201, 'SD', 'SDN', 'Sudan', 249),
        (202, 'SR', 'SUR', 'Suriname', 597),
        (203, 'SJ', 'SJM', 'Svalbard and Jan Mayen', 47),
        (204, 'SZ', 'SWZ', 'Swaziland', 268),
        (205, 'SE', 'SWE', 'Sweden', 46),
        (206, 'CH', 'CHE', 'Switzerland', 41),
        (207, 'SY', 'SYR', 'Syrian Arab Republic', 963),
        (208, 'TW', 'TWN', 'Taiwan, Province of China', 886),
        (209, 'TJ', 'TJK', 'Tajikistan', 992),
        (210, 'TZ', 'TZA', 'Tanzania, United Republic of', 255),
        (211, 'TH', 'THA', 'Thailand', 66),
        (212, 'TL', NULL, 'Timor-Leste', 670),
        (213, 'TG', 'TGO', 'Togo', 228),
        (214, 'TK', 'TKL', 'Tokelau', 690),
        (215, 'TO', 'TON', 'Tonga', 676),
        (216, 'TT', 'TTO', 'Trinidad and Tobago', 1868),
        (217, 'TN', 'TUN', 'Tunisia', 216),
        (218, 'TR', 'TUR', 'Turkey', 90),
        (219, 'TM', 'TKM', 'Turkmenistan', 7370),
        (220, 'TC', 'TCA', 'Turks and Caicos Islands', 1649),
        (221, 'TV', 'TUV', 'Tuvalu', 688),
        (222, 'UG', 'UGA', 'Uganda', 256),
        (223, 'UA', 'UKR', 'Ukraine', 380),
        (224, 'AE', 'ARE', 'United Arab Emirates', 971),
        (225, 'GB', 'GBR', 'United Kingdom', 44),
        (226, 'US', 'USA', 'United States', 1),
        (227, 'UM', NULL, 'United States Minor Outlying Islands', 1),
        (228, 'UY', 'URY', 'Uruguay', 598),
        (229, 'UZ', 'UZB', 'Uzbekistan', 998),
        (230, 'VU', 'VUT', 'Vanuatu', 678),
        (231, 'VE', 'VEN', 'Venezuela', 58),
        (232, 'VN', 'VNM', 'Viet Nam', 84),
        (233, 'VG', 'VGB', 'Virgin Islands, British', 1284),
        (234, 'VI', 'VIR', 'Virgin Islands, U.s.', 1340),
        (235, 'WF', 'WLF', 'Wallis and Futuna', 681),
        (236, 'EH', 'ESH', 'Western Sahara', 212),
        (237, 'YE', 'YEM', 'Yemen', 967),
        (238, 'ZM', 'ZMB', 'Zambia', 260),
        (239, 'ZW', 'ZWE', 'Zimbabwe', 263);

        ";

        Yii::$app->db->createCommand($sql)->execute();



        $this->addColumn('restaurant', 'country_id', $this->integer()->notNull()->defaultValue(114)->after('restaurant_uuid'));


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
