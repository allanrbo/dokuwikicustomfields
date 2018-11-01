<?php
/**
 * Plugin customfields
 */

if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once DOKU_PLUGIN.'syntax.php';

class syntax_plugin_customfields extends DokuWiki_Syntax_Plugin {
    static $customFields = array();

    function getInfo() {
        return array(
            'author' => 'Allan Boll',
            'email'  => 'allan@acoby.com',
            'date'   => '2013-09-29',
            'name'   => 'Custom fields',
            'desc'   => 'A way of defining data in wiki text which can then be used in a template. Similar to the custom fields concept in WordPress.',
            'url'    => 'http://www.acoby.com'
        );
    }

    function getType(){ return 'formatting';}
    function getPType(){ return 'stack';}
    function getSort(){ return 200; }

    function connectTo($mode) {
        global $ID;

        $this->Lexer->addEntryPattern('##[#A-Za-z0-9-]+', $mode, 'plugin_customfields');

        // Ensure that deleted fields are cleared
        $clearFields = array();
        foreach(p_get_metadata($ID) as $k => $v) {
            if(strpos($k, 'customfield-') === 0) {
                $clearFields[$k] = null;
            }
        }
        global $ID;
        p_set_metadata($ID, $clearFields);
    }

    function postConnect() {
        $this->Lexer->addExitPattern('###?', 'plugin_customfields');
    }

    function handle($match, $state, $pos, Doku_Handler $handler) {
        switch ($state) {
            case DOKU_LEXER_ENTER:
                $subject = "abcdef";
                $pattern = '/^def/';
                preg_match('/##([#A-Za-z0-9-]+)/', $match, $r);
                return array($state, $r[1]);

            // Ordinary text
            case DOKU_LEXER_UNMATCHED:
                return array($state, trim($match));

            case DOKU_LEXER_EXIT:
                return array($state, '');
        }

        return array();
    }

    function render($mode, Doku_Renderer $renderer, $data) {
        if($mode != 'xhtml') {
            return false;
        }

        list($state, $match) = $data;
        switch ($state) {
            case DOKU_LEXER_ENTER:
                $this->currentVariableName = $match;
                break;

            // Ordinary text
            case DOKU_LEXER_UNMATCHED:
                global $ID;
                p_set_metadata($ID, array('customfield-' . $this->currentVariableName => $match));
                break;

            case DOKU_LEXER_EXIT:
                break;
        }

        return true;
    }
}
