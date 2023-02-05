<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205221251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag_charge (tag_id INT NOT NULL, charge_id INT NOT NULL, INDEX IDX_BC2AED18BAD26311 (tag_id), INDEX IDX_BC2AED1855284914 (charge_id), PRIMARY KEY(tag_id, charge_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_bien_immo (tag_id INT NOT NULL, bien_immo_id INT NOT NULL, INDEX IDX_1043E090BAD26311 (tag_id), INDEX IDX_1043E0908AEFB514 (bien_immo_id), PRIMARY KEY(tag_id, bien_immo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_charge ADD CONSTRAINT FK_BC2AED18BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_charge ADD CONSTRAINT FK_BC2AED1855284914 FOREIGN KEY (charge_id) REFERENCES charge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_bien_immo ADD CONSTRAINT FK_1043E090BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_bien_immo ADD CONSTRAINT FK_1043E0908AEFB514 FOREIGN KEY (bien_immo_id) REFERENCES bien_immo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_charge DROP FOREIGN KEY FK_BC2AED18BAD26311');
        $this->addSql('ALTER TABLE tag_charge DROP FOREIGN KEY FK_BC2AED1855284914');
        $this->addSql('ALTER TABLE tag_bien_immo DROP FOREIGN KEY FK_1043E090BAD26311');
        $this->addSql('ALTER TABLE tag_bien_immo DROP FOREIGN KEY FK_1043E0908AEFB514');
        $this->addSql('DROP TABLE tag_charge');
        $this->addSql('DROP TABLE tag_bien_immo');
    }
}
