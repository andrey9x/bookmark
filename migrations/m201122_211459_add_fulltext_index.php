<?php

use yii\db\Migration;

/**
 * Class m201122_211459_add_fulltext_index
 */
class m201122_211459_add_fulltext_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE FULLTEXT INDEX url_title_idx ON bookmark(url, title, meta_description, meta_keywords)");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP FULLTEXT INDEX url_title_idx");
    }
}
