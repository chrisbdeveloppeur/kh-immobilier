<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323185240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE frais (id INT AUTO_INCREMENT NOT NULL, bien_immo_id INT NOT NULL, title VARCHAR(255) NOT NULL, quantity INT DEFAULT NULL, INDEX IDX_25404C988AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_frais (tag_id INT NOT NULL, frais_id INT NOT NULL, INDEX IDX_7AC559EBAD26311 (tag_id), INDEX IDX_7AC559EBF516DC4 (frais_id), PRIMARY KEY(tag_id, frais_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_bien_immo (tag_id INT NOT NULL, bien_immo_id INT NOT NULL, INDEX IDX_1043E090BAD26311 (tag_id), INDEX IDX_1043E0908AEFB514 (bien_immo_id), PRIMARY KEY(tag_id, bien_immo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE frais ADD CONSTRAINT FK_25404C988AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tag_frais ADD CONSTRAINT FK_7AC559EBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_frais ADD CONSTRAINT FK_7AC559EBF516DC4 FOREIGN KEY (frais_id) REFERENCES frais (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_bien_immo ADD CONSTRAINT FK_1043E090BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_bien_immo ADD CONSTRAINT FK_1043E0908AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bien_immo CHANGE charges charges INT DEFAULT NULL');
        $this->addSql('ALTER TABLE locataire CHANGE gender gender VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE solde DROP INDEX UNIQ_669183678AEFB514, ADD INDEX IDX_669183678AEFB514 (bien_immo_id)');
        $this->addSql('ALTER TABLE solde ADD quantity INT DEFAULT NULL, ADD payed TINYINT(1) NOT NULL, DROP month_paid, DROP echeance_pasted, DROP malus_added, DROP malus_quantity, CHANGE bien_immo_id bien_immo_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE frais DROP FOREIGN KEY FK_25404C988AEFB514');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE tag_frais DROP FOREIGN KEY FK_7AC559EBAD26311');
        $this->addSql('ALTER TABLE tag_frais DROP FOREIGN KEY FK_7AC559EBF516DC4');
        $this->addSql('ALTER TABLE tag_bien_immo DROP FOREIGN KEY FK_1043E090BAD26311');
        $this->addSql('ALTER TABLE tag_bien_immo DROP FOREIGN KEY FK_1043E0908AEFB514');
        $this->addSql('DROP TABLE frais');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_frais');
        $this->addSql('DROP TABLE tag_bien_immo');
        $this->addSql('ALTER TABLE bien_immo CHANGE charges charges INT NOT NULL');
        $this->addSql('ALTER TABLE locataire CHANGE gender gender VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE solde DROP INDEX IDX_669183678AEFB514, ADD UNIQUE INDEX UNIQ_669183678AEFB514 (bien_immo_id)');
        $this->addSql('ALTER TABLE solde ADD echeance_pasted TINYINT(1) NOT NULL, ADD malus_added TINYINT(1) NOT NULL, ADD malus_quantity DOUBLE PRECISION DEFAULT NULL, DROP quantity, CHANGE bien_immo_id bien_immo_id INT DEFAULT NULL, CHANGE payed month_paid TINYINT(1) NOT NULL');
    }
}
