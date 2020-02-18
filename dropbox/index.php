<?
	function format_size_units($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
	}
	
	function get_file_types()
	{
		return 	array(
			'apk' 	=> 'android',
			'exe'	=> 'application-list',
			'dmg' 	=> 'box',
			'accdb' => 'document-access',
			'htm'	=> 'document-code',
			'html'	=> 'document-code',
			'epub'	=> 'document-epub-text',
			'xlsx'	=> 'document-excel-table',
			'xls'	=> 'document-excel-table',
			'fla'	=> 'document-flash',
			'swf'	=> 'document-flash-movie',
			'ai'	=> 'document-illustrator',
			'mp3'	=> 'document-music-playlist',
			'wav'	=> 'document-music-playlist',
			'flac'	=> 'document-music-playlist',
			'aac'	=> 'document-music-playlist',
			'eml'	=> 'mail-air',
			'pdf'	=> 'document-pdf',
			'psd'	=> 'document-photoshop',
			'php'	=> 'document-php',
			'ppt'	=> 'document-powerpoint',
			'pptx'	=> 'document-powerpoint',
			'doc'	=> 'document-word',
			'docx'	=> 'document-word',
			'avi'	=> 'film',
			'mp4'	=> 'film',
			'flv'	=> 'film',
			'jpg'	=> 'image',
			'gif'	=> 'image-empty',
			'bmp'	=> 'picture',
			'json'	=> 'json',
			'ipa'	=> 'media-player-phone',
			'rtf'	=> 'report',
			'cs'	=> 'script-visual-studio',
			'asp'	=> 'script-visual-studio',
			'aspx'	=> 'script-visual-studio',
			'ascx'	=> 'script-visual-studio',
			'as'	=> 'script-flash',
			'js'	=> 'script-code',
			'css'	=> 'document-block',
			'sql'	=> 'sql',
			'png' 	=> 'image-sunset', 
			'txt' 	=> 'sticky-note',
			'dir' 	=> 'folder',
			'zip' 	=> 'folder-zipper',
			'air' 	=> 'box'
		);
	}
	
	function get_directory_list() 
	{
		$path = '.';
		
		//file types
		$file_types = get_file_types();
		
		//base path
		$image_base = '../lib/img/ico/16/'; 
		
		// create an arrays to hold directory content
		$files = array();
		
		$directories = array();

		// create a handler for the directory
		$handler = opendir($path);

		// open directory and walk through the filenames
		while ($file = readdir($handler))
		{
			// if file isn't this directory or its parent, add it to the results
			if ($file != "." && $file != ".." && $file != ".svn" && $file != "index.php")
			{
				$is_dir = is_dir($path."/".$file);
				
				$file_extension = pathinfo($file, PATHINFO_EXTENSION);
				
				$image = ($file_types[$file_extension] != "") ? $file_types[$file_extension] : "document";
				
				$file_size = format_size_units( filesize($file) );
				
				$file_date = date ("d/m/Y H:i", fileatime($file));
				
				$file_path = $file;
				
				if($is_dir)
				{
					$file_size = "-";
					$image = $file_types["dir"];
				}
				
				$icon_path = $image_base . $image . ".png";
				
				$object = array(
					'name' => 		$file, 
					'size' => 		$file_size,
					'modified'=> 	$file_date, 
					'image' => 		$icon_path,
					'path' =>		$file_path
				);
				
				if($is_dir)
				{
					$directories[] = $object;	
				}
				else
				{
					$files[] = $object;
				}
			}
		}
		// tidy up: close the handler
		closedir($handler);
		
		// done!
		return array_merge($directories, $files);
	}
	
	$list = get_directory_list();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Dropbox</title>
		<style type="text/css">
			body{
				margin:10px;
				font-family:'Arial';
				background-color:#ECEEF1;
				color:#61666C;
				font-size:16px;
			}
			
			body > div{
				padding:30px 50px 40px 50px;
				background-color:#fff;		
			}
			
			table{
				width:100%;	
			}
			
			table thead th{
				text-align:left;
				font-size:12px;
				color:#999;
				padding:12px 0 12px 10px;
				border-bottom:1px solid #edf1f5;
			}
			
			table tbody td{
				padding:12px 0px 12px 10px;
				border-bottom:1px solid #edf1f5;
			}
			
			table tbody tr:hover{
				background-color:#f6f6f6;		
			}
			
			table tbody td:first-child{
				width:16px;
				vertical-align:middle;
			}
			
			table tbody td:first-child img{
				vertical-align:middle;
			}
			
			table tbody td:nth-child(3)
			{
				width:200px;		
			}
			
			table tbody td:last-child{
				width:100px;
			}
			
			a{
				text-decoration:none;
				color:#0471d5;
			}
			
			a:hover{
				color:#266095;	
			}
			
		</style>
	</head>
	<body>
		<div>
			<table cellpadding="0" cellspacing="0" border="0">
				<thead>
					<th>&nbsp;</th>
					<th>Name</th>
					<th>Last modified</th>
					<th>Size</th>
				</thead>
				<tbody>
					<? foreach($list as $item){ ?>
						<tr>
							<td><img src="<?=$item['image']?>"/></td>
							<td>
								<a href="http://www.grantmeek.co.uk/dropbox/<?=$item['path']?>">
									<?=$item['name']?>
								</a>
							</td>
							<td><?=$item['modified']?></td>
							<td><?=$item['size']?></td>
						</tr>
					<? } ?>
				</tbody>
			</table>
		</div>
	</body>
</html> 