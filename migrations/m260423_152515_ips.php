<?php

use yii\db\Migration;

class m260423_152515_ips extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->createTable('{{%ips}}', [
            'id' => $this->primaryKey(),
            'ip' => $this->string(12)->notNull(),
            'url_id' => $this->integer(11)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

         $this->addForeignKey(
        'fk-ips-link_id', // Unique name for foreign key
        '{{%ips}}',       // Table being created
        'url_id',         // Column in this table
        '{{%link}}',       // Related table
        'id',              // Column in related table
        'CASCADE'          // ON DELETE CASCADE
    );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ips}}');
        echo "m260423_152515_ips cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260423_152515_ips cannot be reverted.\n";

        return false;
    }
    */
}
