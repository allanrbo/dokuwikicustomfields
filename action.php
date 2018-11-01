<?php

function getCustomField($fieldName) {
    global $ID;
    return p_get_metadata($ID, 'customfield-' . $fieldName);
}

function getCustomFieldImg($fieldName) {
    $s = getCustomField($fieldName);
    $s = str_replace('{{:', '', $s);
    $s = str_replace('}}', '', $s);
    $s = preg_replace('/\?.*/', '', $s);
    $s = str_replace(':', '/', $s);
    $s = DOKU_BASE . '_media/' . $s;
    return $s;
}
