<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200911103229 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE armoire (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, numero VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, INDEX IDX_93771E4098260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_choix (question_id INT NOT NULL, choix_id INT NOT NULL, INDEX IDX_7FA391951E27F6BF (question_id), INDEX IDX_7FA39195D9144651 (choix_id), PRIMARY KEY(question_id, choix_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE armoire ADD CONSTRAINT FK_93771E4098260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE question_choix ADD CONSTRAINT FK_7FA391951E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question_choix ADD CONSTRAINT FK_7FA39195D9144651 FOREIGN KEY (choix_id) REFERENCES choix (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE armoire');
        $this->addSql('DROP TABLE question_choix');
    }
}
