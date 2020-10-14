<?php

/**
 * This file is part of the Brille24 tierprice plugin.
 *
 * (c) Brille24 GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Brille24\SyliusTierPricePlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201014111513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brille24_tierprice (id INT AUTO_INCREMENT NOT NULL, channel_id INT DEFAULT NULL, product_variant_id INT DEFAULT NULL, customer_group_id INT DEFAULT NULL, price INT NOT NULL, qty INT NOT NULL, INDEX IDX_BA5254F872F5A1AA (channel_id), INDEX IDX_BA5254F8A80EF684 (product_variant_id), INDEX IDX_BA5254F8D2919A68 (customer_group_id), UNIQUE INDEX no_duplicate_prices (qty, channel_id, product_variant_id, customer_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brille24_tierprice ADD CONSTRAINT FK_BA5254F872F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id)');
        $this->addSql('ALTER TABLE brille24_tierprice ADD CONSTRAINT FK_BA5254F8A80EF684 FOREIGN KEY (product_variant_id) REFERENCES sylius_product_variant (id)');
        $this->addSql('ALTER TABLE brille24_tierprice ADD CONSTRAINT FK_BA5254F8D2919A68 FOREIGN KEY (customer_group_id) REFERENCES sylius_customer_group (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE brille24_tierprice');
    }
}
