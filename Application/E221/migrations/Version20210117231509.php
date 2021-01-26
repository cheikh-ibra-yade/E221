<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210117231509 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bien ADD zone_id INT NOT NULL, ADD type VARCHAR(255) NOT NULL, ADD montant DOUBLE PRECISION NOT NULL, ADD periode VARCHAR(255) NOT NULL, ADD type_usage VARCHAR(255) NOT NULL, ADD etat VARCHAR(255) NOT NULL, ADD avatar VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC3869F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('CREATE INDEX IDX_45EDC3869F2C3FAB ON bien (zone_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC3869F2C3FAB');
        $this->addSql('DROP TABLE zone');
        $this->addSql('DROP INDEX IDX_45EDC3869F2C3FAB ON bien');
        $this->addSql('ALTER TABLE bien DROP zone_id, DROP type, DROP montant, DROP periode, DROP type_usage, DROP etat, DROP avatar');
    }
}
