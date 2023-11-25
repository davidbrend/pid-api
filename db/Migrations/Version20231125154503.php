<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231125154503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE openingHours (id INT AUTO_INCREMENT NOT NULL, `fromDay` INT NOT NULL, `toDay` INT NOT NULL, `dateRangeString` VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payMethods (id INT AUTO_INCREMENT NOT NULL, val INT NOT NULL, `description` VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pointTypes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, `description` VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pointsOfSale (id VARCHAR(255) NOT NULL, type_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, lat NUMERIC(10, 7) NOT NULL, lon NUMERIC(10, 7) NOT NULL, INDEX IDX_5C3B8F46C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE point_of_sale_service_group (point_of_sale_id VARCHAR(255) NOT NULL, service_group_id INT NOT NULL, INDEX IDX_6DA51F56B7E9A73 (point_of_sale_id), INDEX IDX_6DA51F5722827A (service_group_id), PRIMARY KEY(point_of_sale_id, service_group_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE point_of_sale_pay_method (point_of_sale_id VARCHAR(255) NOT NULL, pay_method_id INT NOT NULL, INDEX IDX_CBBA2DCE6B7E9A73 (point_of_sale_id), INDEX IDX_CBBA2DCE3486861B (pay_method_id), PRIMARY KEY(point_of_sale_id, pay_method_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE point_of_sale_opening_hours (point_of_sale_id VARCHAR(255) NOT NULL, opening_hours_id INT NOT NULL, INDEX IDX_E42839DC6B7E9A73 (point_of_sale_id), INDEX IDX_E42839DCCE298D68 (opening_hours_id), PRIMARY KEY(point_of_sale_id, opening_hours_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE serviceGroups (id INT AUTO_INCREMENT NOT NULL, `description` VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_group_service (service_group_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_B722745C722827A (service_group_id), INDEX IDX_B722745CED5CA9E6 (service_id), PRIMARY KEY(service_group_id, service_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE services (id INT AUTO_INCREMENT NOT NULL, val INT NOT NULL, `description` VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pointsOfSale ADD CONSTRAINT FK_5C3B8F46C54C8C93 FOREIGN KEY (type_id) REFERENCES pointTypes (id)');
        $this->addSql('ALTER TABLE point_of_sale_service_group ADD CONSTRAINT FK_6DA51F56B7E9A73 FOREIGN KEY (point_of_sale_id) REFERENCES pointsOfSale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE point_of_sale_service_group ADD CONSTRAINT FK_6DA51F5722827A FOREIGN KEY (service_group_id) REFERENCES serviceGroups (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE point_of_sale_pay_method ADD CONSTRAINT FK_CBBA2DCE6B7E9A73 FOREIGN KEY (point_of_sale_id) REFERENCES pointsOfSale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE point_of_sale_pay_method ADD CONSTRAINT FK_CBBA2DCE3486861B FOREIGN KEY (pay_method_id) REFERENCES payMethods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE point_of_sale_opening_hours ADD CONSTRAINT FK_E42839DC6B7E9A73 FOREIGN KEY (point_of_sale_id) REFERENCES pointsOfSale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE point_of_sale_opening_hours ADD CONSTRAINT FK_E42839DCCE298D68 FOREIGN KEY (opening_hours_id) REFERENCES openingHours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_group_service ADD CONSTRAINT FK_B722745C722827A FOREIGN KEY (service_group_id) REFERENCES serviceGroups (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_group_service ADD CONSTRAINT FK_B722745CED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pointsOfSale DROP FOREIGN KEY FK_5C3B8F46C54C8C93');
        $this->addSql('ALTER TABLE point_of_sale_service_group DROP FOREIGN KEY FK_6DA51F56B7E9A73');
        $this->addSql('ALTER TABLE point_of_sale_service_group DROP FOREIGN KEY FK_6DA51F5722827A');
        $this->addSql('ALTER TABLE point_of_sale_pay_method DROP FOREIGN KEY FK_CBBA2DCE6B7E9A73');
        $this->addSql('ALTER TABLE point_of_sale_pay_method DROP FOREIGN KEY FK_CBBA2DCE3486861B');
        $this->addSql('ALTER TABLE point_of_sale_opening_hours DROP FOREIGN KEY FK_E42839DC6B7E9A73');
        $this->addSql('ALTER TABLE point_of_sale_opening_hours DROP FOREIGN KEY FK_E42839DCCE298D68');
        $this->addSql('ALTER TABLE service_group_service DROP FOREIGN KEY FK_B722745C722827A');
        $this->addSql('ALTER TABLE service_group_service DROP FOREIGN KEY FK_B722745CED5CA9E6');
        $this->addSql('DROP TABLE openingHours');
        $this->addSql('DROP TABLE payMethods');
        $this->addSql('DROP TABLE pointTypes');
        $this->addSql('DROP TABLE pointsOfSale');
        $this->addSql('DROP TABLE point_of_sale_service_group');
        $this->addSql('DROP TABLE point_of_sale_pay_method');
        $this->addSql('DROP TABLE point_of_sale_opening_hours');
        $this->addSql('DROP TABLE serviceGroups');
        $this->addSql('DROP TABLE service_group_service');
        $this->addSql('DROP TABLE services');
    }
}
