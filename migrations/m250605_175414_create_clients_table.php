<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%clients}}`.
 */
class m250605_175414_create_clients_table extends Migration
{
    const string TABLE = '{{%clients}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable(self::TABLE, [
            'client_id' => 'uuid PRIMARY KEY',
            'phone' => $this->string(12)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable(self::TABLE);
    }
}
