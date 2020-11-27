<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200929144333 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affectation (id INT AUTO_INCREMENT NOT NULL, livreur_id INT DEFAULT NULL, installateur_id INT DEFAULT NULL, metteur_service_id INT DEFAULT NULL, acceptateur_id INT DEFAULT NULL, mission_id INT DEFAULT NULL, INDEX IDX_F4DD61D3F8646701 (livreur_id), INDEX IDX_F4DD61D351262FCB (installateur_id), INDEX IDX_F4DD61D3F103EAEF (metteur_service_id), INDEX IDX_F4DD61D36342E3B5 (acceptateur_id), INDEX IDX_F4DD61D3BE6CAE90 (mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, validation_id INT DEFAULT NULL, commentaire VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_67F068BCA2274850 (validation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, mission_id INT DEFAULT NULL, stock_id INT DEFAULT NULL, quantite_sortie INT NOT NULL, INDEX IDX_18D2B091BE6CAE90 (mission_id), INDEX IDX_18D2B091DCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, user_creator_id INT DEFAULT NULL, site_id INT DEFAULT NULL, nom_mission VARCHAR(255) NOT NULL, date_mission DATETIME NOT NULL, INDEX IDX_9067F23CC645C84A (user_creator_id), INDEX IDX_9067F23CF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, validation_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_14B78418A2274850 (validation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, ref VARCHAR(255) NOT NULL, nom_produit VARCHAR(255) NOT NULL, quantite_generale INT NOT NULL, quantite_sortie INT NOT NULL, quantite_restant INT NOT NULL, quantite_casse INT NOT NULL, date_creation DATETIME NOT NULL, description VARCHAR(255) NOT NULL, unite VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tracability (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, stock_id INT DEFAULT NULL, quantite_initiale INT NOT NULL, quantite_finale INT NOT NULL, date_action DATETIME NOT NULL, type_action VARCHAR(255) NOT NULL, INDEX IDX_6A1FC6C5A76ED395 (user_id), INDEX IDX_6A1FC6C5DCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE validation (id INT AUTO_INCREMENT NOT NULL, affectation_id INT DEFAULT NULL, materiel_id INT DEFAULT NULL, date_validation DATETIME NOT NULL, validate INT NOT NULL, type_user INT NOT NULL, quantite_ajoute INT NOT NULL, quantite_supprimer INT NOT NULL, quantite_casse INT NOT NULL, INDEX IDX_16AC5B6E6D0ABA22 (affectation_id), INDEX IDX_16AC5B6E16880AAF (materiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3F8646701 FOREIGN KEY (livreur_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D351262FCB FOREIGN KEY (installateur_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3F103EAEF FOREIGN KEY (metteur_service_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D36342E3B5 FOREIGN KEY (acceptateur_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA2274850 FOREIGN KEY (validation_id) REFERENCES validation (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B091BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B091DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CC645C84A FOREIGN KEY (user_creator_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418A2274850 FOREIGN KEY (validation_id) REFERENCES validation (id)');
        $this->addSql('ALTER TABLE tracability ADD CONSTRAINT FK_6A1FC6C5A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE tracability ADD CONSTRAINT FK_6A1FC6C5DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE validation ADD CONSTRAINT FK_16AC5B6E6D0ABA22 FOREIGN KEY (affectation_id) REFERENCES affectation (id)');
        $this->addSql('ALTER TABLE validation ADD CONSTRAINT FK_16AC5B6E16880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE validation DROP FOREIGN KEY FK_16AC5B6E6D0ABA22');
        $this->addSql('ALTER TABLE validation DROP FOREIGN KEY FK_16AC5B6E16880AAF');
        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3BE6CAE90');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B091BE6CAE90');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B091DCD6110');
        $this->addSql('ALTER TABLE tracability DROP FOREIGN KEY FK_6A1FC6C5DCD6110');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA2274850');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418A2274850');
        $this->addSql('DROP TABLE affectation');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE tracability');
        $this->addSql('DROP TABLE validation');
    }
}
