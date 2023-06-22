<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211223212329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE domain_domain_linked_contacts (id UUID NOT NULL, domain_id UUID NOT NULL, contact_id UUID NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6E4509AA115F0EE5 ON domain_domain_linked_contacts (domain_id)');
        $this->addSql('CREATE INDEX IDX_6E4509AAE7A1254A ON domain_domain_linked_contacts (contact_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6E4509AA8CDE5729115F0EE5E7A1254A ON domain_domain_linked_contacts (type, domain_id, contact_id)');
        $this->addSql('COMMENT ON COLUMN domain_domain_linked_contacts.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN domain_domain_linked_contacts.domain_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN domain_domain_linked_contacts.contact_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN domain_domain_linked_contacts.type IS \'(DC2Type:domain_domain_linked_contact_type)\'');
        $this->addSql('ALTER TABLE domain_domain_linked_contacts ADD CONSTRAINT FK_6E4509AA115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain_domains (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE domain_domain_linked_contacts ADD CONSTRAINT FK_6E4509AAE7A1254A FOREIGN KEY (contact_id) REFERENCES domain_contacts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE domain_domain_linked_contacts');
    }
}
