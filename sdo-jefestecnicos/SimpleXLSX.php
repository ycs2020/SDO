<?php

class SimpleXLSX {
    private $sheets = [];
    private $error = '';

    public function __construct($filename) {
        if (!$this->parseFile($filename)) {
            throw new Exception($this->error);
        }
    }

    public static function parse($filename) {
        try {
            $xlsx = new self($filename);
            return $xlsx;
        } catch (Exception $e) {
            return false;
        }
    }

    private function parseFile($filename) {
        $zip = new ZipArchive();
        if ($zip->open($filename) === TRUE) {
            $index = 1; // Corregido: empieza desde la primera hoja
            while ($xml = $zip->getFromName("xl/worksheets/sheet$index.xml")) {
                $this->sheets[] = simplexml_load_string($xml);
                $index++;
            }
            $zip->close();
            return true;
        } else {
            $this->error = 'Unable to open the file';
            return false;
        }
    }

    public function rows($sheetIndex = 0) {
        $rows = [];
        if (isset($this->sheets[$sheetIndex])) {
            foreach ($this->sheets[$sheetIndex]->sheetData->row as $row) {
                $rowData = [];
                foreach ($row->c as $cell) {
                    $value = (string)$cell->v;
                    $rowData[] = $value;
                }
                $rows[] = $rowData;
            }
        }
        return $rows;
    }

    public function error() {
        return $this->error;
    }
}
?>
