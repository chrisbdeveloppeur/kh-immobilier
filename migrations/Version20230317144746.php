<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317144746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bien_immo (id INT AUTO_INCREMENT NOT NULL, copropriete_id INT DEFAULT NULL, user_id INT DEFAULT NULL, etat_des_lieux_id INT DEFAULT NULL, building VARCHAR(255) DEFAULT NULL, street VARCHAR(255) NOT NULL, cp VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, loyer_hc INT NOT NULL, first_day VARCHAR(255) DEFAULT NULL, last_day VARCHAR(255) DEFAULT NULL, month VARCHAR(255) DEFAULT NULL, echeance INT DEFAULT NULL, free TINYINT(1) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, superficie DOUBLE PRECISION DEFAULT NULL, libelle VARCHAR(255) DEFAULT NULL, charges INT DEFAULT NULL, UNIQUE INDEX UNIQ_174DAB76B07769E (copropriete_id), INDEX IDX_174DAB7A76ED395 (user_id), INDEX IDX_174DAB71EA7F144 (etat_des_lieux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE copropriete (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, contact VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, infos LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, created_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documents (id INT AUTO_INCREMENT NOT NULL, locataire_id INT DEFAULT NULL, bien_immo_id INT DEFAULT NULL, created_date DATETIME NOT NULL, file_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_A2B07288D8A38199 (locataire_id), INDEX IDX_A2B072888AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, siret VARCHAR(255) DEFAULT NULL, tva_number VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat_des_lieux (id INT AUTO_INCREMENT NOT NULL, creator_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, sens_circuit TINYINT(1) NOT NULL, date DATETIME DEFAULT NULL, INDEX IDX_F721031261220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat_des_lieux_form_field (etat_des_lieux_id INT NOT NULL, form_field_id INT NOT NULL, INDEX IDX_C6705C591EA7F144 (etat_des_lieux_id), INDEX IDX_C6705C59F50D82F4 (form_field_id), PRIMARY KEY(etat_des_lieux_id, form_field_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE financement (id INT AUTO_INCREMENT NOT NULL, bien_immo_id INT NOT NULL, type VARCHAR(255) NOT NULL, date_investissement DATE NOT NULL, montant DOUBLE PRECISION NOT NULL, taux DOUBLE PRECISION NOT NULL, mensualites DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_59895F568AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_field (id INT AUTO_INCREMENT NOT NULL, form_section_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_D8B2E19BD1E4AFEE (form_section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_section (id INT AUTO_INCREMENT NOT NULL, etat_des_lieux_id INT NOT NULL, label VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, INDEX IDX_1C1F1AD41EA7F144 (etat_des_lieux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frais (id INT AUTO_INCREMENT NOT NULL, bien_immo_id INT NOT NULL, title VARCHAR(255) NOT NULL, quantity INT DEFAULT NULL, INDEX IDX_25404C988AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locataire (id INT AUTO_INCREMENT NOT NULL, logement_id INT DEFAULT NULL, user_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, gender VARCHAR(255) DEFAULT NULL, mode VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, sans_logement TINYINT(1) NOT NULL, phone VARCHAR(255) DEFAULT NULL, INDEX IDX_C47CF6EB58ABF955 (logement_id), INDEX IDX_C47CF6EBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestataire (id INT AUTO_INCREMENT NOT NULL, bien_immo_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, infos LONGTEXT DEFAULT NULL, INDEX IDX_60A264808AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quittance (id INT AUTO_INCREMENT NOT NULL, locataire_id INT DEFAULT NULL, bien_immo_id INT DEFAULT NULL, file_name VARCHAR(255) NOT NULL, created_date DATETIME NOT NULL, year VARCHAR(255) NOT NULL, pdf_exist TINYINT(1) DEFAULT NULL, date DATETIME DEFAULT NULL, month VARCHAR(255) DEFAULT NULL, payed TINYINT(1) NOT NULL, loyer_ht INT DEFAULT NULL, charges INT DEFAULT NULL, mode VARCHAR(255) DEFAULT NULL, INDEX IDX_D57587DDD8A38199 (locataire_id), INDEX IDX_D57587DD8AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solde (id INT AUTO_INCREMENT NOT NULL, bien_immo_id INT NOT NULL, quantity INT DEFAULT NULL, payed TINYINT(1) NOT NULL, INDEX IDX_669183678AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_frais (tag_id INT NOT NULL, frais_id INT NOT NULL, INDEX IDX_7AC559EBAD26311 (tag_id), INDEX IDX_7AC559EBF516DC4 (frais_id), PRIMARY KEY(tag_id, frais_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_bien_immo (tag_id INT NOT NULL, bien_immo_id INT NOT NULL, INDEX IDX_1043E090BAD26311 (tag_id), INDEX IDX_1043E0908AEFB514 (bien_immo_id), PRIMARY KEY(tag_id, bien_immo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bien_immo ADD CONSTRAINT FK_174DAB76B07769E FOREIGN KEY (copropriete_id) REFERENCES copropriete (id)');
        $this->addSql('ALTER TABLE bien_immo ADD CONSTRAINT FK_174DAB7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bien_immo ADD CONSTRAINT FK_174DAB71EA7F144 FOREIGN KEY (etat_des_lieux_id) REFERENCES etat_des_lieux (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288D8A38199 FOREIGN KEY (locataire_id) REFERENCES locataire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B072888AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etat_des_lieux ADD CONSTRAINT FK_F721031261220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE etat_des_lieux_form_field ADD CONSTRAINT FK_C6705C591EA7F144 FOREIGN KEY (etat_des_lieux_id) REFERENCES etat_des_lieux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etat_des_lieux_form_field ADD CONSTRAINT FK_C6705C59F50D82F4 FOREIGN KEY (form_field_id) REFERENCES form_field (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE financement ADD CONSTRAINT FK_59895F568AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id)');
        $this->addSql('ALTER TABLE form_field ADD CONSTRAINT FK_D8B2E19BD1E4AFEE FOREIGN KEY (form_section_id) REFERENCES form_section (id)');
        $this->addSql('ALTER TABLE form_section ADD CONSTRAINT FK_1C1F1AD41EA7F144 FOREIGN KEY (etat_des_lieux_id) REFERENCES etat_des_lieux (id)');
        $this->addSql('ALTER TABLE frais ADD CONSTRAINT FK_25404C988AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id)');
        $this->addSql('ALTER TABLE locataire ADD CONSTRAINT FK_C47CF6EB58ABF955 FOREIGN KEY (logement_id) REFERENCES bien_immo (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE locataire ADD CONSTRAINT FK_C47CF6EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prestataire ADD CONSTRAINT FK_60A264808AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quittance ADD CONSTRAINT FK_D57587DDD8A38199 FOREIGN KEY (locataire_id) REFERENCES locataire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quittance ADD CONSTRAINT FK_D57587DD8AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE solde ADD CONSTRAINT FK_669183678AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id)');
        $this->addSql('ALTER TABLE tag_frais ADD CONSTRAINT FK_7AC559EBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_frais ADD CONSTRAINT FK_7AC559EBF516DC4 FOREIGN KEY (frais_id) REFERENCES frais (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_bien_immo ADD CONSTRAINT FK_1043E090BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_bien_immo ADD CONSTRAINT FK_1043E0908AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien_immo DROP FOREIGN KEY FK_174DAB76B07769E');
        $this->addSql('ALTER TABLE bien_immo DROP FOREIGN KEY FK_174DAB7A76ED395');
        $this->addSql('ALTER TABLE bien_immo DROP FOREIGN KEY FK_174DAB71EA7F144');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288D8A38199');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B072888AEFB514');
        $this->addSql('ALTER TABLE etat_des_lieux DROP FOREIGN KEY FK_F721031261220EA6');
        $this->addSql('ALTER TABLE etat_des_lieux_form_field DROP FOREIGN KEY FK_C6705C591EA7F144');
        $this->addSql('ALTER TABLE etat_des_lieux_form_field DROP FOREIGN KEY FK_C6705C59F50D82F4');
        $this->addSql('ALTER TABLE financement DROP FOREIGN KEY FK_59895F568AEFB514');
        $this->addSql('ALTER TABLE form_field DROP FOREIGN KEY FK_D8B2E19BD1E4AFEE');
        $this->addSql('ALTER TABLE form_section DROP FOREIGN KEY FK_1C1F1AD41EA7F144');
        $this->addSql('ALTER TABLE frais DROP FOREIGN KEY FK_25404C988AEFB514');
        $this->addSql('ALTER TABLE locataire DROP FOREIGN KEY FK_C47CF6EB58ABF955');
        $this->addSql('ALTER TABLE locataire DROP FOREIGN KEY FK_C47CF6EBA76ED395');
        $this->addSql('ALTER TABLE prestataire DROP FOREIGN KEY FK_60A264808AEFB514');
        $this->addSql('ALTER TABLE quittance DROP FOREIGN KEY FK_D57587DDD8A38199');
        $this->addSql('ALTER TABLE quittance DROP FOREIGN KEY FK_D57587DD8AEFB514');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE solde DROP FOREIGN KEY FK_669183678AEFB514');
        $this->addSql('ALTER TABLE tag_frais DROP FOREIGN KEY FK_7AC559EBAD26311');
        $this->addSql('ALTER TABLE tag_frais DROP FOREIGN KEY FK_7AC559EBF516DC4');
        $this->addSql('ALTER TABLE tag_bien_immo DROP FOREIGN KEY FK_1043E090BAD26311');
        $this->addSql('ALTER TABLE tag_bien_immo DROP FOREIGN KEY FK_1043E0908AEFB514');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A4AEAFEA');
        $this->addSql('DROP TABLE bien_immo');
        $this->addSql('DROP TABLE copropriete');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE documents');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE etat_des_lieux');
        $this->addSql('DROP TABLE etat_des_lieux_form_field');
        $this->addSql('DROP TABLE financement');
        $this->addSql('DROP TABLE form_field');
        $this->addSql('DROP TABLE form_section');
        $this->addSql('DROP TABLE frais');
        $this->addSql('DROP TABLE locataire');
        $this->addSql('DROP TABLE prestataire');
        $this->addSql('DROP TABLE quittance');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE solde');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_frais');
        $this->addSql('DROP TABLE tag_bien_immo');
        $this->addSql('DROP TABLE user');
    }
}
