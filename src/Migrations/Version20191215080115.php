<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191215080115 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(100) NOT NULL, content LONGTEXT DEFAULT NULL, published_at DATETIME DEFAULT NULL, image_filename VARCHAR(255) DEFAULT NULL, published_end DATETIME NOT NULL, UNIQUE INDEX UNIQ_23A0E66989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_reference (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, filename VARCHAR(255) NOT NULL, original_filename VARCHAR(255) DEFAULT NULL, mime_type VARCHAR(255) DEFAULT NULL, position INT NOT NULL, INDEX IDX_749619377294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_request (id INT AUTO_INCREMENT NOT NULL, request_id INT DEFAULT NULL, ticket_id INT DEFAULT NULL, nb_ticket INT NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_A40EB1A6427EB8A5 (request_id), INDEX IDX_A40EB1A6700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, type_reduction VARCHAR(255) NOT NULL, path_image VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pvce (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, published_at DATETIME NOT NULL, pdf_filename VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_3B978F9FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, price_ce DOUBLE PRECISION NOT NULL, url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tool (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, forname VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, nb_personne INT NOT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, birthday DATETIME DEFAULT NULL, is_comite TINYINT(1) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, syndicat VARCHAR(255) DEFAULT NULL, smartphone VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('ALTER TABLE article_reference ADD CONSTRAINT FK_749619377294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE ligne_request ADD CONSTRAINT FK_A40EB1A6427EB8A5 FOREIGN KEY (request_id) REFERENCES request (id)');
        $this->addSql('ALTER TABLE ligne_request ADD CONSTRAINT FK_A40EB1A6700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_reference DROP FOREIGN KEY FK_749619377294869C');
        $this->addSql('ALTER TABLE ligne_request DROP FOREIGN KEY FK_A40EB1A6427EB8A5');
        $this->addSql('ALTER TABLE ligne_request DROP FOREIGN KEY FK_A40EB1A6700047D2');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FA76ED395');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_reference');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE ligne_request');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE pvce');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE tool');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE ext_log_entries');
    }
}
