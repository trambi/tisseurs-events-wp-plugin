<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;
use TisseursEventScheduler\Db;

final class DbTest extends TestCase
{
    public function setUp(): void
    {
        $_POST = [];
        parent::setUp();
        setUp();
    }
    public function tearDown(): void
    {
        tearDown();
        parent::tearDown();
    }
    public function testGetRoomTableName(): void
    {
        // // Given
        global $wpdb;
        $wpdb  = Mockery::mock('wpdb');
        $wpdb->prefix = 'wp_';
        $excepted = 'wp_tes_room';
        $db = new Db($wpdb);
        // // When
        $having = $db->getRoomTableName();
        // // Then
        $this->assertEquals(
            $excepted,
            $having
        );
        $this->assertTrue(true);
    }
    public function testGetRoomTableSql(): void
    {
        // // Given
        global $wpdb;
        $wpdb  = Mockery::mock('wpdb');
        $wpdb->shouldReceive('get_charset_collate')->andReturn('utf-8');
        $wpdb->prefix = 'wp_';
        $excepted = "CREATE TABLE IF NOT EXISTS wp_tes_room (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            capacity int(11) DEFAULT NULL,
            description text,
            opening_hours longtext,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) utf-8;";
        $db = new \TisseursEventScheduler\Db($wpdb);
        // // When
        $having = $db->getRoomTableSql();
        // // Then
        $this->assertEquals(
            $excepted,
            $having
        );
        $this->assertTrue(true);
    }
}
