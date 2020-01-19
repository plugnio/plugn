<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cuisine}}`.
 */
class m200119_140152_create_cuisine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%cuisine}}', [
            'cuisine_id' => $this->primaryKey(),
            'cuisine_name' => $this->string(255)->notNull(),
            'cuisine_name_ar' => $this->string(255)
        ],$tableOptions );
        
        $sql = "INSERT INTO `cuisine` (`cuisine_id`, `cuisine_name`, `cuisine_name_ar`) VALUES
                (291, 'American', 'أمريكي'),
                (292, 'Arabic', 'عربى'),
                (293, 'Bakery', 'مخبز'),
                (294, 'Breakfast', 'إفطار'),
                (295, 'Burgers', 'البرجر'),
                (296, 'Chinese', 'صيني'),
                (297, 'Coffee', 'قهوة'),
                (298, 'Crepes', 'كريب'),
                (299, 'Dessert', 'حلويات'),
                (300, 'Healthy Food', 'الطعام الصحي'),
                (301, 'Indian', 'هندي'),
                (302, 'International', 'دولي'),
                (303, 'Iranian', 'إيراني'),
                (304, 'Italian', 'إيطالي'),
                (305, 'Japanese', 'ياباني'),
                (306, 'Korean', 'كوري'),
                (307, 'Kuwaiti', 'كويتي'),
                (308, 'Lebanese', 'لبناني'),
                (309, 'Mexican', 'مكسيكي'),
                (310, 'Pizza', 'البيتزا'),
                (311, 'Sandwiches', 'الساندويشات'),
                (312, 'Seafood', 'أكل بحري'),
                (313, 'Shawerma & Doner', 'الشاورما والدونر'),
                (314, 'Sushi', 'سوشي'),
                (315, 'Turkish', 'تركي'),
                (316, 'Ice-Cream', 'آيس كريم'),
                (317, 'French', 'فرنسي'),
                (318, 'Thai', 'تايلندي'),
                (319, 'Asian', 'الآسيوية'),
                (320, 'Egyptian', 'مصري'),
                (321, 'Organic', 'عضوي'),
                (322, 'Mediterranean', 'متوسط'),
                (323, 'Brazilian', 'برازيلي'),
                (324, 'Spanish', 'إسباني'),
                (325, 'Armenian', 'أرميني'),
                (326, 'Kunafah', 'الكنافة'),
                (327, 'Lunch', 'الغذاء'),
                (328, 'Juice', 'عصير'),
                (329, 'Cafe', 'كافيه'),
                (331, 'Middle Eastern', 'شرق أوسطي'),
                (332, 'Meals', 'وجبات'),
                (333, 'Baklava', 'بقلاوة'),
                (334, 'Trending', 'عروض'),
                (335, 'African', 'افريقي'),
                (336, 'Gluten Free', 'خالي من الغلوتين'),
                (337, 'Steak House', 'ستيك هاوس'),
                (338, 'Vegan', 'نباتي'),
                (339, 'Fried Chicken', 'الدجاج'),
                (340, 'Zwarah', 'زوارة'),
                (341, 'Offers', 'العروض'),
                (344, 'Specialty Coffee', 'القهوة المتخصصة'),
                (353, 'Flowers & Chocolate', 'الزهور والشوكولاتة'),
                (416, 'Greek', 'يوناني'),
                (616, 'Salads', 'السلطات')";
        
        Yii::$app->db->createCommand($sql)->execute();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cuisine}}');
    }
}
