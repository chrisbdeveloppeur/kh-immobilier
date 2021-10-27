<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211027111227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bien_immo (id INT AUTO_INCREMENT NOT NULL, copropriete_id INT DEFAULT NULL, user_id INT DEFAULT NULL, building VARCHAR(255) DEFAULT NULL, street VARCHAR(255) NOT NULL, cp VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, loyer_hc NUMERIC(10, 2) NOT NULL, charges NUMERIC(10, 2) NOT NULL, first_day VARCHAR(255) DEFAULT NULL, last_day VARCHAR(255) DEFAULT NULL, month VARCHAR(255) DEFAULT NULL, echeance INT DEFAULT NULL, free TINYINT(1) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, superficie DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_174DAB76B07769E (copropriete_id), INDEX IDX_174DAB7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE copropriete (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, contact VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, infos LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, created_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, siret VARCHAR(255) DEFAULT NULL, tva_number VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locataire (id INT AUTO_INCREMENT NOT NULL, logement_id INT DEFAULT NULL, user_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, mode VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, sans_logement TINYINT(1) NOT NULL, phone VARCHAR(255) DEFAULT NULL, INDEX IDX_C47CF6EB58ABF955 (logement_id), INDEX IDX_C47CF6EBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestataire (id INT AUTO_INCREMENT NOT NULL, bien_immo_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, infos LONGTEXT DEFAULT NULL, INDEX IDX_60A264808AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quittance (id INT AUTO_INCREMENT NOT NULL, locataire_id INT DEFAULT NULL, bien_immo_id INT DEFAULT NULL, file_name VARCHAR(255) NOT NULL, created_date DATETIME NOT NULL, year VARCHAR(255) NOT NULL, INDEX IDX_D57587DDD8A38199 (locataire_id), INDEX IDX_D57587DD8AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solde (id INT AUTO_INCREMENT NOT NULL, bien_immo_id INT DEFAULT NULL, month_paid TINYINT(1) NOT NULL, echeance_pasted TINYINT(1) NOT NULL, malus_added TINYINT(1) NOT NULL, malus_quantity DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_669183678AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, gender VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bien_immo ADD CONSTRAINT FK_174DAB76B07769E FOREIGN KEY (copropriete_id) REFERENCES copropriete (id)');
        $this->addSql('ALTER TABLE bien_immo ADD CONSTRAINT FK_174DAB7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE locataire ADD CONSTRAINT FK_C47CF6EB58ABF955 FOREIGN KEY (logement_id) REFERENCES bien_immo (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE locataire ADD CONSTRAINT FK_C47CF6EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prestataire ADD CONSTRAINT FK_60A264808AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id)');
        $this->addSql('ALTER TABLE quittance ADD CONSTRAINT FK_D57587DDD8A38199 FOREIGN KEY (locataire_id) REFERENCES locataire (id)');
        $this->addSql('ALTER TABLE quittance ADD CONSTRAINT FK_D57587DD8AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id)');
        $this->addSql('ALTER TABLE solde ADD CONSTRAINT FK_669183678AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE locataire DROP FOREIGN KEY FK_C47CF6EB58ABF955');
        $this->addSql('ALTER TABLE prestataire DROP FOREIGN KEY FK_60A264808AEFB514');
        $this->addSql('ALTER TABLE quittance DROP FOREIGN KEY FK_D57587DD8AEFB514');
        $this->addSql('ALTER TABLE solde DROP FOREIGN KEY FK_669183678AEFB514');
        $this->addSql('ALTER TABLE bien_immo DROP FOREIGN KEY FK_174DAB76B07769E');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A4AEAFEA');
        $this->addSql('ALTER TABLE quittance DROP FOREIGN KEY FK_D57587DDD8A38199');
        $this->addSql('ALTER TABLE bien_immo DROP FOREIGN KEY FK_174DAB7A76ED395');
        $this->addSql('ALTER TABLE locataire DROP FOREIGN KEY FK_C47CF6EBA76ED395');
        $this->addSql('DROP TABLE bien_immo');
        $this->addSql('DROP TABLE copropriete');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE locataire');
        $this->addSql('DROP TABLE prestataire');
        $this->addSql('DROP TABLE quittance');
        $this->addSql('DROP TABLE solde');
        $this->addSql('DROP TABLE user');
    }
}
