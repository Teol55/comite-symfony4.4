<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191130102708 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ligne_request ADD ticket_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_request ADD CONSTRAINT FK_A40EB1A6700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A40EB1A6700047D2 ON ligne_request (ticket_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ligne_request DROP FOREIGN KEY FK_A40EB1A6700047D2');
        $this->addSql('DROP INDEX UNIQ_A40EB1A6700047D2 ON ligne_request');
        $this->addSql('ALTER TABLE ligne_request DROP ticket_id');
    }
}
