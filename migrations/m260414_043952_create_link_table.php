<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%link}}`.
 */
class m260414_043952_create_link_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%link}}', [
            'id' => $this->primaryKey(),
            'url_real' => $this->text()->notNull(),
            'url_short' => $this->text()->notNull()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%link}}');
        echo "m260414_043952_create_link_table cannot be reverted.\n";

        return;
    }
}
