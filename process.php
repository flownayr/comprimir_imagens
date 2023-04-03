<?php
// Verifica se foi enviado um arquivo
if(isset($_FILES['image'])){
	// Define as configurações de redimensionamento
	$max_width = 800;
	$max_height = 0;

	// Obtém o nome e o tipo do arquivo
	$file_name = $_FILES['image']['name'];
	$file_type = $_FILES['image']['type'];

	// Verifica se o arquivo é uma imagem
	if($file_type == 'image/jpeg' || $file_type == 'image/png'){
		// Define o caminho e o nome temporário do arquivo
		$tmp_name = $_FILES['image']['tmp_name'];
		$new_name = 'compressed_'.$file_name;

		// Obtém as dimensões da imagem original
		list($orig_width, $orig_height) = getimagesize($tmp_name);

		// Calcula as novas dimensões mantendo a proporção
		$ratio = $orig_width / $max_width;
		$new_width = $max_width;
		$new_height = $orig_height / $ratio;

		// Cria uma nova imagem com as dimensões redimensionadas
		$new_img = imagecreatetruecolor($new_width, $new_height);

		// Carrega a imagem original
		if($file_type == 'image/jpeg'){
			$orig_img = imagecreatefromjpeg($tmp_name);
		}else{
			$orig_img = imagecreatefrompng($tmp_name);
		}

		// Redimensiona a imagem original
		imagecopyresampled($new_img, $orig_img, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);

		// Comprime a imagem
		imagejpeg($new_img, $new_name, 75);

		// Define o cabeçalho para download
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($new_name));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($new_name));

		// Lê o arquivo e envia para o navegador
		ob_clean();
		flush();
		readfile($new_name);

		// Remove o arquivo temporário
		unlink($new_name);
	}else{
		echo 'Por favor, selecione um arquivo de imagem válido (JPEG ou PNG).';
	}
}
?>
