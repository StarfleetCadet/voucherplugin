<?php
class Config
{
    /**
     * The plugin name (also name of the plugin's directory)
     */
    const PLUGIN_NAME = "voucherplugin";

    /** Some paths */
    const PLUGIN_BASE_PATH = ABSPATH.'wp-content/plugins/'.self::PLUGIN_NAME.'/';
    const VIEW_PATH = self::PLUGIN_BASE_PATH.'view/';
    const CONTROLLER_PATH = self::PLUGIN_BASE_PATH.'controller/';
    const MODEL_PATH = self:: PLUGIN_BASE_PATH.'model/';
    const DAO_PATH = self:: PLUGIN_BASE_PATH.'dao/';
    const LIB_PATH = self::PLUGIN_BASE_PATH.'lib/';
    const IMAGE_PATH = self::PLUGIN_BASE_PATH.'img/';
    const TMP_PATH = self::PLUGIN_BASE_PATH.'tmp/';

    /**
     * Border around the background image in inch
     */
    const PDF_BORDER_IN = 0.1;

    /**
     * Size of the pdf: "A4", "A5", "Letter", "Legal"
     */
    const PDF_SIZE = "A4";

    /**
     * Orientation of the pdf document: "P" for portrait, "H" for horizontal
     */
    const PDF_ORIENTATION = "P";
}