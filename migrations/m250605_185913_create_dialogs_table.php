<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dialogs}}`.
 */
class m250605_185913_create_dialogs_table extends Migration
{
    const string TABLE = '{{%dialogs}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable(self::TABLE, [
            'dialog_id' => 'uuid PRIMARY KEY DEFAULT gen_random_uuid()',
            'client_id' => 'uuid',
        ]);

        $this->addForeignKey('clients_kf', self::TABLE, 'client_id', '{{%clients}}', 'client_id', 'CASCADE', 'CASCADE');
        $this->createIndex('clients_uniq', self::TABLE, 'client_id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable(self::TABLE);
    }
}
