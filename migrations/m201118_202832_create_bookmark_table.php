<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bookmark}}`.
 */
class m201118_202832_create_bookmark_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bookmark}}', [
            'id' => $this->primaryKey(),
            'favicon' => $this->binary(),
            'url' => $this->string(),
            'title' => $this->string(),
            'meta_description' => $this->string(),
            'meta_keywords' => $this->string(),
            'password_hash' => $this->string(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bookmark}}');
    }
}
