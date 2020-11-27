<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200917150634 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE noeud_acceptance (id INT AUTO_INCREMENT NOT NULL, user_creator_id INT DEFAULT NULL, user_destinator_id INT NOT NULL, site_id INT DEFAULT NULL, armoire_id INT DEFAULT NULL, fiche_id INT NOT NULL, statut VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, longitude DOUBLE PRECISION NOT NULL, latitude DOUBLE PRECISION NOT NULL, type_acceptance VARCHAR(255) NOT NULL, many_to_one VARCHAR(255) NOT NULL, INDEX IDX_E5F36045C645C84A (user_creator_id), INDEX IDX_E5F36045CCA4484B (user_destinator_id), INDEX IDX_E5F36045F6BD1646 (site_id), INDEX IDX_E5F36045CFB9323 (armoire_id), INDEX IDX_E5F36045DF522508 (fiche_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE noeud_acceptance ADD CONSTRAINT FK_E5F36045C645C84A FOREIGN KEY (user_creator_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE noeud_acceptance ADD CONSTRAINT FK_E5F36045CCA4484B FOREIGN KEY (user_destinator_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE noeud_acceptance ADD CONSTRAINT FK_E5F36045F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE noeud_acceptance ADD CONSTRAINT FK_E5F36045CFB9323 FOREIGN KEY (armoire_id) REFERENCES armoire (id)');
        $this->addSql('ALTER TABLE noeud_acceptance ADD CONSTRAINT FK_E5F36045DF522508 FOREIGN KEY (fiche_id) REFERENCES fiche (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE noeud_acceptance');
    }
}
