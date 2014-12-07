<?php

class Extension_TextareaNew extends Extension
{
    public function install()
    {
        try {

            Symphony::Database()->query(

                "CREATE TABLE IF NOT EXISTS `tbl_fields_textareanew` (

                    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                    `field_id` int(11) unsigned NOT NULL,
                    `formatter` text COLLATE utf8_unicode_ci DEFAULT NULL,
                    `size` int(3) unsigned NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `field_id` (`field_id`)

                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
            );

        } catch (Exception $exception) {

            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (parent::uninstall()) {

            Symphony::Database()->query("DROP TABLE `tbl_fields_textareanew`");

            return true;
        }

        return false;
    }
}
