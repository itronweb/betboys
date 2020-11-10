<?php 
/*
*
*	configExcel function
*			$configExcel = [
				'properties' => [
					'Creator' => 'By Setade bazsazi',
					'LastModifiedBy' => 'Alborz Co',
					'Title' => 'گزارش جامع بازسازی',
					'Subject' => 'Everything',
					'Description' => 'All thing about this excel file'
				],
				'wrapCell' => [ 'B1:B3' ],
				'headerCell' => [ 'A1:U4']
			];

	configPDF function
			$configPDF = [
				'paper_size' => 'a3',
				'properties' => [
					'Creator' => 'By Setade bazsazi',
					'LastModifiedBy' => 'Alborz Co',
					'Title' => 'گزارش جامع بازسازی',
					'Subject' => 'Everything',
					'Description' => 'All thing about this PDF file'
				],
				'ColumnDimension' => [
					'B' => '30',
					'C' => '20',
					'D-N' => '8',
					'T' => '20',
					'U' => '20',
				],
				'oriention' => 'portrait',
				'margin' => [	
					'top' 	=> '0.75',
					'right' => '0.25',
					'left' 	=> '0.25',
					'bottom' => '0.75'
					],
				'numberCell' => ['C5:U18', 'A5:A14']

			];
*
*
*/
define('TMP_FILES', "../../uploads/excel/");

$basePath = ''; // make sure path and dir's are correct.
include_once ($basePath . 'PHPExcel.php');
include_once ($basePath . 'PHPExcel/IOFactory.php');
include_once ($basePath . 'PHPExcel/Writer/Excel2007.php');
//include_once ($basePath . 'PHPExcel/Writer/PDF/tcPDF.php');

ini_set("display_errors", "On");
error_reporting(E_ALL ^ E_NOTICE);

class convertHTMLtoExcel{
	
	public $fileName;
	
	public $htmlFile;
	
	/*
	*
	*/
	public function __construct(){
			// create random name for file
		$this->fileName = substr(md5(date('m/d/y h:i:s:u')), 0, 8);
		
		if(!is_dir( TMP_FILES )) { // check if temp folder not not exists
			mkdir( TMP_FILES, 0777 ); // create new temp dir for storing xlsx files.
		}
	
	}
	/*
	*	@param : stirng		$content 		// HTML content for convert to excel and PDF
	*	@param : array		$configExcel 	// Config attribute for excel file
	*	@param : array		$configPDF	 	// Config attribute for PDF file
	*
	*	@return : string	$fileName 		// Created file address: 
	*/
	public function generateExcelAndPDF($content,$configExcel,$configPDF){
		
		// Create HTML file
		$this->htmlFile = TMP_FILES . $this->fileName . '.html'; // create new html file under temp folder
		file_put_contents($this->htmlFile,$content); // copy the html contents into tmp created html file
		
		// Call excel function for generate excel file
		$excel = $this->excel($configExcel);
		$pdf = $excel ? $this->PDF($configPDF) : 'false';
		if($pdf)
			return( TMP_FILES . $this->fileName );
	}
	
	/*
	*	@param : stirng		$content 		// HTML content convert to excel
	*	@param : array		$configExcel 	// Config attribute for excel file
	*
	*	@return : string	$fileName 		// Created file address: 
	*/
	public function generateExcel($content,$configExcel){
		
		// Create HTML file
		$this->htmlFile = TMP_FILES . 'Mobile' . '.html'; // create new html file under temp folder
		file_put_contents($this->htmlFile,$content); // copy the html contents into tmp created html file
		
		// Call excel function for generate excel file
		$excel = $this->excel($configExcel);
		
		return( TMP_FILES . 'Mobile' . '.xlsx' );
	}
	
	/*
	*	@param : stirng		$content 		// HTML content convert to PDF
	*	@param : array		$configPDF	 	// Config attribute for PDF file
	*
	*	@return : string	$fileName 		// Created file address: 
	*/
	public function generatePDF($content,$configPDF){
		
		// Create HTML file
		$this->htmlFile = TMP_FILES . $this->fileName . '.html'; // create new html file under temp folder
		file_put_contents($this->htmlFile,$content); // copy the html contents into tmp created html file
		
		// Call excel function for generate PDF file
		
		$pdf = $this->PDF($configPDF);
		
		return( TMP_FILES . $this->fileName . '.pdf' );
	}
	
	/*
	*	@param : array 		$configExcel	// Config Excel attribute, For more reade line : ....
	*
	*	$return : bool		if Excel file is created
	*/
	public function excel($configExcel){
		
		$objReader = new PHPExcel_Reader_HTML; // new loader
		$objPHPExcel = $objReader->load($this->htmlFile); // load .html file that generated under temp folder
		
		// Set properties
		foreach($configExcel[properties] as $key=>$value){
			$setProperties = 'set' . $key;
			$objPHPExcel->getProperties()->{$setProperties}($value);
		}
		
		// right-to-left worksheet
		$objPHPExcel->getActiveSheet()->setRightToLeft(false);
		$objPHPExcel->getActiveSheet()->getHighestDataRow(100);
		// Text-wrap headre
		foreach($configExcel[wrapCell] as $wrap){
			$objPHPExcel->getActiveSheet()->getStyle($wrap.$objPHPExcel->getActiveSheet()->getHighestRow(0))
    				->getAlignment()->setWrapText(true); 	
		}
		// Example : Text-wrap headre
		//$objPHPExcel->getActiveSheet()->getStyle('A1:Z3')->getAlignment()->setWrapText(true); 
		// Example : Text-wrap B column
		//$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$objPHPExcel->getActiveSheet()->getHighestRow())
    	//			->getAlignment()->setWrapText(true); 
    
	   /* simple style to make sure all cell's text have HORIZONTAL_LEFT alignment */
		$headerStyle = array(
		    'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'wrap' => true,
		     ),
			'font'  => array(
				'name'  => 'Tahoma',
				'bold'  => true,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
			),
			
		);
		

		$style = array(
		    'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		     ),
			'font'  => array(
				'name'  => 'B Titr',
				'bold'  => false,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
			)
		);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);

		//Apply the style
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);
		// Apply style for header cell
		foreach( $configExcel[headerCell] as $header ){
			$objPHPExcel->getActiveSheet()->getStyle($header)->applyFromArray($headerStyle);		
		}
		// Example : Apply style for header cell exapmle
    	//	$objPHPExcel->getActiveSheet()->getStyle("A1:U4")->applyFromArray($headerStyle);	
		
		// Create excel file name in temp
		$excelFile = TMP_FILES . 'Mobile' . '.xlsx'; // create excel file under temp folder.
		
		// Creates a writer to output the $objPHPExcel's content
	 	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($excelFile); // saving the excel file
		
		// Write file to the browser
		//header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		//header('Cache-Control: max-age=0');
		//header('Content-Disposition: attachment; filename="'.$excelFile .'"');
		//header("Pragma: no-cache");
		//header ("Expires: 0");
		
		//unlink($htmlfile); // delete .html file
		return(true);
		//exit();
	}
	
	/*
	*	@param : array 		$configPDF	// Config Excel attribute, For more reade line : ....
	*
	*	$return : bool		if Excel file is created
	*/
	public function PDF($configPDF){
		
		$objReader = new PHPExcel_Reader_HTML; // new loader
		$objPHPExcel = $objReader->load($this->htmlFile); // load .html file that generated under temp folder		
		
		$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
		$rendererLibrary = 'tcpdf';
//		$rendererLibraryPath = 'PDF/' . $rendererLibrary;
		$rendererLibraryPath = dirname(__FILE__) . '/PHPExcel/Shared/PDF';
		
		
		// Set properties
		foreach($configPDF[properties] as $key=>$value){
			$setProperties = 'set' . $key;
			$objPHPExcel->getProperties()->{$setProperties}($value);
		}
//		// Set document properties
//		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
//									 ->setLastModifiedBy("Maarten Balliauw")
//									 ->setTitle("PDF Test Document")
//									 ->setSubject("PDF Test Document")
//									 ->setDescription("Test document for PDF, generated using PHP classes.")
//									 ->setKeywords("pdf php")
//									 ->setCategory("Test result file");
		// right-to-left worksheet
		$objPHPExcel->getActiveSheet()->setRightToLeft(true);
		
		// Set Row && column dimension
//		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
		
		// Set Column
		foreach($configPDF['ColumnDimension'] as $key=>$value){
			$columns = (explode('-',$key));
			foreach($columns as $columnName){
				for($columnName;$columnName<=end($columns);$columnName++){
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnName)->setWidth($value);
				}

			}
			
				
		}
		// Example
		//$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		//$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

		// Set defualt dimension but not working
		//	$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
		// Auto size
		//$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
		
//		
//		if( isset($configPDF['oriention']) && strtoupper($configPDF['oriention']) == 'PORTRAIT'){
//			$objPHPExcel->getActiveSheet()->getPageSetup()
//					->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
//		}else{
//			$objPHPExcel->getActiveSheet()->getPageSetup()
//					->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
//		}
		$objPHPExcel->getActiveSheet()->getPageSetup()
					->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Example set oriention
//		$objPHPExcel->getActiveSheet()->getPageSetup()
//					->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set paper size
		if(isset($configPDF['paper_size'])){
			switch ($configPDF['paper_size']){
			case 'c':
					$objPHPExcel->getActiveSheet()->getPageSetup()
								->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_C);
					break;
			case 'd':
					$objPHPExcel->getActiveSheet()->getPageSetup()
								->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_D);
					break;
			case 'e':
					$objPHPExcel->getActiveSheet()->getPageSetup()
								->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_E);
					break;
			case 'a2':
					$objPHPExcel->getActiveSheet()->getPageSetup()
								->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A2_PAPER);
				break;
			case 'a3':
					$objPHPExcel->getActiveSheet()->getPageSetup()
						->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3_EXTRA_TRANSVERSE_PAPER);
				break;
			case 'a4':
					$objPHPExcel->getActiveSheet()->getPageSetup()
								->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
				break;
			default:
				$objPHPExcel->getActiveSheet()->getPageSetup()
						->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3_EXTRA_TRANSVERSE_PAPER);
				break;
			}	
		}else{
			$objPHPExcel->getActiveSheet()->getPageSetup()
						->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3_EXTRA_TRANSVERSE_PAPER);
		}

		// Page margin
		if(!isset($configPDF['margin'])){
			$configPDF['margin'] = [
					'top' 		=> '0.75',
					'right' 	=> '0.25',
					'left' 		=> '0.25',
					'bottom' 	=> '0.75'
				];
		}
		
		foreach($configPDF['margin'] as $key=>$value){
				$setMargin = 'set'.ucfirst($key);
				$objPHPExcel->getActiveSheet()->getPageMargins()->{$setMargin}($value);
			}
		// Example
//		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.75);
//		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
//		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);
//		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.75);
		// right-to-left worksheet
		
		// Page setup
		$objPHPExcel->getActiveSheet()->getPageSetup()
									->setFitToPage(true)
									->setHorizontalCentered(true)
									->setVerticalCentered(true);
		
		//$style = array('font' => array('size' => 14,'bold' => true,'color' => array('rgb' => 'ff0000')));
		$style = array(
		    'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		     ),
			'font'  => array(
//				'name' => 'freeserif',
//				'name' => 'nazaninb',
//				'name' => 'yagutb',
//				'name' => 'zarb',
				'name' => 'zarbold',
				'bold'  => true,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
			)
		);
		// Persian Font ; aefurat && dejavusans
		
		$numberStyle = array(
			'font' => array(
//				'name' => 'hiwebmitra',
				'name' => 'zarbold',
			'size'  => 10,
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'FFFFFF')
        	)
		);
		$colorStyle = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'E7E7e7')
        	)
		);
		// Get row information
		$row = $configPDF['row'];
		$header_row = $configPDF['header_row'];
		$footer_row = $configPDF['footer_row'];
		// config number cell
 		if(isset($configPDF['numberCell'])){
			foreach($configPDF['numberCell'] as $number){
				$cell = explode(':',$number);
				if($cell[0]==$cell[1])
					$configPDF['number'] .= $cell[0].($header_row+1).':'.$cell[1].($header_row+$row);	
				else
					$configPDF['number'] .= $cell[0].($header_row+1).':'.$cell[1].($header_row+$row+$footer_row);
				$configPDF['number'] .= '-';
			}
		}
		// Set number style 
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);
			if(isset($configPDF['number'])){
				$configPDF['number']= explode('-',$configPDF['number']);
				foreach( $configPDF['number'] as $number ){
					if($number=='')
						break;
				$objPHPExcel->getActiveSheet()->getStyle($number)->applyFromArray($numberStyle);
				}	
			}
		// Set column for header and footer
		if(isset($configPDF['headerCell'])){
			$headerCell = explode(':',$configPDF['headerCell']);
			$configPDF['colorCell'][] = $headerCell[0].'1'.":".$headerCell[1].($header_row);
			$configPDF['colorCell'][] = $headerCell[0].($row+$header_row+1).':'.$headerCell[1].($row+$header_row+$footer_row);
		}
		// Set Color
		if(isset($configPDF['colorCell'])){
				foreach( $configPDF['colorCell'] as $color){
				$objPHPExcel->getActiveSheet()->getStyle($color)->applyFromArray($colorStyle);
				}	
			}	
		// Example
//		$objPHPExcel->getActiveSheet()->getStyle("C5:U18")->applyFromArray($numberStyle);
		
		// Add some data
//		$objPHPExcel->setActiveSheetIndex(0)
//					->setCellValue('A1', 'Hello')
//					->setCellValue('B2', 'world!')
//					->setCellValue('C1', 'Hello')
//					->setCellValue('D2', 'world!');
//
//		// Miscellaneous glyphs, UTF-8
//		$objPHPExcel->setActiveSheetIndex(0)
//					->setCellValue('A4', 'Miscellaneous glyphs')
//					->setCellValue('A5', 'ssss');

		// Rename worksheet
//		$objPHPExcel->getActiveSheet()->setTitle('Simple');
//		$objPHPExcel->getActiveSheet()->setShowGridLines(false);

		/* //If used external excel file 
		$inputFile="simple.xlsx";
		$inputFileType = PHPExcel_IOFactory::identify($inputFile);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFile);
		*/

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//		$objPHPExcel->setActiveSheetIndex(0);


		if (!PHPExcel_Settings::setPdfRenderer(
				$rendererName,
				$rendererLibraryPath
			)) {
			die(
				'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
				'<br />' .
				'at the top of this script as appropriate for your directory structure'
			);
		}


		// Redirect output to a client’s web browser (PDF)
//		header('Content-Type: application/pdf');
//		header('Content-Disposition: attachment;filename="01simple.pdf"');
//		header('Cache-Control: max-age=0');
		$PDFfile = TMP_FILES . $this->fileName . '.pdf';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
//		$objWriter->SetFont('dejavusans');
		
//		var_dump($objWriter->getPaperSize());
//		
//		var_dump($test);
//		var_dump($objPHPExcel);
		$objWriter->save($PDFfile);
		return(true);
	}
	
	//////////////////////////
	// Test
	private function generateRandomName() {
		$randName = substr(md5(date('m/d/y h:i:s:u')), 0, 8);
		if(file_exists(TMP_FILES . $randName . '.html')) {
			return $this -> generateRandomName();
		}
		return $randName;
	}
	
//	public function generateExcel($content) { // $content <- html_content
		
//		$filename = $this -> generateRandomName();
//
//		if( !ini_get('date.timezone') ) {
//		    date_default_timezone_set('GMT');
//		}
//		
//		if(!is_dir( TMP_FILES )) { // check if temp folder not not exists
//			mkdir( TMP_FILES, 0777 ); // create new temp dir for storing xlsx files.
//		}
//
//		$htmlfile = TMP_FILES . $filename . '.html'; // create new html file under temp folder
//		file_put_contents($htmlfile, utf8_decode($content)); // copy the html contents into tmp created html file
//		
//		$objReader = new PHPExcel_Reader_HTML; // new loader
//		$objPHPExcel = $objReader->load($htmlfile); // load .html file that generated under temp folder
//		
//		// Set properties
//		$objPHPExcel->getProperties()->setCreator("Narain Sagar");
//		$objPHPExcel->getProperties()->setLastModifiedBy("Narain Sagar");
//		$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
//		$objPHPExcel->getProperties()->setSubject("XLSX Report");
//		$objPHPExcel->getProperties()->setDescription("XLSX report document for Office 2007");
//    
//                /* simple style to make sure all cell's text have HORIZONTAL_LEFT alignment */
//		$style = array(
//		    'alignment' => array(
//		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
//		     )
//		);
//
//		//Apply the style
//	        $objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);
//    
//	        $excelFile = TMP_FILES . $filename . '.xlsx'; // create excel file under temp folder.
//	    
//		// Creates a writer to output the $objPHPExcel's content
//	 	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//		$objWriter->save($excelFile); // saving the excel file
//
//		unlink($htmlfile); // delete .html file
//		
//		if(file_exists($excelFile)) {
//			return $filename . '.xlsx';
//		}
//		
//		return false;		
//	}
	
	public function generateExceltest($content) { // $content <- html_content
		
		$filename = substr(md5(date('m/d/y h:i:s:u')), 0, 8);
		
		if(!is_dir( TMP_FILES )) { // check if temp folder not not exists
			mkdir( TMP_FILES, 0777 ); // create new temp dir for storing xlsx files.
		}

		$htmlfile = $filename . '.html'; // create new html file under temp folder
		file_put_contents($htmlfile,$content); // copy the html contents into tmp created html file
		
		$objReader = new PHPExcel_Reader_HTML; // new loader
		$objPHPExcel = $objReader->load($htmlfile); // load .html file that generated under temp folder
		
		// Set properties
		$objPHPExcel->getProperties()->setCreator("Setad Bazsazi");
		$objPHPExcel->getProperties()->setLastModifiedBy("User_name");
		$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
		$objPHPExcel->getProperties()->setSubject("XLSX Report");
		$objPHPExcel->getProperties()->setDescription("XLSX report document for Office 2007");
		
		// right-to-left worksheet
		$objPHPExcel->getActiveSheet()->setRightToLeft(true);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(10);
		// Text-wrap headre
//		$objPHPExcel->getActiveSheet()->getStyle('A1:Z3')->getAlignment()->setWrapText(true); 
		// Text-wrap B column
		$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$objPHPExcel->getActiveSheet()->getHighestRow())
    				->getAlignment()->setWrapText(true); 
    
	   /* simple style to make sure all cell's text have HORIZONTAL_LEFT alignment */
		$headerStyle = array(
		    'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'wrap' => true,
		     ),
			'font'  => array(
				'name'  => 'Tahoma',
				'bold'  => true,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
			),
			
		);
		

		$style = array(
		    'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		     ),
			'font'  => array(
				'name'  => 'B Titr',
				'bold'  => false,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
			)
		);
		
	

		//Apply the style
	        $objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);
    		$objPHPExcel->getActiveSheet()->getStyle("A1:U4")->applyFromArray($headerStyle);	
	        $excelFile = $filename . '.xlsx'; // create excel file under temp folder.
		
//		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');
//		header('Pragma: no-cache');
//		header('Cache-Control: ');
		header('Content-disposition: attachment; filename="'. $excelFile .'"');
	    
		// Creates a writer to output the $objPHPExcel's content
	 	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($excelFile); // saving the excel file
		$objWriter->save('php://output'); // Download the excel file
		var_dump($objReader);
		
		exit();
	}
	// End test excel
	////////////////////////
	
	///////////////////////// Generate Excel /////////////////////
	
	public function generateExcel1($content) { // $content <- html_content
		
		$filename = substr(md5(date('m/d/y h:i:s:u')), 0, 8);
		
		if(!is_dir( TMP_FILES )) { // check if temp folder not not exists
			mkdir( TMP_FILES, 0777 ); // create new temp dir for storing xlsx files.
		}

		$htmlfile = TMP_FILES . $filename . '.html'; // create new html file under temp folder
		file_put_contents($htmlfile,$content); // copy the html contents into tmp created html file
		
		$objReader = new PHPExcel_Reader_HTML; // new loader
		$objPHPExcel = $objReader->load($htmlfile); // load .html file that generated under temp folder
		
		// Set properties
		$objPHPExcel->getProperties()->setCreator("Setad Bazsazi");
		$objPHPExcel->getProperties()->setLastModifiedBy("User_name");
		$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Document");
		$objPHPExcel->getProperties()->setSubject("XLSX Report");
		$objPHPExcel->getProperties()->setDescription("XLSX report document for Office 2007");
		
		// right-to-left worksheet
		$objPHPExcel->getActiveSheet()->setRightToLeft(true);
		// Text-wrap headre
//		$objPHPExcel->getActiveSheet()->getStyle('A1:Z3')->getAlignment()->setWrapText(true); 
		// Text-wrap B column
		$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$objPHPExcel->getActiveSheet()->getHighestRow())
    				->getAlignment()->setWrapText(true); 
    
	   /* simple style to make sure all cell's text have HORIZONTAL_LEFT alignment */
		$headerStyle = array(
		    'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'wrap' => true,
		     ),
			'font'  => array(
				'name'  => 'Tahoma',
				'bold'  => true,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
			),
			
		);
		

		$style = array(
		    'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		     ),
			'font'  => array(
				'name'  => 'B Titr',
				'bold'  => false,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
			)
		);
		
	

		//Apply the style
	        $objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);
    		$objPHPExcel->getActiveSheet()->getStyle("A1:U4")->applyFromArray($headerStyle);	
	        $excelFile = TMP_FILES . $filename . '.xlsx'; // create excel file under temp folder.
		
	   
		// Creates a writer to output the $objPHPExcel's content
	 	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($excelFile); // saving the excel file
		
		// Write file to the browser
//		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//		header('Cache-Control: max-age=0');
//		header('Content-Disposition: attachment; filename="'.$excelFile .'"');
//		header("Pragma: no-cache");
//		header ("Expires: 0");
		
		unlink($htmlfile); // delete .html file
		return($excelFile);
//		exit();
	}
	
	/////////////////////// End of Generate exel file /////////////////
	
	////////////// Test Create PDF //////////////////////////////////////
	
//	public function generatePDF($content){
//		$objPHPExcel = new PHPExcel();
//		$filename = $content;
//
//		if (PHPExcel_IOFactory::identify($filename) == "Excel2007") {
//			$objReader = PHPExcel_IOFactory::createReader("Excel2007");	
//			$objReader->setIncludeCharts(TRUE);
//		} else {
//			die("Can't load charts");
//		}
//
//		$objPHPExcel = $objReader->load($filename);
//
//		$objPHPExcel->setActiveSheetIndex(0);
//
//		$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
//		$rendererLibraryPath = (dirname(__FILE__) . '/PDF');
//	//	var_dump($filename);
//		if(!PHPExcel_Settings::setPdfRenderer( $rendererName, $rendererLibraryPath ) ) {
//			die('NOTICE: Please set the $rendererName and $rendererLibraryPath values' .'<br />' .'at the top of s script as appropriate for your directory structure');
//		}
//
//		$pdfFile = TMP_FILES . '123.pdf';
//		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
//		$objWriter->writeAllSheets();
//	//		var_dump($objWriter);
//
//		$objWriter->save($pdfFile);
//	//    $objWriter->save('php://output');
//	//		return ($pdfFile);
//
//			// Redirect output to a client’s web browser (PDF)
//			header('Content-Type: application/pdf');
//			header('Content-Disposition: attachment;filename="01simple.pdf"');
//			header('Cache-Control: max-age=0');
//
//			$objWriter->save('php://output');
//			exit();
//
//	//		return true;
//	//		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
//	//		$objWriter->save('php:// output');
//	//		exit;
//	//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
//	//$objWriter->save('php://output');
//	}
	
	public function generatePDFtest($content){
		
		$filename = $content;
		$objPHPExcel = new PHPExcel();
		
		$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
		$rendererLibrary = 'tcpdf';
//		$rendererLibraryPath = 'PDF/' . $rendererLibrary;
		$rendererLibraryPath = dirname(__FILE__) . '/PDF';
		
		$inputFile = $content ;
		
		$inputFileType = PHPExcel_IOFactory::identify($inputFile);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFile);
		
		$objPHPExcel->setActiveSheetIndex(0);

		if (!PHPExcel_Settings::setPdfRenderer(
		  $rendererName,
		  $rendererLibraryPath
		 )) {
		 die(
		  'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
		  '<br />' .
		  'at the top of this script as appropriate for your directory structure'
		 );
		}
		
		$PDFFile = $filename . '.pdf';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
//		var_dump($objPHPExcel);
		try{
			$objWriter->save($PDFFile);
		}catch(Exception $e){
			echo $error;
		}
//		var_dump($PDFFile);
//		echo "save";
		
	}
	
	//////////// End test create PDF //////////////////////////////////////
	
	//////////// Generate PDF ////////////////////////////////
	public function generatePDF1($content){
		
		//$objPHPExcel = new PHPExcel();
		
		$filename = substr(md5(date('m/d/y h:i:s:u')), 0, 8);
		
		if(!is_dir( TMP_FILES )) { // check if temp folder not not exists
			mkdir( TMP_FILES, 0777 ); // create new temp dir for storing xlsx files.
		}
		
		$htmlfile = TMP_FILES . $filename . '.html'; // create new html file under temp folder
		file_put_contents($htmlfile,$content); // copy the html contents into tmp created html file
		
		$objReader = new PHPExcel_Reader_HTML; // new loader
		$objPHPExcel = $objReader->load($htmlfile); // load .html file that generated under temp folder		
		
		$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
		$rendererLibrary = 'tcpdf';
//		$rendererLibraryPath = 'PDF/' . $rendererLibrary;
		$rendererLibraryPath = dirname(__FILE__) . '/PHPExcel/Shared/PDF';

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
									 ->setLastModifiedBy("Maarten Balliauw")
									 ->setTitle("PDF Test Document")
									 ->setSubject("PDF Test Document")
									 ->setDescription("Test document for PDF, generated using PHP classes.")
									 ->setKeywords("pdf php")
									 ->setCategory("Test result file");
		// right-to-left worksheet
		$objPHPExcel->getActiveSheet()->setRightToLeft(true);
		
		// Set Row && column dimension
//		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
		
		// Set Column
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
		// Set defualt dimension but not working
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
		// Auto size
//		$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
		
		
		$objPHPExcel->getActiveSheet()->getPageSetup()
					->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// A2 paper
//		$objPHPExcel->getActiveSheet()->getPageSetup()
//					->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A2_PAPER);
		// A4 paper
		$objPHPExcel->getActiveSheet()->getPageSetup()
					->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3_EXTRA_TRANSVERSE_PAPER);
//		
		// Page margin
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.75);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.75);
		// right-to-left worksheet
		$objPHPExcel->getActiveSheet()->getPageSetup()
									->setFitToPage(true)
									->setHorizontalCentered(true)
									->setVerticalCentered(true);
		
		//$style = array('font' => array('size' => 14,'bold' => true,'color' => array('rgb' => 'ff0000')));
		$style = array(
		    'alignment' => array(
		        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		     ),
			'font'  => array(
				'name' => 'freeserif',
				'bold'  => true,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
			)
		);
		// Persian Font ; aefurat && dejavusans
		
		$numberStyle = array(
			'font' => array(
				'name' => 'hiwebmitra',
			'size'  => 10,
			)
		);
		
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle("C5:U18")->applyFromArray($numberStyle);
		// Add some data
//		$objPHPExcel->setActiveSheetIndex(0)
//					->setCellValue('A1', 'Hello')
//					->setCellValue('B2', 'world!')
//					->setCellValue('C1', 'Hello')
//					->setCellValue('D2', 'world!');
//
//		// Miscellaneous glyphs, UTF-8
//		$objPHPExcel->setActiveSheetIndex(0)
//					->setCellValue('A4', 'Miscellaneous glyphs')
//					->setCellValue('A5', 'ssss');

		// Rename worksheet
//		$objPHPExcel->getActiveSheet()->setTitle('Simple');
//		$objPHPExcel->getActiveSheet()->setShowGridLines(false);

		/* //If used external excel file 
		$inputFile="simple.xlsx";
		$inputFileType = PHPExcel_IOFactory::identify($inputFile);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFile);
		*/

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		if (!PHPExcel_Settings::setPdfRenderer(
				$rendererName,
				$rendererLibraryPath
			)) {
			die(
				'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
				'<br />' .
				'at the top of this script as appropriate for your directory structure'
			);
		}


		// Redirect output to a client’s web browser (PDF)
//		header('Content-Type: application/pdf');
//		header('Content-Disposition: attachment;filename="01simple.pdf"');
//		header('Cache-Control: max-age=0');
		$PDFfile = TMP_FILES . $filename . '.pdf';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
//		$objWriter->SetFont('dejavusans');
		
//		var_dump($objWriter->getPaperSize());
//		
//		var_dump($test);
//		var_dump($objPHPExcel);
		$objWriter->save($PDFfile);
		return($PDFfile);

		
	}
	
	//////////// End of Generate PDF ////////////////////////////
	
	////////////////// Test //////////////////////////////////////
	public function downloadFile() {
		$fields = array("fileName");
		
		$fileName = TMP_FILES . $_GET['fileName'];
		$fileNamePieces = explode( '.', $fileName);
		if(count($fileNamePieces) > 1) {
			$fileType = array_pop($fileNamePieces);
		}

		if(file_exists($fileName) && ($fileType == 'html' || $fileType == 'xlsx')) {
			if($fileType == 'xlsx') {
				header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Pragma: ');
				header('Cache-Control: ');
				header('Content-disposition: attachment; filename="'. $_GET['fileName'] .'"');
			}
			else {
				header('Content-Type: text/html');
			}

			readfile($fileName);
			unlink($fileName); // each asset can only be accessed once, delete after access
			exit();
		}
	}	
	public function downloadFile1() {
		
		$fileName = $_POST['fileName'];
		
//		if ($_POST['fileName'] != '')
//			$name = end(explode( '/' , $fileName));
//		
		$fileNamePieces = explode( '.', $fileName);
		if(count($fileNamePieces) > 1) {
			$fileType = array_pop($fileNamePieces);
			
		}
		var_dump($fileName);
		
//		var_dump($fileName);
		if(file_exists($fileName) && ($fileType == 'html' || $fileType == 'xlsx')) {
			if($fileType == 'xlsx') {
				
				$fileName = $_POST['fileName'];
				header("location: $fileName");
				
			}
			else {
				// dl HTML
			}
			
//			${"$fileName"}->save('php://output');
//			readfile($fileName);
//			unlink($fileName); // each asset can only be accessed once, delete after access
//			ob_end_clean();
//			exit();
		}
		
	}	
	
}

?>