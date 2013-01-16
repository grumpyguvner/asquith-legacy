<?php
static $config = NULL;
static $log = NULL;

// Error Handler
function error_handler_for_report_export($errno, $errstr, $errfile, $errline) {
	global $config;
	global $log;
	
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$errors = "Notice";
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$errors = "Warning";
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$errors = "Fatal Error";
			break;
		default:
			$errors = "Unknown";
			break;
	}
		
	if (($errors=='Warning') || ($errors=='Unknown')) {
		return true;
	}

	if ($config->get('config_error_display')) {
		echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}
	
	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
}


function fatal_error_shutdown_handler_for_report_export()
{
	$last_error = error_get_last();
	if ($last_error['type'] === E_ERROR) {
		// fatal error
		error_handler_for_export(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
	}
}

class ModelReportExport extends Model {
    

	protected function clearSpreadsheetCache() {
		$files = glob(DIR_CACHE . 'Spreadsheet_Excel_Writer' . '*');
		
		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					@unlink($file);
					clearstatcache();
				}
			}
		}
	}
    
    public function download($fileName = 'Report', $worksheetName = 'Sheet 1', $data = array(), $headings = array(), $settings = array()) {
        
		global $config;
		global $log;
		$config = $this->config;
		$log = $this->log;
		set_error_handler('error_handler_for_report_export',E_ALL);
		register_shutdown_function('fatal_error_shutdown_handler_for_report_export');
        
        chdir( '../system/pear' );
        require_once 'Spreadsheet/Excel/Writer.php';
		chdir( '../../admin' );

        // Creating a workbook
        $workbook = new Spreadsheet_Excel_Writer();
        
		$workbook->setTempDir(DIR_CACHE);
		$workbook->setVersion(8); // Use Excel97/2000 BIFF8 Format

        // sending HTTP headers
        $workbook->send($fileName . date('_Ymd_Hi') . '.xls');

        // Creating a worksheet
        $worksheet =& $workbook->addWorksheet($worksheetName);
        $worksheet->setInputEncoding ( 'UTF-8' );

        $priceFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '######0.00'));
		$boxFormat =& $workbook->addFormat(array('Size' => 10,'vAlign' => 'vequal_space' ));
		$weightFormat =& $workbook->addFormat(array('Size' => 10,'Align' => 'right','NumFormat' => '##0.00'));
		$textFormat =& $workbook->addFormat(array('Size' => 10, 'NumFormat' => "@" ));
		
		$i = 0;
		foreach ($data as $rownum => $row) {
			if ($i == 0 && !empty($headings))
            {
                foreach ($headings as $key => $column) {
                    $worksheet->writeString($i, $key, $column, $boxFormat);
                }
                $i += 1;
            }
            foreach ($row as $key => $column) {
                $format = (array_key_exists($key, $settings)) ? $settings[$key] . 'Format' : 'textFormat';
                if (!isset($$format)) $format = 'textFormat';
                $worksheet->write($i, $key, $column, $$format);
            }
			$i += 1;
		}

        // Let's send the file
        $workbook->close();
        
		// Clear the spreadsheet caches
		$this->clearSpreadsheetCache();
        
		exit;
	}
    
}

?>
