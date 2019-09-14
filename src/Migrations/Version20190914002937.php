<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190914002937 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, dicsr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_cart (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishlist_cart (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, cart_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_1F1B251E1AD5CDBF (cart_id), INDEX IDX_1F1B251EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE normal_item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale_item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(191) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(191) NOT NULL, facebook_id VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_cart ADD CONSTRAINT FK_4652B2A0BF396750 FOREIGN KEY (id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishlist_cart ADD CONSTRAINT FK_709C5941BF396750 FOREIGN KEY (id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_cart DROP FOREIGN KEY FK_4652B2A0BF396750');
        $this->addSql('ALTER TABLE wishlist_cart DROP FOREIGN KEY FK_709C5941BF396750');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E1AD5CDBF');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EA76ED395');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE order_cart');
        $this->addSql('DROP TABLE wishlist_cart');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE normal_item');
        $this->addSql('DROP TABLE sale_item');
        $this->addSql('DROP TABLE user');
    }
}
