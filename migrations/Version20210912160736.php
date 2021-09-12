<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210912160736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_group (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, groups_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8F02BF9DA76ED395 (user_id), INDEX IDX_8F02BF9DF373DCF (groups_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DF373DCF FOREIGN KEY (groups_id) REFERENCES groups (id)');
        $this->addSql('DROP TABLE users_groups');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_groups (users_id INT NOT NULL, groups_id INT NOT NULL, INDEX IDX_FF8AB7E067B3B43D (users_id), INDEX IDX_FF8AB7E0F373DCF (groups_id), PRIMARY KEY(users_id, groups_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E067B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E0F373DCF FOREIGN KEY (groups_id) REFERENCES groups (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_group');
    }
}
