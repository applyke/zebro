<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170110124412 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO role (role.code, role.name) VALUES ("user", "User")');
        $this->addSql('INSERT INTO role (role.code, role.name) VALUES ("admin", "Admin")');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE * FROM role');
    }
}
