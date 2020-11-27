<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201016094317 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire_site (id INT AUTO_INCREMENT NOT NULL, reponse_id INT DEFAULT NULL, comment VARCHAR(255) NOT NULL, date_commentaire DATETIME NOT NULL, INDEX IDX_80BBCDB6CF18BB82 (reponse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_site (id INT AUTO_INCREMENT NOT NULL, reponse_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, date_insertion DATETIME NOT NULL, INDEX IDX_9B79E37ACF18BB82 (reponse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, choix_reponse_id INT DEFAULT NULL, question_id INT DEFAULT NULL, date_reponse DATETIME NOT NULL, INDEX IDX_5FB6DEC7C7381D31 (choix_reponse_id), INDEX IDX_5FB6DEC71E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tracability_reponse (id INT AUTO_INCREMENT NOT NULL, reponse_id INT DEFAULT NULL, INDEX IDX_977703FACF18BB82 (reponse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire_site ADD CONSTRAINT FK_80BBCDB6CF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id)');
        $this->addSql('ALTER TABLE image_site ADD CONSTRAINT FK_9B79E37ACF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7C7381D31 FOREIGN KEY (choix_reponse_id) REFERENCES choix (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC71E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE tracability_reponse ADD CONSTRAINT FK_977703FACF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire_site DROP FOREIGN KEY FK_80BBCDB6CF18BB82');
        $this->addSql('ALTER TABLE image_site DROP FOREIGN KEY FK_9B79E37ACF18BB82');
        $this->addSql('ALTER TABLE tracability_reponse DROP FOREIGN KEY FK_977703FACF18BB82');
        $this->addSql('DROP TABLE commentaire_site');
        $this->addSql('DROP TABLE image_site');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE tracability_reponse');
    }
}
