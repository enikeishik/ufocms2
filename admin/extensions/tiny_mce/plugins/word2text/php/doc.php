<?php 
// ������ ������ �� DOC 
// ������ 0.4 
// �����: ������� ������ a.k.a Ramon 
// E-mail: alex@rembish.ru 
// Copyright 2009 

// ����� �������� � doc, �� ����� ����� �������� � WCBFF �� ��� ��? 
require_once "cfb.php"; 

// ����� ��� ������ � Microsoft Word Document (� ������ doc), ��������� 
// Windows Compound Binary File Format. ������� ��������� ����� ����� � 
// ����� 
class doc extends cfb { 
    // ������� parse ��������� ������������ ������� � �� ������ �������� 
    // ����� �� ������� �����. ���� ���-�� ����� �� ��� - ���������� false 
    public function parse() { 
        parent::parse(); 

        // ��� ������ DOC'� ��� ����� ��� ������ - WordDocument � 0Table ��� 
        // 1Table � ����������� �� ��������. ��� ������ ����� ������ - � �� 
        // (������) ���������� ������� ������, ������� ��� ������ �������. 
        $wdStreamID = $this->getStreamIdByName("WordDocument"); 
        if ($wdStreamID === false) { return false; } 

        // ����� �����, ������ ��� � ���������� 
        $wdStream = $this->getStreamById($wdStreamID); 

        // ����� ��� ����� �������� ���-��� �� FIB - ����������� ���� ��� ��������� 
        // File Information Block � ������ ������ WordDocument. 
        $bytes = $this->getShort(0x000A, $wdStream); 
        // ��������� ����� ������ ������� ��� ����� ����� ������ - ������ ��� �������. 
        // ��� ����� ��������� ���� ��������� ��� �� ��������� �� ���������� ��������. 
        $fWhichTblStm = ($bytes & 0x0200) == 0x0200; 

        // ������ ��� ����� ������ ������� CLX � ��������� ������. �� � ������ ����� ������ 
        // CLX - ����� ��� ����� �����. 
        $fcClx = $this->getLong(0x01A2, $wdStream); 
        $lcbClx = $this->getLong(0x01A6, $wdStream); 

        // ������ ��������� ��������, ����� �������� ������� �� ����������� � clx 
        $ccpText = $this->getLong(0x004C, $wdStream); 
        $ccpFtn = $this->getLong(0x0050, $wdStream); 
        $ccpHdd = $this->getLong(0x0054, $wdStream); 
        $ccpMcr = $this->getLong(0x0058, $wdStream); 
        $ccpAtn = $this->getLong(0x005C, $wdStream); 
        $ccpEdn = $this->getLong(0x0060, $wdStream); 
        $ccpTxbx = $this->getLong(0x0064, $wdStream); 
        $ccpHdrTxbx = $this->getLong(0x0068, $wdStream); 

        // � ������� ������������� ��������, ������� �������� ���������� CP - character position 
        $lastCP = $ccpFtn + $ccpHdd + $ccpMcr + $ccpAtn + $ccpEdn + $ccpTxbx + $ccpHdrTxbx; 
        $lastCP += ($lastCP != 0) + $ccpText; 

        // ������� � ����� ������ ��� ��������. 
        $tStreamID = $this->getStreamIdByName(intval($fWhichTblStm)."Table"); 
        if ($tStreamID === false) { return false; } 

        // � ��������� �� �� ����� � ���������� 
        $tStream = $this->getStreamById($tStreamID); 
        // ����� ������� � ������ CLX 
        $clx = substr($tStream, $fcClx, $lcbClx); 

        // � ������ ��� � CLX (complex, ���) ����� ����� ����� �� ���������� � ������������� 
        // �������� ������. 
        $lcbPieceTable = 0; 
        $pieceTable = ""; 

        // ������, ��� ����� ������������� ����. � ������������ �� ����� ������ �� ������� 
        // ������� ���� ����� ���� �� pieceTable � ���� CLX, ������� ����� �������� �� ������ 
        // �������� - ���� ��������� ������ pieceTable (����������� ���������� �� 0�02), ����� 
        // ������ ��������� 4 ����� - ����������� pieceTable. ���� ����������� �� ����� � 
        // �����������, ���������� �� ��������, �� �����! �� ����� ���� pieceTable. ���? 
        // ���� ������. 

        $from = 0; 
        // ���� 0�02 � �������� �������� � CLX 
        while (($i = strpos($clx, chr(0x02), $from)) !== false) { 
            // ������� ������ pieceTable 
            $lcbPieceTable = $this->getLong($i + 1, $clx); 
            // ������� pieceTable 
            $pieceTable = substr($clx, $i + 5); 

            // ���� ������ ����������� ���������� �� �������, �� ��� �� �� - 
            // ���� ������. 
            if (strlen($pieceTable) != $lcbPieceTable) { 
                $from = $i + 1; 
                continue; 
            } 
            // ���� ��� - ����� �����, break, ��������! 
            break; 
        } 

        // ������ ��������� ������ character positions, ���� �� �������� 
        // �� ��������� CP. 
        $cp = array(); $i = 0; 
        while (($cp[] = $this->getLong($i, $pieceTable)) != $lastCP) 
            $i += 4; 
        // ������� ��� �� PCD (piece descriptors) 
        $pcd = str_split(substr($pieceTable, $i + 4), 8); 

        $text = ""; 
        // ���! �� ������� � �������� - ������ ������ �� �����. 
        // ��� �� ������������� �������� 
        for ($i = 0; $i < count($pcd); $i++) { 
            // �������� ����� �� ��������� � ������ ���������� 
            $fcValue = $this->getLong(2, $pcd[$i]); 
            // ������� - ��� ����� ���� ����� ANSI ��� Unicode 
            $isANSI = ($fcValue & 0x40000000) == 0x40000000; 
            // ��������� ��� ������� ��� �� �������� 
            $fc = $fcValue & 0x3FFFFFFF; 

            // �������� ����� ������� ������ 
            $lcb = $cp[$i + 1] - $cp[$i]; 
            // ���� ����� ���� Unicode, �� �� ������ ��������� � ��� ���� ������ ������ 
            if (!$isANSI) 
                $lcb *= 2; 
            // ���� ANSI, �� ������ � ��� ���� ������. 
            else 
                $fc /= 2; 

            // ������ ����� � ������ �������� � ������� �� WordDocument-������ 
            $part = substr($wdStream, $fc, $lcb); 
            // ���� ����� ���� Unicode, �� ��������������� ��� � ���������� ��������� 
            if (!$isANSI) 
                $part = $this->unicode_to_utf8($part); 

            // ��������� ������� � ������ ������ 
            $text .= $part; 
        } 

        // ������� �� ����� ��������� � ���������� ��������� 
        $text = preg_replace("/HYPER13 *(INCLUDEPICTURE|HTMLCONTROL)(.*)HYPER15/iU", "", $text); 
        $text = preg_replace("/HYPER13(.*)HYPER14(.*)HYPER15/iU", "$2", $text); 
        // ���������� ��������� 
        return $text; 
    } 
    // ������� �������������� �� Unicode � UTF8, � �� ���-�� �� ���. 
    protected function unicode_to_utf8($in) { 
        $out = ""; 
        // ��� �� ������������ ������������������� 
        for ($i = 0; $i < strlen($in); $i += 2) { 
            $cd = substr($in, $i, 2); 

            // ���� ������� ���� �������, �� ����� ���� ANSI 
            if (ord($cd[1]) == 0) { 
                // � ������, ���� ASCII-�������� ������� ����� ���� 32, �� ����� ��� ����. 
                if (ord($cd[0]) >= 32) 
                    $out .= $cd[0]; 

                // � ��������� ������ ��������� ������� �� ��������� ������� (������ ����� 
                // ��������� � ���������). 
                switch (ord($cd[0])) { 
                    case 0x0D: $out .= "\r"; break; 
                    case 0x0A: $out .= "\n"; break; 
                    case 0x07: $out .= "\r\n"; break; 
                    case 0x08: case 0x01: $out .= ""; break; 
                    case 0x13: $out .= "HYPER13"; break; 
                    case 0x14: $out .= "HYPER14"; break; 
                    case 0x15: $out .= "HYPER15"; break; 
                } 
            } else // ����� ��������������� � HTML entity 
                $out .= html_entity_decode("&#x".sprintf("%04x", $this->getShort(0, $cd)).";"); 
        } 

        // � ���������� ��������� 
        return $out; 
    } 
} 

// ������� ��� �������������� doc � plain-text. ��� ���, ���� "�� ����� ������". 
function doc2text($filename) { 
    $doc = new doc; 
    $doc->read($filename); 
    return $doc->parse(); 
} 
?>