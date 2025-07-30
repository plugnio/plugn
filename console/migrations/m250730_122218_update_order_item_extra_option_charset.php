<?php

use yii\db\Migration;

/**
 * Class m250730_122218_update_order_item_extra_option_charset
 */
class m250730_122218_update_order_item_extra_option_charset extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%order_item_extra_option}}', 'extra_option_name', $this->string(255)->append('CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'));
        $this->alterColumn('{{%order_item_extra_option}}', 'extra_option_name_ar', $this->string(255)->append('CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'));
        
        // Optional: Also update the table's default character set
        $this->execute('ALTER TABLE {{%order_item_extra_option}} CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         // Revert back to utf8 if needed
         $this->alterColumn('{{%order_item_extra_option}}', 'extra_option_name', $this->string(255)->append('CHARACTER SET utf8 COLLATE utf8_unicode_ci'));
         $this->alterColumn('{{%order_item_extra_option}}', 'extra_option_name_ar', $this->string(255)->append('CHARACTER SET utf8 COLLATE utf8_unicode_ci'));
         $this->execute('ALTER TABLE {{%order_item_extra_option}} CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }

}
