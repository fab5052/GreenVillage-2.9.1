<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203090010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C14584665A');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C18D9F6D38');
        $this->addSql('ALTER TABLE order_details ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C14584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C18D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD53D89DD2');
        $this->addSql('DROP INDEX IDX_D34A04AD53D89DD2 ON product');
        $this->addSql('ALTER TABLE product CHANGE rubrics_id rubric_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA29EC0FC FOREIGN KEY (rubric_id) REFERENCES rubric (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADA29EC0FC ON product (rubric_id)');
        $this->addSql('DROP INDEX slug ON rubric');
        $this->addSql('ALTER TABLE rubric DROP image');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order_details` MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE `order_details` DROP FOREIGN KEY FK_845CA2C14584665A');
        $this->addSql('ALTER TABLE `order_details` DROP FOREIGN KEY FK_845CA2C18D9F6D38');
        $this->addSql('DROP INDEX `PRIMARY` ON `order_details`');
        $this->addSql('ALTER TABLE `order_details` DROP id');
        $this->addSql('ALTER TABLE `order_details` ADD CONSTRAINT FK_845CA2C14584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order_details` ADD CONSTRAINT FK_845CA2C18D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order_details` ADD PRIMARY KEY (product_id, order_id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA29EC0FC');
        $this->addSql('DROP INDEX IDX_D34A04ADA29EC0FC ON product');
        $this->addSql('ALTER TABLE product CHANGE rubric_id rubrics_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD53D89DD2 FOREIGN KEY (rubrics_id) REFERENCES rubric (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D34A04AD53D89DD2 ON product (rubrics_id)');
        $this->addSql('ALTER TABLE rubric ADD image VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX slug ON rubric (slug)');
    }
}
