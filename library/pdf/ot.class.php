<?php

define('OT_CMAP_PLATFORM_WINDOWS', 3);
define('OT_CMAP_PLATFORM_WINDOWS_UNICODE', 1);

define('OT_PLATFORM_ID_MICROSOFT', 3);

define('OT_NAME_ID_SUBFAMILY_NAME', 2);
define('OT_NAME_ID_UNIQUE_ID', 3);
define('OT_NAME_ID_FULL_NAME', 4);
define('OT_NAME_ID_POSTSCRIPT_NAME', 6);

define('OT_CMAP_LANGUAGE_WINDOWS_ENGLISH_AMERICAN', 0x0409);

/**
 * This class allows parsing TrueType/OpenType font files
 */
class OpenTypeFile {
  var $_filehandle;
  var $_sfnt;

  function OpenTypeFile() {
    $this->_filehandle = null;
    $this->_sfnt = new OpenTypeFileSFNT();
  }
  
  function open($filename) {
    $this->_filehandle = fopen($filename, 'rb');
    $this->_read($this->_filehandle);
  }

  function close() {
    fclose($this->_filehandle);
  }

  function _delete() {
    $this->close();
    $this->_sfnt->_delete();
  }

  function getFileHandle() {
    return $this->_filehandle;
  }

  function &getTable($tag) {
    $table =& $this->_sfnt->_getTable($tag, $this->_filehandle, $this);   
    return $table;
  }

  function &_getCMAPSubtable($offset) {
    $table =& $this->_sfnt->_getCMAPSubtable($offset, $this->_filehandle, $this);
    return $table;
  }

  function _read($filehandle) {
    $this->_sfnt->_read($filehandle);
  }
}

/**
 * A key  characteristic of the  OpenType format is the  TrueType sfnt
 * "wrapper", which  provides organization for a  collection of tables
 * in a general and extensible manner.
 */
class OpenTypeFileSFNT {
  var $_offsetTable;
  var $_tableDirectory;

  var $_tables;

  function _delete() {
    foreach ($this->_tables as $key => $value) {
      $this->_tables[$key]->_delete();
      unset($this->_tables[$key]);
    };
    $this->_tables = array();
  }

  function OpenTypeFileSFNT() {
    $this->_offsetTable = new OpenTypeFileOffsetTable();
    $this->_tableDirectory = array();
  }

  function _read($filehandle) {
    $this->_offsetTable->_read($filehandle);

    for ($i=0; $i<$this->_offsetTable->_numTables; $i++) {
      $tableDirectory = new OpenTypeFileTableDirectory();
      $tableDirectory->_read($filehandle);
      $this->_tableDirectory[] = $tableDirectory;
    };
  }

  function &_getCMAPSubtable($offset, $filehandle, $file) {
    $dir = $this->_getDirectory('cmap');
    if (is_null($dir)) { $dummy = null; return $dummy; };
    
    /**
     * Store  current  file  position,  as _getCMAPSubtable  could  be
     * called from another file-related operation
     */
    $old_pos = ftell($filehandle);
    
    fseek($filehandle, $dir->_offset, SEEK_SET);
    fseek($filehandle, $offset, SEEK_CUR);
    $subtable = new OpenTypeFileCMAPSubtable();
    $subtable->_read($filehandle);
    
    /**
     * Restore current file position
     */
    fseek($filehandle, $old_pos, SEEK_SET);
    
    return $subtable;
  }

  function &_getTable($tag, $filehandle, $file) {
    if (!isset($this->_tables[$tag])) {
      $table = $this->_createTableByTag($tag);
      if (is_null($table)) { $dummy = null; return $dummy; };
      $table->setFontFile($file);

      $dir = $this->_getDirectory($tag);
      if (is_null($dir)) { $dummy = null; return $dummy; };

      /**
       * Store  current file  position, as  _getTable could  be called
       * from another _getTable
       */
      $old_pos = ftell($filehandle);

      fseek($filehandle, $dir->_offset, SEEK_SET);
      $table->_read($filehandle);

      /**
       * Restore current file position
       */
      fseek($filehandle, $old_pos, SEEK_SET);

      $this->_tables[$tag] =& $table;
    };

    return $this->_tables[$tag];
  }

  function _getDirectory($tag) {
    foreach ($this->_tableDirectory as $directoryEntry) {
      if ($directoryEntry->_tag == $tag) {
        return $directoryEntry;
      };
    };

    return null;
  }

  function _createTableByTag($tag) {
    switch ($tag) {
    case 'hhea':
      return new OpenTypeFileHHEA();
    case 'maxp':
      return new OpenTypeFileMAXP();
    case 'cmap':
      return new OpenTypeFileCMAP();
    case 'hmtx':
      return new OpenTypeFileHMTX();
    case 'post':
      return new OpenTypeFilePOST();
    case 'head':
      return new OpenTypeFileHEAD();
    case 'name':
      return new OpenTypeFileNAME();
    default:
      return null;
    }
  }
}

/**
 * The OpenType font with the Offset Table. If the font file contains only one font, the Offset Table will begin at byte 0 of the file. If the font file is a TrueType collection, the beginning point of the Offset Table for each font is indicated in the TTCHeader.
 * 
 * Offset Table Type 	Name 	Description
 * Fixed 	sfnt version 	0x00010000 for version 1.0.
 * USHORT 	numTables 	Number of tables.
 * USHORT 	searchRange 	(Maximum power of 2 <= numTables) x 16.
 * USHORT 	entrySelector 	Log2(maximum power of 2 <= numTables).
 * USHORT 	rangeShift 	NumTables x 16-searchRange.
 *
 * OpenType fonts that contain  TrueType outlines should use the value
 * of 1.0  for the  sfnt version. OpenType  fonts containing  CFF data
 * should use the tag 'OTTO' as the sfnt version number.
 *
 * NOTE: The Apple specification  for TrueType fonts allows for 'true'
 * and 'typ1' for sfnt version.  These version tags should not be used
 * for fonts which contain OpenType tables.
 */
class OpenTypeFileOffsetTable {
  var $_numTables;
  var $_searchRange;
  var $_entrySelector;
  var $_rangeShift;

  function OpenTypeFileOffsetTable() {
    $this->_numTables     = 0;
    $this->_searchRange   = 0;
    $this->_entrySelector = 0;
    $this->_rangeShift    = 0;
  }

  function _read($filehandle) {
    $content = fread($filehandle, 4+4*2);    

    $unpacked = unpack("Nversion/nnumTables/nsearchRange/nentrySelector/nrangeShift", $content);

    $fixed                = $unpacked['version'];
    $this->_numTables     = $unpacked['numTables'];
    $this->_searchRange   = $unpacked['searchRange'];
    $this->_entrySelector = $unpacked['entrySelector'];
    $this->_rangeShift    = $unpacked['rangeShift'];
  }
}

/**
 * The  Offset Table is  followed immediately  by the  Table Directory
 * entries. Entries in the Table Directory must be sorted in ascending
 * order by  tag. Offset  values in the  Table Directory  are measured
 * from the start of the font file.
 *
 * Table Directory Type 	Name 	Description
 * ULONG 	tag 	4 -byte identifier.
 * ULONG 	checkSum 	CheckSum for this table.
 * ULONG 	offset 	Offset from beginning of TrueType font file.
 * ULONG 	length 	Length of this table.
 *
 * The Table Directory  makes it possible for a  given font to contain
 * only  those tables  it  actually needs.  As  a result  there is  no
 * standard value for numTables.
 *
 * Tags are the  names given to tables in the  OpenType font file. All
 * tag names  consist of  four characters. Names  with less  than four
 * letters  are   allowed  if  followed  by   the  necessary  trailing
 * spaces. All  tag names  defined within a  font (e.g.,  table names,
 * feature tags, language tags) must be built from printing characters
 * represented by ASCII values 32-126.
 */
class OpenTypeFileTableDirectory {
  var $_tag;
  var $_checkSum;
  var $_offset;
  var $_length;

  function OpenTypeFileTableDirectory() {
    $this->_tag      = null;
    $this->_checkSum = 0;
    $this->_offset   = 0;
    $this->_length   = 0;
  }

  function _read($filehandle) {
    $content = fread($filehandle, 4*4);

    $unpacked = unpack("c4tag/NcheckSum/Noffset/Nlength", $content);

    $this->_tag      = chr($unpacked['tag1']).chr($unpacked['tag2']).chr($unpacked['tag3']).chr($unpacked['tag4']);
    $this->_checkSum = $unpacked['checkSum'];
    $this->_offset   = $unpacked['offset'];
    $this->_length   = $unpacked['length'];
  }
}

/* -------------- */

class OpenTypeFileTable {
  var $_fontFile;

  function _delete() {
  }

  function OpenTypeFileTable() {
    $this->_fontFile = null;
  }

  function setFontFile(&$fontFile) {
    $this->_fontFile =& $fontFile;
  }

  function &getFontFile() {
    return $this->_fontFile;
  }

  function _fixFWord($value) {
    if ($value > 65536/2) {
      return $value - 65536;
    } else {
      return $value;
    };
  }

  function _fixShort($value) {
    if ($value > 65536/2) {
      return $value - 65536;
    } else {
      return $value;
    };
  }
}

class OpenTypeFilePOST extends OpenTypeFileTable {
  var $_version;
  var $_italicAngle;
  var $_underlinePosition;
  var $_underlineThickness;
  var $_isFixedPitch;
  var $_minMemType42;
  var $_maxMemType42;
  var $_minMemType1;
  var $_maxMemType1;

  function OpenTypeFilePOST() {
    $this->OpenTypeFileTable();
  }

  function _read($filehandle) {
    $content  = fread($filehandle, 2*2 + 7*4);
    $unpacked = unpack("Nversion/NitalicAngle/nunderlinePosition/nunderlineThickness/NisFixedPitch/NminMemType42/NmaxMemType42/NminMemType1/NmaxMemType1", $content);
    $this->_version            = $unpacked['version'];
    $this->_italicAngle        = $unpacked['italicAngle'];
    $this->_underlinePosition  = $this->_fixFWord($unpacked['underlinePosition']);
    $this->_underlineThickness = $this->_fixFWord($unpacked['underlineThickness']);
    $this->_isFixedPitch       = $unpacked['isFixedPitch'];
    $this->_minMemType42       = $unpacked['minMemType42'];
    $this->_maxMemType42       = $unpacked['maxMemType42'];
    $this->_minMemType1        = $unpacked['minMemType1'];
    $this->_maxMemType1        = $unpacked['maxMemType1'];
  }
}

class OpenTypeFileNAME extends OpenTypeFileTable {
  var $_format;
  var $_count;
  var $_stringOffset;
  var $_nameRecord;

  function OpenTypeFileNAME() {
    $this->OpenTypeFileTable();
    $this->_nameRecord = array();
  }

  function _read($filehandle) {
    $content  = fread($filehandle, 2*3);    
    $unpacked = unpack("nformat/ncount/nstringOffset", $content);

    $this->_format       = $unpacked['format'];
    $this->_count        = $unpacked['count'];
    $this->_stringOffset = $unpacked['stringOffset'];

    $baseOffset = ftell($filehandle) + OpenTypeFileNAMERecord::sizeof()*$this->_count;

    for ($i=0; $i<$this->_count; $i++) {
      $record =& new OpenTypeFileNAMERecord();
      $record->setBaseOffset($baseOffset);
      $record->setFontFile($this->getFontFile());
      $record->_read($filehandle);
      $this->_nameRecord[] =& $record;
    };
  }

  /**
   * Note that this function can perform "wildcard" lookups when one or more 
   * parameters is set to null value; in this case the first encountered name 
   * will be returned
   *
   * @return String corresponding name content or null is this name is
   * not defined in the font file
   */
  function lookup($platformId, $encodingId, $languageId, $nameId) {
    $size = count($this->_nameRecord);

    for ($i=0; $i<$size; $i++) {
      if ($this->_nameRecord[$i]->match($platformId, $encodingId, $languageId, $nameId)) {
        return $this->_nameRecord[$i]->getName();
      };
    }

    return null;
  }
}

class OpenTypeFileNAMERecord extends OpenTypeFileTable {
  var $_platformId;
  var $_encodingId;
  var $_languageId;
  var $_nameId;
  var $_length;
  var $_offset;

  var $_content;
  var $_baseOffset;

  function OpenTypeFileNAMERecord() {
    $this->OpenTypeFileTable();
    $this->_content = null;
  }

  function sizeof() {
    return 6*2;
  }

  function setBaseOffset($offset) {
    $this->_baseOffset = $offset;
  }

  function match($platformId, $encodingId, $languageId, $nameId) {
    return
      (is_null($platformId) || $platformId == $this->_platformId) &&
      (is_null($encodingId) || $encodingId == $this->_encodingId) &&
      (is_null($languageId) || $languageId == $this->_languageId) &&
      (is_null($nameId)     || $nameId     == $this->_nameId);
  }

  function getBaseOffset() {
    return $this->_baseOffset;
  }

  function getName() {
    if (is_null($this->_content)) {
      $file =& $this->getFontFile();
      $filehandle = $file->getFileHandle();
      $old_offset = ftell($filehandle);

      fseek($filehandle, $this->getBaseOffset() + $this->_offset, SEEK_SET);
      $this->_content = fread($filehandle, $this->_length);
    
      fseek($filehandle, $old_offset, SEEK_SET);
    };

    return $this->_content;
  }

  function _read($filehandle) {
    $content = fread($filehandle, 6*2);

    $unpacked = unpack("nplatformId/nencodingId/nlanguageId/nnameId/nlength/noffset", $content);

    $this->_platformId    = $unpacked['platformId'];
    $this->_encodingId    = $unpacked['encodingId'];
    $this->_languageId    = $unpacked['languageId'];
    $this->_nameId        = $unpacked['nameId'];
    $this->_length        = $unpacked['length'];
    $this->_offset        = $unpacked['offset'];
  }
}

/**
 * This table  gives global information  about the font.  The bounding
 * box  values  should  be   computed  using  only  glyphs  that  have
 * contours.  Glyphs  with  no  contours  should be  ignored  for  the
 * purposes of these calculations.
 *
 * Type 	Name 	Description
 * Fixed 	Table version number 	0x00010000 for version 1.0.
 * Fixed 	fontRevision 	Set by font manufacturer.
 * ULONG 	checkSumAdjustment 	To compute: set it to 0, sum the entire font as ULONG, then store 0xB1B0AFBA - sum.
 * ULONG 	magicNumber 	Set to 0x5F0F3CF5.
 * USHORT 	flags 	Bit 0: Baseline for font at y=0;
 * Bit 1: Left sidebearing point at x=0;
 * Bit 2: Instructions may depend on point size;
 * Bit 3: Force ppem to integer values for all internal scaler math; may use fractional ppem sizes if this bit is clear;
 * Bit 4: Instructions may alter advance width (the advance widths might not scale linearly);
 * Bits 5-10: These should be set according to Apple's specification . However, they are not implemented in OpenType.
 * Bit 11: Font data is 'lossless,' as a result of having been compressed and decompressed with the Agfa MicroType Express engine.
 * Bit 12: Font converted (produce compatible metrics)
 * Bit 13: Font optimised for ClearType
 * Bit 14: Reserved, set to 0
 * Bit 15: Reserved, set to 0
 * USHORT 	unitsPerEm 	Valid range is from 16 to 16384. This value should be a power of 2 for fonts that have TrueType outlines.
 * LONGDATETIME 	created 	Number of seconds since 12:00 midnight, January 1, 1904. 64-bit integer
 * LONGDATETIME 	modified 	Number of seconds since 12:00 midnight, January 1, 1904. 64-bit integer
 * SHORT 	xMin 	For all glyph bounding boxes.
 * SHORT 	yMin 	For all glyph bounding boxes.
 * SHORT 	xMax 	For all glyph bounding boxes.
 * SHORT 	yMax 	For all glyph bounding boxes.
 * USHORT 	macStyle 	Bit 0: Bold (if set to 1);
 * Bit 1: Italic (if set to 1)
 * Bit 2: Underline (if set to 1)
 * Bit 3: Outline (if set to 1)
 * Bit 4: Shadow (if set to 1)
 * Bit 5: Condensed (if set to 1)
 * Bit 6: Extended (if set to 1)
 * Bits 7-15: Reserved (set to 0).
 * USHORT 	lowestRecPPEM 	Smallest readable size in pixels.
 * SHORT 	fontDirectionHint 	0: Fully mixed directional glyphs;
 * 1: Only strongly left to right;
 * 2: Like 1 but also contains neutrals;
 * -1: Only strongly right to left;
 * -2: Like -1 but also contains neutrals. 1
 * SHORT 	indexToLocFormat 	0 for short offsets, 1 for long.
 * SHORT 	glyphDataFormat 	0 for current format.
 */
class OpenTypeFileHEAD extends OpenTypeFileTable {
  var $_version;
  var $_fontRevision;
  var $_checkSumAdjustment;
  var $_magicNumber;
  var $_flags;
  var $_unitsPerEm;
  var $_created;
  var $_modified;
  var $_xMin;
  var $_yMin;
  var $_xMax;
  var $_yMax;
  var $_macStyle;
  var $_lowestRecPPEM;
  var $_fontDirectionHint;
  var $_indexToLocFormat;
  var $_glyphDataFormat;

  function OpenTypeFileHEAD() {
    $this->OpenTypeFileTable();
  }

  function _read($filehandle) {
    $content = fread($filehandle, 4*4 + 11*2 + 2*8);
    
    $unpacked = unpack("Nversion/NfontRevision/NcheckSumAdjustment/NmagicNumber/nflags/nunitsPerEm/N2created/N2modified/nxMin/nyMin/nxMax/nyMax/nmacStyle/nlowestRecPPEM/nfontDirectionHint/nindexToLocFormat/nglyphDataFormat", $content);
    $this->_version            = $unpacked['version'];
    $this->_fontRevision       = $unpacked['fontRevision'];
    $this->_checkSumAdjustment = $unpacked['checkSumAdjustment'];
    $this->_magicNumber        = $unpacked['magicNumber'];
    $this->_flags              = $unpacked['flags'];
    $this->_unitsPerEm         = $unpacked['unitsPerEm'];
    $this->_created            = $unpacked['created1']  << 32 | $unpacked['created2'];
    $this->_modified           = $unpacked['modified1'] << 32 | $unpacked['modified2'];
    $this->_xMin               = $this->_fixShort($unpacked['xMin']);
    $this->_yMin               = $this->_fixShort($unpacked['yMin']);
    $this->_xMax               = $this->_fixShort($unpacked['xMax']);
    $this->_yMax               = $this->_fixShort($unpacked['yMax']);
    $this->_macStyle           = $unpacked['macStyle'];
    $this->_lowestRecPPEM      = $unpacked['lowestRecPPEM'];
    $this->_fontDirectionHint  = $this->_fixShort($unpacked['fontDirectionHint']);
    $this->_indexToLocFormat   = $this->_fixShort($unpacked['indexToLocFormat']);
    $this->_glyphDataFormat    = $this->_fixShort($unpacked['glyphDataFormat']);
  }
}

class OpenTypeFileCMAP extends OpenTypeFileTable {
  var $_header;
  var $_encodings;
  var $_subtables;

  function OpenTypeFileCMAP() {
    $this->OpenTypeFileTable();
    $this->_header = new OpenTypeFileCMAPHeader();
    $this->_encodings = array();
    $this->_subtables = array();
  }

  function _read($filehandle) {
    $this->_header->_read($filehandle);

    for ($i=0; $i<$this->_header->_numTables; $i++) {
      $encoding = new OpenTypeFileCMAPEncoding();
      $encoding->_read($filehandle);
      $this->_encodings[] =& $encoding;
    };
  }

  /**
   * It is assumed that current  file position is set to the beginning
   * of CMAP table
   */
  function _getSubtable($filehandle, $offset) {
    fseek($filehandle, $offset, SEEK_CUR);

    $subtable = new OpenTypeFileCMAPSubtable();
    $subtable->_read($filehandle);

    return $subtable;
  }

  function &findSubtable($platformId, $encodingId) {
    $file = $this->getFontFile();

    $index = 0;
    foreach ($this->_encodings as $encoding) {
      if ($encoding->_platformId == $platformId &&
          $encoding->_encodingId == $encodingId) {
        return $this->getSubtable($index);
      };
    };

    $dummy = null; return $dummy;
  }

  function &getSubtable($index) {
    if (!isset($this->_subtables[$index])) {
      $file =& $this->getFontFile(); 
      $subtable =& $file->_getCMAPSubtable($this->_encodings[$index]->_offset);
      $this->_subtables[$index] =& $subtable;
      return $subtable;
    } else {
      return $this->_subtables[$index];
    };
  }
}

/**
 * TODO: support for CMAP subtable formats other than 4
 */
class OpenTypeFileCMAPSubtable {
  var $_format;
  var $_content;

  function OpenTypeFileCMAPSubtable() {
    $this->_content = null;
  }

  function lookup($unicode) { 
    return $this->_content->lookup($unicode); 
  }

  function _read($filehandle) {
    $content = fread($filehandle, 2);
    
    $unpacked = unpack("nformat", $content);
    $this->_format = $unpacked['format'];

    switch ($this->_format) {
    case 4:
      $this->_content = new OpenTypeFileCMAPSubtable4();
      $this->_content->_read($filehandle);
      return;
        
    default:
      die(sprintf("Unsupported CMAP subtable format: %i", $this->_format));
    }
  }
}

class OpenTypeFileCMAPSubtable4 extends OpenTypeFileTable {
  var $_length;
  var $_language;
  var $_segCountX2;
  var $_searchRange;
  var $_entrySelector;
  var $_rangeShift;
  var $_endCount;
  var $_startCount;
  var $_idDelta;
  var $_idRangeOffset;
  var $_glyphIdArray;

  function OpenTypeFileCMAPSubtable4() {
    $this->_endCount      = array();
    $this->_startCount    = array();
    $this->_idDelta       = array();
    $this->_idRangeOffset = array();
    $this->_glyphIdArray  = array();
  }

  function lookup($unicode) {
    $index = $this->_lookupSegment($unicode);
    if (is_null($index)) { return null; };

    if ($this->_idRangeOffset[$index] != 0) {
      /**
       * If  the idRangeOffset  value for  the segment  is not  0, the
       * mapping  of  character  codes  relies  on  glyphIdArray.  The
       * character  code  offset  from   startCode  is  added  to  the
       * idRangeOffset value. This  sum is used as an  offset from the
       * current location within idRangeOffset itself to index out the
       * correct glyphIdArray value. This obscure indexing trick works
       * because glyphIdArray immediately follows idRangeOffset in the
       * font file. The C expression that yields the glyph index is:
       *
       * *(idRangeOffset[i]/2 + (c - startCount[i]) + &idRangeOffset[i])
       *
       * The value c  is the character code in question,  and i is the
       * segment index in which c  appears. If the value obtained from
       * the   indexing   operation   is   not  0   (which   indicates
       * missingGlyph),  idDelta[i] is added  to it  to get  the glyph
       * index. The idDelta arithmetic is modulo 65536.
       */
      $value = $this->_glyphIdArray[$unicode - $this->_startCount[$index]];
      return ($value + $this->_idDelta[$index]) % 65536;

    } else {
      /**
       * If  the  idRangeOffset  is  0,  the idDelta  value  is  added
       * directly to  the character code offset (i.e.  idDelta[i] + c)
       * to  get the  corresponding  glyph index.  Again, the  idDelta
       * arithmetic is modulo 65536.
       */
      return ($this->_idDelta[$index] + $unicode) % 65536;
    };
  }

  /**
   * The segments  are sorted in  order of increasing  endCode values,
   * and the segment values are specified in four parallel arrays. You
   * search for the first endCode that is greater than or equal to the
   * character code you want to map.
   */
  function _lookupSegment($unicode) {
    for ($i=0; $i<$this->_segCountX2/2; $i++) {
      if ($unicode <= $this->_endCount[$i]) {       
        /**
         * If the corresponding startCode is less than or equal to the
         * character code, then you  use the corresponding idDelta and
         * idRangeOffset to  map the character  code to a  glyph index
         * (otherwise, the missingGlyph is returned). 
         */
        if ($this->_startCount[$i] <= $unicode) {
          return $i;
        } else {
          return null;
        };
      };
    };
    return null;
  }

  function _read($filehandle) {
    $content = fread($filehandle, 6*2);
    $unpacked = unpack("nlength/nlanguage/nsegCountX2/nsearchRange/nentrySelector/nrangeShift", $content);
    $this->_length        = $unpacked['length'];
    $this->_language      = $unpacked['language'];
    $this->_segCountX2    = $unpacked['segCountX2'];
    $this->_searchRange   = $unpacked['searchRange'];
    $this->_entrySelector = $unpacked['entrySelector'];
    $this->_rangeShift    = $unpacked['rangeShift'];

    for ($i=0; $i<floor($this->_segCountX2/2); $i++) {
      $content = fread($filehandle, 2);
      $unpacked = unpack("nendCount", $content);
      $this->_endCount[] = $unpacked['endCount'];
    };
    
    // Skip 'reservedPad' field
    $content = fread($filehandle, 2);
    
    for ($i=0; $i<$this->_segCountX2/2; $i++) {
      $content = fread($filehandle, 2);
      $unpacked = unpack("nstartCount", $content);
      $this->_startCount[] = $unpacked['startCount'];
    };

    for ($i=0; $i<$this->_segCountX2/2; $i++) {
      $content = fread($filehandle, 2);
      $unpacked = unpack("nidDelta", $content);
      $this->_idDelta[] = $this->_fixShort($unpacked['idDelta']);
    };

    for ($i=0; $i<$this->_segCountX2/2; $i++) {
      $content = fread($filehandle, 2);
      $unpacked = unpack("nidRangeOffset", $content);
      $this->_idRangeOffset[] = $unpacked['idRangeOffset'];
    };

    for ($i=0; $i<$this->_length - 2*12; $i+=2) {
      $content = fread($filehandle, 2);
      $unpacked = unpack("nglyphId", $content);
      $this->_glyphIdArray[] = $unpacked['glyphId'];
    };
  }
}

class OpenTypeFileCMAPEncoding {
  var $_platformId;
  var $_encodingId;
  var $_offset;

  function _read($filehandle) {
    $content = fread($filehandle, 2*2+4);
    
    $unpacked = unpack("nplatformId/nencodingId/Noffset", $content);
    $this->_platformId = $unpacked['platformId'];
    $this->_encodingId = $unpacked['encodingId'];
    $this->_offset     = $unpacked['offset'];
  }
}

class OpenTypeFileCMAPHeader {
  var $_version;
  var $_numTables;

  function _read($filehandle) {
    $content = fread($filehandle, 2*2);

    $unpacked = unpack("nversion/nnumTables", $content);
    $this->_version   = $unpacked['version'];
    $this->_numTables = $unpacked['numTables'];
  }
}

// @TODO: v 1.0 support
class OpenTypeFileMAXP extends OpenTypeFileTable {
  var $_numGlyphs;

  function OpenTypeFileMAXP() {
    $this->OpenTypeFileTable();
  }

  function _read($filehandle) {
    $content = fread($filehandle, 4+2*1);
    
    $unpacked = unpack("Nversion/nnumGlyphs", $content);

    $version          = $unpacked['version'];
    $this->_numGlyphs = $unpacked['numGlyphs'];
  }
}

class OpenTypeFileHHEA extends OpenTypeFileTable {
  var $_ascender;
  var $_descender;
  var $_lineGap;
  var $_advanceWidthMax;
  var $_minLeftSideBearing;
  var $_minRightSideBearing;
  var $_xMaxExtent;
  var $_caretSlopeRise;
  var $_caretSlopeRun;
  var $_caretOffset;
  var $_metricDataFormat;
  var $_numberOfHMetrics;

  function OpenTypeFileHHEA() {
    $this->OpenTypeFileTable();
  }

  function _read($filehandle) {
    $content = fread($filehandle, 4+16*2);

    $unpacked = unpack("Nversion/nascender/ndescender/nlineGap/nadvanceWidthMax/nminLeftSideBearing/".
                       "nminRightSideBearing/nxMaxExtent/ncaretSlopeRise/ncaretSlopeRun/ncaretOffset/n4reserved/".
                       "nmetricDataFormat/nnumberOfHMetrics", $content);

    $version                    = $unpacked['version'];
    $this->_ascender            = $this->_fixFWord($unpacked['ascender']);
    $this->_descender           = $this->_fixFWord($unpacked['descender']);
    $this->_lineGap             = $this->_fixFWord($unpacked['lineGap']);
    $this->_advanceWidthMax     = $unpacked['advanceWidthMax']; 
    $this->_minLeftSideBearing  = $this->_fixFWord($unpacked['minLeftSideBearing']);
    $this->_minRightSideBearing = $this->_fixFWord($unpacked['minRightSideBearing']);
    $this->_xMaxExtent          = $this->_fixFWord($unpacked['xMaxExtent']);
    $this->_caretSlopeRise      = $this->_fixShort($unpacked['caretSlopeRise']);
    $this->_caretSlopeRun       = $this->_fixShort($unpacked['caretSlopeRun']);
    $this->_caretOffset         = $this->_fixShort($unpacked['caretOffset']);
    $this->_metricDataFormat    = $this->_fixShort($unpacked['metricDataFormat']);
    $this->_numberOfHMetrics    = $unpacked['numberOfHMetrics'];
  }
}

class OpenTypeFileHMTX extends OpenTypeFileTable {
  var $_hMetrics;
  var $_leftSideBearing;

  function _delete() {
    unset($this->_hMetrics);
    unset($this->_leftSideBearing);
  }

  function OpenTypeFileHMTX() {
    $this->OpenTypeFileTable();

    $this->_hMetrics        = array();
    $this->_leftSideBearing = array();
  }

  function _read($filehandle) {
    $fontFile =& $this->getFontFile();
    $hhea =& $fontFile->getTable('hhea');
    $maxp =& $fontFile->getTable('maxp');

    for ($i=0; $i<$hhea->_numberOfHMetrics; $i++) {
      $content = fread($filehandle, 2*2);
      $unpacked = unpack("nadvanceWidth/nlsb", $content);
      $this->_hMetrics[] = array('advanceWidth' => $unpacked['advanceWidth'],
                                 'lsb'          => $this->_fixShort($unpacked['lsb']));
    };

    for ($i=0; $i<$maxp->_numGlyphs; $i++) {
      $content = fread($filehandle, 2);
      $unpacked = unpack("nitem", $content);
      $this->_leftSideBearing[] = $unpacked['item'];
    };
  }
}


?>