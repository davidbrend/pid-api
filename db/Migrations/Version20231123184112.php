<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231123184112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE openingHours (id INT AUTO_INCREMENT NOT NULL, point_of_sale_id VARCHAR(255) DEFAULT NULL, `fromDay` INT NOT NULL, `toDay` INT NOT NULL, `dateRangeString` VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F07B24B96B7E9A73 (point_of_sale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payMethods (id INT AUTO_INCREMENT NOT NULL, point_of_sale_id VARCHAR(255) DEFAULT NULL, val INT NOT NULL, `description` VARCHAR(255) NOT NULL, INDEX IDX_E9F7E3396B7E9A73 (point_of_sale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pointTypes (id INT AUTO_INCREMENT NOT NULL, point_of_sale_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, `description` VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4C54314D6B7E9A73 (point_of_sale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pointsOfSale (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, lat NUMERIC(10, 7) NOT NULL, lon NUMERIC(10, 7) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE serviceGroups (id INT AUTO_INCREMENT NOT NULL, point_of_sale_id VARCHAR(255) DEFAULT NULL, `description` VARCHAR(255) NOT NULL, INDEX IDX_D48B3D3D6B7E9A73 (point_of_sale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE services (id INT AUTO_INCREMENT NOT NULL, service_group_id INT DEFAULT NULL, val INT NOT NULL, `description` VARCHAR(255) NOT NULL, INDEX IDX_7332E169722827A (service_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE openingHours ADD CONSTRAINT FK_F07B24B96B7E9A73 FOREIGN KEY (point_of_sale_id) REFERENCES pointsOfSale (id)');
        $this->addSql('ALTER TABLE payMethods ADD CONSTRAINT FK_E9F7E3396B7E9A73 FOREIGN KEY (point_of_sale_id) REFERENCES pointsOfSale (id)');
        $this->addSql('ALTER TABLE pointTypes ADD CONSTRAINT FK_4C54314D6B7E9A73 FOREIGN KEY (point_of_sale_id) REFERENCES pointsOfSale (id)');
        $this->addSql('ALTER TABLE serviceGroups ADD CONSTRAINT FK_D48B3D3D6B7E9A73 FOREIGN KEY (point_of_sale_id) REFERENCES pointsOfSale (id)');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E169722827A FOREIGN KEY (service_group_id) REFERENCES serviceGroups (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE openingHours DROP FOREIGN KEY FK_F07B24B96B7E9A73');
        $this->addSql('ALTER TABLE payMethods DROP FOREIGN KEY FK_E9F7E3396B7E9A73');
        $this->addSql('ALTER TABLE pointTypes DROP FOREIGN KEY FK_4C54314D6B7E9A73');
        $this->addSql('ALTER TABLE serviceGroups DROP FOREIGN KEY FK_D48B3D3D6B7E9A73');
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E169722827A');
        $this->addSql('DROP TABLE openingHours');
        $this->addSql('DROP TABLE payMethods');
        $this->addSql('DROP TABLE pointTypes');
        $this->addSql('DROP TABLE pointsOfSale');
        $this->addSql('DROP TABLE serviceGroups');
        $this->addSql('DROP TABLE services');
    }
}
