<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200913125627 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE weblog (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, user_type VARCHAR(255) NOT NULL, session_id VARCHAR(50) NOT NULL, page VARCHAR(3000) NOT NULL, action VARCHAR(50) NOT NULL, post_string VARCHAR(2000) NOT NULL, ip_address VARCHAR(255) DEFAULT NULL, datestamp DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_category (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, question_category_id INT NOT NULL, question_type_id INT NOT NULL, question_text VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B6F7494EF142426F (question_category_id), INDEX IDX_B6F7494ECB90598E (question_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_choice (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, choice_text VARCHAR(255) NOT NULL, is_right_choice TINYINT(1) NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_C6F6759A1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_question_answer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, question_id INT NOT NULL, question_choice_id INT NOT NULL, is_right_choice TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_CF5C09A2A76ED395 (user_id), INDEX IDX_CF5C09A21E27F6BF (question_id), INDEX IDX_CF5C09A29053224A (question_choice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EF142426F FOREIGN KEY (question_category_id) REFERENCES question_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494ECB90598E FOREIGN KEY (question_type_id) REFERENCES question_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question_choice ADD CONSTRAINT FK_C6F6759A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_question_answer ADD CONSTRAINT FK_CF5C09A2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_question_answer ADD CONSTRAINT FK_CF5C09A21E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_question_answer ADD CONSTRAINT FK_CF5C09A29053224A FOREIGN KEY (question_choice_id) REFERENCES question_choice (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question_choice DROP FOREIGN KEY FK_C6F6759A1E27F6BF');
        $this->addSql('ALTER TABLE user_question_answer DROP FOREIGN KEY FK_CF5C09A21E27F6BF');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EF142426F');
        $this->addSql('ALTER TABLE user_question_answer DROP FOREIGN KEY FK_CF5C09A29053224A');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494ECB90598E');
        $this->addSql('ALTER TABLE user_question_answer DROP FOREIGN KEY FK_CF5C09A2A76ED395');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_category');
        $this->addSql('DROP TABLE question_choice');
        $this->addSql('DROP TABLE question_type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_question_answer');
        $this->addSql('DROP TABLE weblog');
    }
}
