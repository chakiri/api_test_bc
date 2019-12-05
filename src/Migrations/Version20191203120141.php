<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191203120141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE advert DROP FOREIGN KEY FK_54F1F40BB6E62EFA');
        $this->addSql('DROP INDEX UNIQ_54F1F40BB6E62EFA ON advert');
        $this->addSql('ALTER TABLE advert ADD attributes LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP attribute_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE advert ADD attribute_id INT DEFAULT NULL, DROP attributes');
        $this->addSql('ALTER TABLE advert ADD CONSTRAINT FK_54F1F40BB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_54F1F40BB6E62EFA ON advert (attribute_id)');
    }
}
