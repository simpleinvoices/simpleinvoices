<?php

define('CSS_PROPERTY_LEVEL_CURRENT',0);
define('CSS_PROPERTY_LEVEL_PARENT',1);

define('CSS_PROPERTY_INHERIT', null);

define('CSS_BACKGROUND',          1);
define('CSS_BACKGROUND_COLOR',    2);
define('CSS_BACKGROUND_IMAGE',    3);
define('CSS_BORDER',              4);
define('CSS_BORDER_BOTTOM',       5);
define('CSS_BORDER_BOTTOM_COLOR', 6);
define('CSS_BORDER_BOTTOM_STYLE', 7);
define('CSS_BORDER_BOTTOM_WIDTH', 8);
define('CSS_BORDER_COLLAPSE',     9);
define('CSS_BORDER_COLOR',       10);
define('CSS_BORDER_LEFT',        11);
define('CSS_BORDER_LEFT_COLOR',  12);
define('CSS_BORDER_LEFT_STYLE',  13);
define('CSS_BORDER_LEFT_WIDTH',  14);
define('CSS_BORDER_RIGHT',       15);
define('CSS_BORDER_RIGHT_COLOR', 16);
define('CSS_BORDER_RIGHT_STYLE', 17);
define('CSS_BORDER_RIGHT_WIDTH', 18);
define('CSS_BORDER_STYLE',       19);
define('CSS_BORDER_TOP',         20);
define('CSS_BORDER_TOP_COLOR',   21);
define('CSS_BORDER_TOP_STYLE',   22);
define('CSS_BORDER_TOP_WIDTH',   23);
define('CSS_BORDER_WIDTH',       24);
define('CSS_BOTTOM',             25);
define('CSS_CLEAR',              26);
define('CSS_COLOR',              27);
define('CSS_CONTENT',            28);
define('CSS_DISPLAY',            29);
define('CSS_FLOAT',              30);
define('CSS_FONT',               31);
define('CSS_FONT_FAMILY',        32);
define('CSS_FONT_SIZE',          33);
define('CSS_FONT_STYLE',         34);
define('CSS_FONT_WEIGHT',        35);
define('CSS_HEIGHT',             36);
define('CSS_LEFT',               37);
define('CSS_LETTER_SPACING',     38);
define('CSS_LINE_HEIGHT',        39);
define('CSS_LIST_STYLE',         40);
define('CSS_MARGIN',             41);
define('CSS_MARGIN_BOTTOM',      42);
define('CSS_MARGIN_LEFT',        43);
define('CSS_MARGIN_RIGHT',       44);
define('CSS_MARGIN_TOP',         45);
define('CSS_MIN_HEIGHT',         46);
define('CSS_OVERFLOW',           47);
define('CSS_PADDING',            48);
define('CSS_PADDING_BOTTOM',     49);
define('CSS_PADDING_LEFT',       50);
define('CSS_PADDING_RIGHT',      51);
define('CSS_PADDING_TOP',        52);
define('CSS_PAGE_BREAK_AFTER',   53);
define('CSS_POSITION',           54);
define('CSS_RIGHT',              55);
define('CSS_TEXT_ALIGN',         56);
define('CSS_TEXT_DECORATION',    57);
define('CSS_TEXT_INDENT',        58);
define('CSS_TEXT_TRANSFORM',     59);
define('CSS_TOP',                60);
define('CSS_VERTICAL_ALIGN',     61);
define('CSS_VISIBILITY',         62);
define('CSS_WIDTH',              63);
define('CSS_WHITE_SPACE',        64);
define('CSS_Z_INDEX',            65);

define('CSS_BACKGROUND_POSITION',100);
define('CSS_BACKGROUND_REPEAT',  101);
define('CSS_MAX_HEIGHT',         102);
define('CSS_LIST_STYLE_IMAGE',   103);
define('CSS_LIST_STYLE_POSITION',104);
define('CSS_LIST_STYLE_TYPE',    105);
define('CSS_WORD_SPACING',       106);
define('CSS_MIN_WIDTH',          107);
define('CSS_PAGE_BREAK_INSIDE',  108);
define('CSS_PAGE_BREAK_BEFORE',  109);
define('CSS_ORPHANS',            110);
define('CSS_WIDOWS',             111);
define('CSS_TABLE_LAYOUT',       112);
define('CSS_DIRECTION',          113);
define('CSS_PAGE',               114);
define('CSS_BACKGROUND_ATTACHMENT', 115);
define('CSS_SIZE', 116);

define('CSS_HTML2PS_ALIGN',            900);
define('CSS_HTML2PS_CELLPADDING',      901);
define('CSS_HTML2PS_CELLSPACING',      902);
define('CSS_HTML2PS_FORM_ACTION',      903);
define('CSS_HTML2PS_FORM_RADIOGROUP',  904);
define('CSS_HTML2PS_LOCALALIGN',       905);
define('CSS_HTML2PS_LINK_DESTINATION', 906);
define('CSS_HTML2PS_LINK_TARGET',      907);
define('CSS_HTML2PS_LIST_COUNTER',     908);
define('CSS_HTML2PS_NOWRAP',           909);

define('CSS_HTML2PS_TABLE_BORDER', 910);
define('CSS_HTML2PS_HTML_CONTENT', 911);
define('CSS_HTML2PS_PSEUDOELEMENTS', 912);
define('CSS_HTML2PS_COMPOSITE_WIDTH', 913);
define('CSS_HTML2PS_PIXELS', 914);

// Selectors

define('CSS_PAGE_SELECTOR_ALL',   0);
define('CSS_PAGE_SELECTOR_FIRST', 1);
define('CSS_PAGE_SELECTOR_LEFT',  2);
define('CSS_PAGE_SELECTOR_RIGHT', 3);
define('CSS_PAGE_SELECTOR_NAMED', 4);

define('CSS_MARGIN_BOX_SELECTOR_TOP', 0);
define('CSS_MARGIN_BOX_SELECTOR_TOP_LEFT_CORNER', 1);
define('CSS_MARGIN_BOX_SELECTOR_TOP_LEFT', 2);
define('CSS_MARGIN_BOX_SELECTOR_TOP_CENTER', 3);
define('CSS_MARGIN_BOX_SELECTOR_TOP_RIGHT', 4);
define('CSS_MARGIN_BOX_SELECTOR_TOP_RIGHT_CORNER', 5);
define('CSS_MARGIN_BOX_SELECTOR_BOTTOM', 6);
define('CSS_MARGIN_BOX_SELECTOR_BOTTOM_LEFT_CORNER', 7);
define('CSS_MARGIN_BOX_SELECTOR_BOTTOM_LEFT', 8);
define('CSS_MARGIN_BOX_SELECTOR_BOTTOM_CENTER', 9);
define('CSS_MARGIN_BOX_SELECTOR_BOTTOM_RIGHT', 10);
define('CSS_MARGIN_BOX_SELECTOR_BOTTOM_RIGHT_CORNER', 11);
define('CSS_MARGIN_BOX_SELECTOR_LEFT_TOP', 12);
define('CSS_MARGIN_BOX_SELECTOR_LEFT_MIDDLE', 13);
define('CSS_MARGIN_BOX_SELECTOR_LEFT_BOTTOM', 14);
define('CSS_MARGIN_BOX_SELECTOR_RIGHT_TOP', 15);
define('CSS_MARGIN_BOX_SELECTOR_RIGHT_MIDDLE', 16);
define('CSS_MARGIN_BOX_SELECTOR_RIGHT_BOTTOM', 17);

// 'border-style' values

define('BS_NONE',   1);
define('BS_SOLID',  2);
define('BS_INSET',  3);
define('BS_GROOVE', 4);
define('BS_RIDGE',  5);
define('BS_OUTSET', 6);
define('BS_DASHED', 7);
define('BS_DOTTED', 8);
define('BS_DOUBLE', 9);

// Unit types

define('UNIT_NONE', 0);

// relative units

define('UNIT_PX', 2);
define('UNIT_EM', 5);
define('UNIT_EX', 6);

// absolute length units

define('UNIT_IN', 7);
define('UNIT_CM', 4);
define('UNIT_MM', 3);
define('UNIT_PT', 1);
define('UNIT_PC', 8);

// Cache constants

define('CACHE_MIN_WIDTH',0);
define('CACHE_MAX_WIDTH',1);
define('CACHE_TYPEFACE', 2);
define('CACHE_MIN_WIDTH_NATURAL', 3);

// CSS regular expressions

define('CSS_NL_REGEXP', '(?:\n|\r\n|\r|\f)');
define('CSS_UNICODE_REGEXP', '\\[0-9a-f]{1,6}(?:\r\n|[ \n\r\t\f])?');
define('CSS_NONASCII_REGEXP', '[^\0-\177]');
define('CSS_ESCAPE_REGEXP', CSS_UNICODE_REGEXP.'|\\[^\n\r\f0-9a-f]');
define('CSS_NMSTART_REGEXP', '(?:[_a-z]|'.CSS_NONASCII_REGEXP.'|'.CSS_ESCAPE_REGEXP.')');
define('CSS_NMCHAR_REGEXP', '(?:[_a-z0-9-]|'.CSS_NONASCII_REGEXP.'|'.CSS_ESCAPE_REGEXP.')');
define('CSS_IDENT_REGEXP', '-?'.CSS_NMSTART_REGEXP.CSS_NMCHAR_REGEXP.'*');
define('CSS_FUNCTION_REGEXP', '(?:'.CSS_IDENT_REGEXP.'\()');
define('CSS_STRING1_REGEXP', '\"(?:[^\n\r\f\\"]|\\\\'.CSS_NL_REGEXP.'|'.CSS_ESCAPE_REGEXP.')*\"');
define('CSS_STRING2_REGEXP', '\\'."'".'(?:[^\n\r\f\\'."'".']|\\\\'.CSS_NL_REGEXP.'|'.CSS_ESCAPE_REGEXP.')*\\'."'");

?>