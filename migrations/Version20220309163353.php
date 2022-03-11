<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309163353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, fk_id_user_id INT NOT NULL, numero VARCHAR(10) DEFAULT NULL, nom_rue VARCHAR(100) NOT NULL, type VARCHAR(100) NOT NULL, codepostal INT NOT NULL, ville VARCHAR(100) NOT NULL, INDEX IDX_C35F0816899DB076 (fk_id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, fk_id_realisations_id INT NOT NULL, comment LONGTEXT NOT NULL, publish TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_D9BEC0C4A15DD78A (fk_id_realisations_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, date_dispo DATE NOT NULL, is_book TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forfait (id INT AUTO_INCREMENT NOT NULL, type_forfait VARCHAR(100) NOT NULL, prix_forfait DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, relation_id INT DEFAULT NULL, nameimg VARCHAR(100) DEFAULT NULL, INDEX IDX_E01FBE6A3256915B (relation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realisations (id INT AUTO_INCREMENT NOT NULL, fk_id_user_id INT DEFAULT NULL, img VARCHAR(30) NOT NULL, titre VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_FC5C476D899DB076 (fk_id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, fk_id_forfait_id INT NOT NULL, date_prestation_id INT NOT NULL, fk_id_user_id INT DEFAULT NULL, date_resa DATE NOT NULL, msg_resa LONGTEXT NOT NULL, INDEX IDX_4DA23951ADAA39 (fk_id_forfait_id), UNIQUE INDEX UNIQ_4DA239E57BB41D (date_prestation_id), INDEX IDX_4DA239899DB076 (fk_id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(100) DEFAULT NULL, prenom VARCHAR(100) DEFAULT NULL, telephone INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816899DB076 FOREIGN KEY (fk_id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4A15DD78A FOREIGN KEY (fk_id_realisations_id) REFERENCES realisations (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A3256915B FOREIGN KEY (relation_id) REFERENCES realisations (id)');
        $this->addSql('ALTER TABLE realisations ADD CONSTRAINT FK_FC5C476D899DB076 FOREIGN KEY (fk_id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23951ADAA39 FOREIGN KEY (fk_id_forfait_id) REFERENCES forfait (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239E57BB41D FOREIGN KEY (date_prestation_id) REFERENCES disponibilite (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239899DB076 FOREIGN KEY (fk_id_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239E57BB41D');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23951ADAA39');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4A15DD78A');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A3256915B');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816899DB076');
        $this->addSql('ALTER TABLE realisations DROP FOREIGN KEY FK_FC5C476D899DB076');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239899DB076');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE forfait');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE realisations');
        $this->addSql('DROP TABLE reservations');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
