<?php

	/**
	* @desc Para correto funcionamento, deverá estar ativada a biblioteca do php phpzip
	* 
	* @author Tiago Teixeira
	* @desc classe responsavel por zipar arquivos.
	 Exemplo de uso:
	 	//Envia um ou varios arquivos
	 	//deve ser enviado o caminho completo dos arquivos
		 $files = array('temp/exemplo1.txt','temp/exemplo2.txt');

	    $arquivo = new Zip();
	    $arquivo->setZipName('name');// o nome que sera salvo o arquivo zip
	    $arquivo->addArquivo($files);//arquivos que serao zipados
	    $arquivo->salvarZip();//zipa os arquivos
	    $arquivo->baixarZip();//baixa os arquivos
	   	$arquivo->deletar();//deleta o zip criado anteriormente
	*/
	class Zip extends ZipArchive{
 		
 		private $arquivos;//arquivos já devem vir com o caminho completo
 		private $zipName = 'Arquivos';
 		private $zip;//referecia a classe pai
 		private $path = 'certificados/';//pasta onde sera armazenado o zip
 		private $basepath;//url completa do arquivo zip
 		private $fileName;//nome do arquivo zipado
 		private $files;//arquivos zipados a serem excluidos
 		
 		function __construct($zipName,$files=array()) {
 			// $this->zip = new ZipArchive();
 			$this->zipName = $zipName;
 			
 			if (!empty($files)) {
 				$this->addArquivo($files);
 			}
 		}

 		public function setZipName($name){
 			$this->zipName = $name;
 		}

 		public function setFileName($name){
 			$this->fileName = $name;
 		}

 		public function getQtdFiles(){
 			return count($this->files);
 		}

 		public function getFileName($posicao){
 			return $this->fileName[$posicao];
 		}

 		public function setPath($name){
 			$this->path = $name;
 		}

 		public function addArquivo($arquivo){

 			if (is_array($arquivo)) {
 				foreach ($arquivo as $key => $value) {
 					$this->arquivos[] = $value;
 				}
 			} else {
 				$this->arquivos[] = $arquivo;
 			}
 		}

 		public function open(){
 			$this->basepath = $this->path.$this->zipName.".zip";
 			// $this->zip->open($this->basepath, ZipArchive::CREATE|ZipArchive::OVERWRITE);
 			parent::open($this->basepath, ZipArchive::CREATE|ZipArchive::OVERWRITE);
 		}

 		public function close(){
 			// $this->zip->close();
 			parent::close();
 		}

 		public function salvarZip(){
 			try {
 				
 				$this->open();
	 			
	 			foreach ($this->arquivos as $value) {
	 				// $this->zip->addFile($value);
	 				parent::addFile($this->path.$value);
	 			}

	 			$this->close();

 			} catch (Exception $e) {
 				echo "Ocorreu um erro do tipo ".$e->getMessage();
 			}
 		}

 		public function baixarZip($default=true){
 			header('Content-type: application/zip');
		    header('Content-disposition: attachment; filename="'.$this->zipName.'"');
		    readfile($this->basepath);
		    
		    if ($default) {
		    	$this->deletar();
		    }
 		}

 		public function deletar(){
 			try {
 				
 				if (file_exists($this->basepath)) { 					
	 				unlink($this->basepath);	 				
 				}

 			} catch (Exception $e) {
 				print "Erro ao excluir arquivo ".$e->getMessage();
 			}
 		}

 		private function criaDiretorio(){
 			
 			if(!file_exists($this->path)) {
            	if (!mkdir($this->path,0777,true)){
            		echo "Erro ao criar diretório";
            	}
            }
 			
 		}

 		public function criaArquivoTxt($array) {
		    try {
		    	$this->criaDiretorio();
		        $name = $this->path.$this->fileName;//$array['name'];//date("dd-mm-YYYY-h-i-s")."txt";
		        $fp = fopen($name, "a");
		        $escreve = fwrite($fp, $array['conteudo']);
		        fclose($fp);
		        $this->files[] = $name;
		        return $name;   
		    } catch (Exception $e) {
		        echo "Erro => ".$e->getMessage();
		    }
		}

		function deleteFiles(){
			try {
				foreach ($this->files as $key => $value) {
					unlink($value);
				}
				
			} catch (Exception $e) {
				echo "Erro ao exlcuir arquivos => ".$e->getMessage();
			}
		}
 	}	

    // $files = array('notes.txt','notes2.txt');

    // $arquivo = new Zip("Teste_tiago3");    
    // // // $arquivo->setZipName('teste_tiago2');    
    // $arquivo->addArquivo($files);
    // $arquivo->salvarZip();
    // $arquivo->baixarZip();
?>