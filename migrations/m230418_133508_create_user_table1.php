<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m230418_133508_create_user_table1 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this-> createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'login' => $this->string()->notNull(),
            'username'=>$this->string()->notNull(),
            'password_hash'=>$this->string()->notNull(),
            'auth_key'=>$this->string()->notNull(),
            'email'=>$this->string()->notNull(),
            'access_level'=>$this->smallInteger(1)
        ], $tableOptions);

        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'category_name' => $this->string()->notNull()
        ], $tableOptions);


        $this->createTable('{{%blog_posts}}', [
            'id' => $this->primaryKey(),
            'topic' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'category' => $this->integer(),
            'author' =>$this->integer()
        ], $tableOptions);

        $this->createTable('{{%commentary}}', [
            'id' => $this->primaryKey(),
            'com_text' => $this->string()->notNull(),
            'post' => $this->integer(),
            'author' =>$this->integer()
        ], $tableOptions);
        $this->addForeignKey('author_id2','{{%commentary}}', 'author', '{{%user}}', 'id', 'SET NULL', 'CASCADE' );
        $this->addForeignKey('post_id', '{{%commentary}}', 'post', '{{%blog_posts}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('category_id','{{%blog_posts}}','category','{{%category}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('author_id','{{%blog_posts}}',  'author', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_posts}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{commentary}}');

    }
}
