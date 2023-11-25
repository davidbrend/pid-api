<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231125134706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE openingHours (id INT AUTO_INCREMENT NOT NULL, `fromDay` INT NOT NULL, `toDay` INT NOT NULL, `dateRangeString` VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pointsOfSale (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, lat NUMERIC(10, 7) NOT NULL, lon NUMERIC(10, 7) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE point_of_sale_opening_hours (point_of_sale_id VARCHAR(255) NOT NULL, opening_hours_id INT NOT NULL, INDEX IDX_E42839DC6B7E9A73 (point_of_sale_id), INDEX IDX_E42839DCCE298D68 (opening_hours_id), PRIMARY KEY(point_of_sale_id, opening_hours_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE point_of_sale_opening_hours ADD CONSTRAINT FK_E42839DC6B7E9A73 FOREIGN KEY (point_of_sale_id) REFERENCES pointsOfSale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE point_of_sale_opening_hours ADD CONSTRAINT FK_E42839DCCE298D68 FOREIGN KEY (opening_hours_id) REFERENCES openingHours (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE point_of_sale_opening_hours DROP FOREIGN KEY FK_E42839DC6B7E9A73');
        $this->addSql('ALTER TABLE point_of_sale_opening_hours DROP FOREIGN KEY FK_E42839DCCE298D68');
        $this->addSql('DROP TABLE openingHours');
        $this->addSql('DROP TABLE pointsOfSale');
        $this->addSql('DROP TABLE point_of_sale_opening_hours');
    }
}
