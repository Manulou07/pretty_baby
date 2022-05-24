<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220315112559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations ADD fk_id_adresse_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2398C272949 FOREIGN KEY (fk_id_adresse_id) REFERENCES adresse (id)');
        $this->addSql('CREATE INDEX IDX_4DA2398C272949 ON reservations (fk_id_adresse_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse CHANGE numero numero VARCHAR(10) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom_rue nom_rue VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE type type VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE ville ville VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE adresse_complete adresse_complete VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commentaires CHANGE comment comment LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE forfait CHANGE type_forfait type_forfait VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE images CHANGE nameimg nameimg VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE realisations CHANGE img img VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE titre titre VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2398C272949');
        $this->addSql('DROP INDEX IDX_4DA2398C272949 ON reservations');
        $this->addSql('ALTER TABLE reservations DROP fk_id_adresse_id, CHANGE msg_resa msg_resa LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom nom VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(100) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
