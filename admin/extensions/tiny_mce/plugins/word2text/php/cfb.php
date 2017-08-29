<?php 
// ������ WCBFF 
// ������ 0.2 
// �����: ������� ������ a.k.a Ramon 
// E-mail: alex@rembish.ru 
// Copyright 2009 

// ����, ��������-�������, ����� ���� ����� ��� ������ � WCBFF, ��� ����������������, ��� 
// Windows Compound Binary File Format. ����� ��� �����? �� ������ ����� 
// ������� �������� ����� "�������" ����� ��� .doc, .xls � .ppt. �������, ��������, ��� 
// ��� ��������! 
class cfb { 
    // � ��� ���������� ����� ��������� ���������� �����, ������� ����� ������������. 
    protected $data = ""; 

    // ������� FAT-������� (1 << 9 = 512), Mini FAT-������� (1 << 6 = 64) � ������������ 
    // ������ ������, ������� ����� ���� ������� � miniFAT'�. 
    protected $sectorShift = 9; 
    protected $miniSectorShift = 6; 
    protected $miniSectorCutoff = 4096; 

    // ������ ������������������ FAT-�������� � ������ "������" �������� ��������� ����� 
    protected $fatChains = array(); 
    protected $fatEntries = array(); 

    // ������ ������������������� Mini FAT-�������� � ���� Mini FAT ������ ����� 
    protected $miniFATChains = array(); 
    protected $miniFAT = ""; 

    // ������ (3 ��� 4), � ����� ������ ������ ����� (little-endian) 
    private $version = 3; 
    private $isLittleEndian = true; 

    // ���������� "������" � ������� �������� ������� "�����" � FAT'� 
    private $cDir = 0; 
    private $fDir = 0; 

    // ���������� FAT-�������� � ����� 
    private $cFAT = 0; 

    // ���������� miniFAT-�������� � ������� ������������������ miniFAT-�������� � ����� 
    private $cMiniFAT = 0; 
    private $fMiniFAT = 0; 

    // DIFAT: ���������� ������� �������� � �������� �� 110 ������� (������ 109 � ���������) 
    private $DIFAT = array(); 
    private $cDIFAT = 0; 
    private $fDIFAT = 0; 

    // ��������: ����� ������� � ������ ������ (�� 4 ����� ������) 
    const ENDOFCHAIN = 0xFFFFFFFE; 
    const FREESECT   = 0xFFFFFFFF; 

    // ������ ���� �� ���������� ���������� ������ 
    public function read($filename) { 
        $this->data = file_get_contents($filename); 
    } 

    public function parse() { 
        // ������ ��� ������, ��� ��������� - �� ����� �� ���� ����� ���� CFB? 
        // ��� ��� ��������� ������ 8 ���� � ��������� �� ������������ � ����� ���������: ������������ �  
        // ������� - ���������� �� �������������. 
        $abSig = strtoupper(bin2hex(substr($this->data, 0, 8))); 
        if ($abSig != "D0CF11E0A1B11AE1" && $abSig != "0E11FC0DD0CF11E0") { return false; } 

        // ����� ������ ��������� �����; 
        $this->readHeader(); 
        // ���������� ���������� DIFAT-�������, ���� ����� ����; 
        $this->readDIFAT(); 
        // ������ ������������������ FAT-�������� 
        $this->readFATChains(); 
        // ������ ������������������ MiniFAT-�������� 
        $this->readMiniFATChains(); 
        // �������� ��������� "����������" ������ ����� 
        $this->readDirectoryStructure(); 


        // �� � ������� ��������� ������� ��������� ��������� � �������� ��������� 
        // ������ ����� ����������� ������ �������������� � �����, ��� ������� ������, 
        // ��� �������� ������ �� miniFAT-�����, ������� �� ����������� � ��������������� 
        // ����������. 
        $reStreamID = $this->getStreamIdByName("Root Entry"); 
        if ($reStreamID === false) { return false; } 
        $this->miniFAT = $this->getStreamById($reStreamID, true); 

        // ������� �������� ��� ������ �� DIFAT-�������, ������ ��� � ��� ���� ����������� 
        // ������� FAT. 
        unset($this->DIFAT); 

        // ����� ���������� ���� �������� ����� �������� �������� � ����� �� "������������" 
        // �������� Microsoft: doc, xls ��� ppt. 
    } 

    // �������, ������� ������� (���� �������) ����� ������ (stream'�) � ��������� "����������" 
    // �� ��� �����. � ��������� ������ - false. 
    public function getStreamIdByName($name, $from = 0) { 
        for($i = $from; $i < count($this->fatEntries); $i++) { 
            if ($this->fatEntries[$i]["name"] == $name) 
                return $i; 
        } 
        return false; 
    } 
    // ������� �������� �� ���� ����� ������ ($id) �, � �������� ���������� ��� ��������� 
    // ���������, ������ ��������. ���������� �������� ���������� ������� ������. 
    public function getStreamById($id, $isRoot = false) { 
        $entry = $this->fatEntries[$id]; 
        // �������� ������ � ������� �������� �� ���������� "��������" �����. 
        $from = $entry["start"]; 
        $size = $entry["size"]; 

        // ������ �������� ��� - ���� ������ ������ 4096 ����, �� ��� ����� ������ ������ 
        // �� MiniFAT'�, ���� ������ ��� ����� ������ �� ������ FAT'�. ���������� RootEntry, 
        // ��� �������� �� ������ ��������� ���������� �� FAT'� - ���� ��� ��� ��� ���� 
        // �������� MiniFAT. 

        $stream = ""; 
        // ����, ����� ���� ������� �1 - ��������� ������ � �� ������ 
        if ($size < $this->miniSectorCutoff && !$isRoot) { 
            // �������� ������ ������� miniFAT - 64 ����� 
            $ssize = 1 << $this->miniSectorShift; 

            do { 
                // �������� �������� � miniFAT'� 
                $start = $from << $this->miniSectorShift; 
                // ������ miniFAT-������ 
                $stream .= substr($this->miniFAT, $start, $ssize); 
                // ������� ��������� ����� miniFAT'� � ������� ������������������� 
                $from = isset($this->miniFATChains[$from]) ? $this->miniFATChains[$from] : self::ENDOFCHAIN; 
                // ���� �� �������� �� ���� ����� ������������������. 
            } while ($from != self::ENDOFCHAIN); 
        } else { 
            // ������� �2 - ����� ������� - ������ �� FAT. 
            // ������� ������ ������� - 512 (��� 4096 ��� ����� ������) 
            $ssize = 1 << $this->sectorShift; 
             
            do { 
                // ������� �������� � ����� (��������, ��� ������� ����� ��������� �� 512 ����) 
                $start = ($from + 1) << $this->sectorShift; 
                // ������ ������ 
                $stream .= substr($this->data, $start, $ssize); 
                // ������� ��������� ������ � ������� FAT-������������������� 
                #if (!isset($this->fatChains[$from])) 
                #    $from = self::ENDOFCHAIN; 
                #elseif ($from != self::ENDOFCHAIN && $from != self::FREESECT) 
                #    $from = $this->fatChains[$from]; 
                $from = isset($this->fatChains[$from]) ? $this->fatChains[$from] : self::ENDOFCHAIN; 
                // ���� �� �������� �� ����� ������������������. 
            } while ($from != self::ENDOFCHAIN); 
        } 
        // ���������� ���������� ������ � ������ ��� �������. 
        return substr($stream, 0, $size); 
    } 

    // ������� ������ ������ � ������ ������ �� ��������� ����� 
    private function readHeader() { 
        // ��� ������ ������ ��� �������� ������ � ����� 
        $uByteOrder = strtoupper(bin2hex(substr($this->data, 0x1C, 2))); 
        // ��� � ��������� ��� ����� little-endian ������, �� �� ������ ������ �������� 
        $this->isLittleEndian = $uByteOrder == "FEFF"; 

        // ������ 3 ��� 4 (4�� �� ���� �� ��������, �� � ������������ ��� �������) 
        $this->version = $this->getShort(0x1A); 

        // �������� ��� FAT � miniFAT 
        $this->sectorShift = $this->getShort(0x1E); 
        $this->miniSectorShift = $this->getShort(0x20); 
        $this->miniSectorCutoff = $this->getLong(0x38); 

        // ���������� ��������� � ���������� ����� � �������� �� ������� �������� � ����� 
        if ($this->version == 4) 
            $this->cDir = $this->getLong(0x28); 
        $this->fDir = $this->getLong(0x30); 

        // ���������� FAT-�������� � ����� 
        $this->cFAT = $this->getLong(0x2C); 

        // ���������� � ������� ������� miniFAT-������� �������������������. 
        $this->cMiniFAT = $this->getLong(0x40); 
        $this->fMiniFAT = $this->getLong(0x3C); 

        // ��� ����� ������� FAT-�������� � ������� ����� �������. 
        $this->cDIFAT = $this->getLong(0x48); 
        $this->fDIFAT = $this->getLong(0x44); 
    } 

    // ����, DIFAT. DIFAT ���������� � ����� �������� ����� ����� 
    // �������� ������� FAT-��������. ��� ���� ������� �� �� ������ 
    // ��������� ���������� ������� � ������ "�����������������" 
    // ������ 
    private function readDIFAT() { 
        $this->DIFAT = array(); 
        // ������ 109 ������ �� ������� �������� ����� � ��������� ������ ����� 
        for ($i = 0; $i < 109; $i++) 
            $this->DIFAT[$i] = $this->getLong(0x4C + $i * 4); 

        // ��� �� �� �������, ���� �� ��� ���-������ ������ �� �������. � ��������� 
        // ������ (�� 8,5 ��) �� ��� (������� ������ 109 ������), � ������� - �� 
        // ������� ��������� � ��. 
        if ($this->fDIFAT != self::ENDOFCHAIN) { 
            // ������ ������� � ������� ������ ���� �������� ������ ������. 
            $size = 1 << $this->sectorShift; 
            $from = $this->fDIFAT; 
            $j = 0; 

            do { 
                // �������� ������� � ����� � ������ ��������� 
                $start = ($from + 1) << $this->sectorShift; 
                // ������ ������ �� ������� ������� 
                for ($i = 0; $i < ($size - 4); $i += 4) 
                    $this->DIFAT[] = $this->getLong($start + $i); 
                // ������� ��������� DIFAT-������ - ������ �� ���� 
                // �������� ��������� "������" � ������� DIFAT-������� 
                $from = $this->getLong($start + $i); 
                // ���� ������ ����������, �� ������� � ����. 
            } while ($from != self::ENDOFCHAIN && ++$j < $this->cDIFAT); 
        } 

        // ��� �������� ������� �������� �������������� ������. 
        while($this->DIFAT[count($this->DIFAT) - 1] == self::FREESECT) 
            array_pop($this->DIFAT); 
    } 
    // ���, DIFAT �� ��������� - ������ ����� ������ �� ������� FAT-�������� 
    // ���������� � �������� �������. ������� �������� �� ����� ������. 
    private function readFATChains() { 
        // ������ ������� 
        $size = 1 << $this->sectorShift; 
        $this->fatChains = array(); 

        // ������� ������ DIFAT. 
        for ($i = 0; $i < count($this->DIFAT); $i++) { 
            // ��� �� ������ �� ������ ��� ������ (� ������ ���������) 
            $from = ($this->DIFAT[$i] + 1) << $this->sectorShift; 
            // �������� ������� FAT: ������ ������� - ��� ������� ������, 
            // �������� �������� ������� - ������ ���������� �������� ��� 
            // ENDOFCHAIN - ���� ��� ��������� ������� �������. 
            for ($j = 0; $j < $size; $j += 4) 
                $this->fatChains[] = $this->getLong($from + $j); 
        } 
    } 
    // FAT-������� �� ���������, ������ ����� ��������� MiniFAT-������� 
    // ��������� �����. 
    private function readMiniFATChains() { 
        // ������ ������� 
        $size = 1 << $this->sectorShift; 
        $this->miniFATChains = array(); 

        // ���� ������ ������ � MiniFAT-��������� 
        $from = $this->fMiniFAT; 
        // ���� � ����� MiniFAT ������������, ��  
        while ($from != self::ENDOFCHAIN) { 
            // ������� �������� � ������� � MiniFat-�������� 
            $start = ($from + 1) << $this->sectorShift; 
            // ������ ������� �� �������� ������� 
            for ($i = 0; $i < $size; $i += 4) 
                $this->miniFATChains[] = $this->getLong($start + $i); 
            // � ���� ���� ������ �� �������� � FAT-�������, �� ��������� ������. 
            $from = isset($this->fatChains[$from]) ? $this->fatChains[$from] : self::ENDOFCHAIN; 
        } 
    } 

    // ����� ������ �������, ������� ������ ��������� "������" ������� ����� (�� �������� 
    // �� ��������). � ��� ��������� �������� ��� ������� �� ������� �����. 
    private function readDirectoryStructure() { 
        // ������� ������ ������ � "�������" �� 
        $from = $this->fDir; 
        // �������� ������ ������� 
        $size = 1 << $this->sectorShift; 
        $this->fatEntries = array(); 
        do { 
            // ������� ������ � ����� 
            $start = ($from + 1) << $this->sectorShift; 
            // ����� ������ �� ����������� �������. � ����� ������� ���������� �� 4 (��� 128 ��� ������ 4) 
            // ��������� � ��. ������ ��. 
            for ($i = 0; $i < $size; $i += 128) { 
                // �������� �������� ����� 
                $entry = substr($this->data, $start + $i, 128); 
                // � ������������ ���: 
                $this->fatEntries[] = array( 
                    // �������� ��� ��������� 
                    "name" => $this->utf16_to_ansi(substr($entry, 0, $this->getShort(0x40, $entry))), 
                    // ��� ��� - �����, ���������������� ������, ������ ������ � �.�. 
                    "type" => ord($entry[0x42]), 
                    // ��� ���� � Red-Black ������ 
                    "color" => ord($entry[0x43]), 
                    // ��� ����� ������ 
                    "left" => $this->getLong(0x44, $entry), 
                    // ��� ������ ������ 
                    "right" => $this->getLong(0x48, $entry), 
                    // ��� �������� ������� 
                    "child" => $this->getLong(0x4C, $entry), 
                    // �������� �� ����������� � FAT ��� miniFAT 
                    "start" => $this->getLong(0x74, $entry), 
                    // ������ ����������� 
                    "size" => $this->getSomeBytes($entry, 0x78, 8), 
                ); 
            } 

            // ����� ������� ��������� ������ � ���������� � ������� ���� 
            $from = isset($this->fatChains[$from]) ? $this->fatChains[$from] : self::ENDOFCHAIN; 
            // ���� ������� ����� ������� 
        } while ($from != self::ENDOFCHAIN); 

        // ������� �������� "������" ���������, ���� ������� ����. 
        while($this->fatEntries[count($this->fatEntries) - 1]["type"] == 0) 
            array_pop($this->fatEntries); 

        #dump($this->fatEntries, false); 
    } 

    // ��������������� ������� ��� ��������� ����������� ����� �������� ��������� � ��. 
    // ������, ��� ����� �������� � Unicode. 
    private function utf16_to_ansi($in) { 
        $out = ""; 
        for ($i = 0; $i < strlen($in); $i += 2) 
            $out .= chr($this->getShort($i, $in)); 
        return trim($out); 
    } 

    // ������� �������������� �� Unicode � UTF8, � �� ���-�� �� ���. 
    protected function unicode_to_utf8($in, $check = false) { 
        $out = ""; 
        if ($check && strpos($in, chr(0)) !== 1) { 
            while (($i = strpos($in, chr(0x13))) !== false) { 
                $j = strpos($in, chr(0x15), $i + 1); 
                if ($j === false) 
                    break; 

                $in = substr_replace($in, "", $i, $j - $i); 
            } 
            for ($i = 0; $i < strlen($in); $i++) { 
                if (ord($in[$i]) >= 32) {} 
                elseif ($in[$i] == ' ' || $in[$i] == '\n') {} 
                else 
                    $in = substr_replace($in, "", $i, 1); 
            } 
            $in = str_replace(chr(0), "", $in); 

            return $in; 
        } elseif ($check) { 
            while (($i = strpos($in, chr(0x13).chr(0))) !== false) { 
                $j = strpos($in, chr(0x15).chr(0), $i + 1); 
                if ($j === false) 
                    break; 

                $in = substr_replace($in, "", $i, $j - $i); 
            } 
            $in = str_replace(chr(0).chr(0), "", $in); 
        } 

        // ��� �� ������������ ������������������� 
        $skip = false; 
        for ($i = 0; $i < strlen($in); $i += 2) { 
            $cd = substr($in, $i, 2); 
            if ($skip) { 
                if (ord($cd[1]) == 0x15 || ord($cd[0]) == 0x15) 
                    $skip = false; 
                continue; 
            } 

            // ���� ������� ���� �������, �� ����� ���� ANSI 
            if (ord($cd[1]) == 0) { 
                // � ������, ���� ASCII-�������� ������� ����� ���� 32, �� ����� ��� ����. 
                if (ord($cd[0]) >= 32) 
                    $out .= $cd[0]; 
                elseif ($cd[0] == ' ' || $cd[0] == '\n') 
                    $out .= $cd[0]; 
                elseif (ord($cd[0]) == 0x13) 
                    $skip = true; 
                else { 
                    continue; 
                    // � ��������� ������ ��������� ������� �� ��������� ������� (������ ����� 
                    // ��������� � ���������). 
                    switch (ord($cd[0])) { 
                        case 0x0D: case 0x07: $out .= "\n"; break; 
                        case 0x08: case 0x01: $out .= ""; break; 
                        case 0x13: $out .= "HYPER13"; break; 
                        case 0x14: $out .= "HYPER14"; break; 
                        case 0x15: $out .= "HYPER15"; break; 
                        default: $out .= " "; break; 
                    } 
                } 
            } else { // ����� ��������������� � HTML entity 
                if (ord($cd[1]) == 0x13) { 
                    echo "@"; 
                    $skip = true; 
                    continue; 
                } 
                $out .= "&#x".sprintf("%04x", $this->getShort(0, $cd)).";"; 
            } 
        } 

        // � ���������� ��������� 
        return $out; 
    } 

    // ��������������� ������� ��� ������ ���������� ���������� ���� �� ������ 
    // � ������ ������� ������ � �������������� �������� � �����. 
    protected function getSomeBytes($data, $from, $count) { 
        // �� ��������� ������ ������ �� ���������� ������ $data. 
        if ($data === null) 
            $data = $this->data; 

        // ������ ����� 
        $string = substr($data, $from, $count); 
        // � ������ ��������� ������� ������ - �������������� ����� 
        if ($this->isLittleEndian) 
            $string = strrev($string); 

        // ������������ �� ��������� ������� � hex'�, � ����� � �����. 
        return hexdec(bin2hex($string)); 
    } 
    // ������ ����� �� ���������� (�� ��������� �� this->data) 
    protected function getShort($from, $data = null) { 
        return $this->getSomeBytes($data, $from, 2); 
    } 
    // ������ ������� ����� �� ���������� (�� ��������� �� this->data) 
    protected function getLong($from, $data = null) { 
        return $this->getSomeBytes($data, $from, 4); 
    } 
} 
?>