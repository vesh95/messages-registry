<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%messages}}`.
 */
class m250605_180027_create_messages_table extends Migration
{
    const string TABLE = '{{%messages}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable(self::TABLE, [
            'message_id' => 'uuid PRIMARY KEY',
            'client_id' => 'uuid',
            'message_text' => $this->text()->notNull(),
            'send_at' => $this->integer()->notNull()->unsigned(),
        ]);

        $this->addForeignKey('client_kf', self::TABLE, 'client_id', '{{%clients}}', 'client_id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable(self::TABLE);
    }
}
