<?php

declare(strict_types=1);

namespace TisseursEventScheduler;

class Db
{
    public \wpdb $wpdb;
    function __construct(\wpdb $wpdb)
    {
        $this->wpdb = $wpdb;
    }

    /**
     * Return the table name for the room table
     * @return string
     */
    function getRoomTableName()
    {
        return $this->wpdb->prefix . 'tes_room';
    }

    /**
     * Return the SQL string to create the room table
     * @return string
     */
    function getRoomTableSql()
    {
        $charset_collate = $this->wpdb->get_charset_collate();
        $table_name = $this->getRoomTableName();
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            capacity int(11) DEFAULT NULL,
            description text,
            opening_hours longtext,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        return $sql;
    }
}
