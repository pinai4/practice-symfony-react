<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211128222208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE domain_contacts (id UUID NOT NULL, owner_id UUID NOT NULL, cr_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, organization VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, zip VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, name_first VARCHAR(255) NOT NULL, name_last VARCHAR(255) NOT NULL, phone_country_code INT NOT NULL, phone_number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN domain_contacts.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN domain_contacts.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN domain_contacts.cr_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN domain_contacts.email IS \'(DC2Type:domain_contact_email)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE domain_contacts');
    }
}
