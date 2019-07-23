<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190722141839 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quack (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, parent INT DEFAULT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_83D44F6F60BB6FE6 (auteur_id), INDEX IDX_83D44F6F3D8E604F (parent), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quack_tags (quack_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_70A21AAED3950CA9 (quack_id), INDEX IDX_70A21AAE8D7B4FB4 (tags_id), PRIMARY KEY(quack_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, duckname VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64990361416 (duckname), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quack ADD CONSTRAINT FK_83D44F6F60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quack ADD CONSTRAINT FK_83D44F6F3D8E604F FOREIGN KEY (parent) REFERENCES quack (id)');
        $this->addSql('ALTER TABLE quack_tags ADD CONSTRAINT FK_70A21AAED3950CA9 FOREIGN KEY (quack_id) REFERENCES quack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quack_tags ADD CONSTRAINT FK_70A21AAE8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quack DROP FOREIGN KEY FK_83D44F6F3D8E604F');
        $this->addSql('ALTER TABLE quack_tags DROP FOREIGN KEY FK_70A21AAED3950CA9');
        $this->addSql('ALTER TABLE quack_tags DROP FOREIGN KEY FK_70A21AAE8D7B4FB4');
        $this->addSql('ALTER TABLE quack DROP FOREIGN KEY FK_83D44F6F60BB6FE6');
        $this->addSql('DROP TABLE quack');
        $this->addSql('DROP TABLE quack_tags');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE user');
    }
}
