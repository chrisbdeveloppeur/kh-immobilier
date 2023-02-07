<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207140909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE frais (id INT AUTO_INCREMENT NOT NULL, bien_immo_id INT NOT NULL, title VARCHAR(255) NOT NULL, quantity INT DEFAULT NULL, INDEX IDX_25404C988AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_frais (tag_id INT NOT NULL, frais_id INT NOT NULL, INDEX IDX_7AC559EBAD26311 (tag_id), INDEX IDX_7AC559EBF516DC4 (frais_id), PRIMARY KEY(tag_id, frais_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE frais ADD CONSTRAINT FK_25404C988AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id)');
        $this->addSql('ALTER TABLE tag_frais ADD CONSTRAINT FK_7AC559EBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_frais ADD CONSTRAINT FK_7AC559EBF516DC4 FOREIGN KEY (frais_id) REFERENCES frais (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE charge DROP FOREIGN KEY FK_556BA4348AEFB514');
        $this->addSql('ALTER TABLE tag_charge DROP FOREIGN KEY FK_BC2AED1855284914');
        $this->addSql('ALTER TABLE tag_charge DROP FOREIGN KEY FK_BC2AED18BAD26311');
        $this->addSql('DROP TABLE charge');
        $this->addSql('DROP TABLE tag_charge');
        $this->addSql('ALTER TABLE bien_immo ADD charges INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE charge (id INT AUTO_INCREMENT NOT NULL, bien_immo_id INT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quantity INT DEFAULT NULL, INDEX IDX_556BA4348AEFB514 (bien_immo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tag_charge (tag_id INT NOT NULL, charge_id INT NOT NULL, INDEX IDX_BC2AED18BAD26311 (tag_id), INDEX IDX_BC2AED1855284914 (charge_id), PRIMARY KEY(tag_id, charge_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE charge ADD CONSTRAINT FK_556BA4348AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tag_charge ADD CONSTRAINT FK_BC2AED1855284914 FOREIGN KEY (charge_id) REFERENCES charge (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_charge ADD CONSTRAINT FK_BC2AED18BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE frais DROP FOREIGN KEY FK_25404C988AEFB514');
        $this->addSql('ALTER TABLE tag_frais DROP FOREIGN KEY FK_7AC559EBAD26311');
        $this->addSql('ALTER TABLE tag_frais DROP FOREIGN KEY FK_7AC559EBF516DC4');
        $this->addSql('DROP TABLE frais');
        $this->addSql('DROP TABLE tag_frais');
        $this->addSql('ALTER TABLE bien_immo DROP charges');
    }
}
