<?php
    ini_set('max_execution_time', 0);
    error_reporting(E_ERROR);
    ini_set("display_errors", 1);
    set_time_limit(0);
?>
<html>
<head>
    <title>Extract Document Editor interface files</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:900,800,700,600,500,400,300&amp;subset=latin,cyrillic-ext,cyrillic,latin-ext" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <p id="back-top" style="display: none">
        <a title="Scroll up" href="#top"></a>
    </p>
    <h1>Extract Document Editor interface files</h1>
<?php

    if(is_dir('files/out/web-apps')==1 || is_dir('files/out/web-apps-pro')==1){
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('files/out'), RecursiveIteratorIterator::SELF_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir('files/out/web-apps');
        rmdir('files/out/web-apps-pro');
    }
    
    $arrayNames = ['document_editor_v2', 'document_editor_mobile', 'document_editor', 'presentation_editor_v2', 'presentation_editor_mobile', 'presentation_editor', 'spreadsheet_editor_v2', 'spreadsheet_editor_mobile', 'spreadsheet_editor'];

    $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('files/src'), RecursiveIteratorIterator::SELF_FIRST);
    foreach($objects as $name => $object){
        $editorDir = '';
        $mobileDir = 'main/';
        $webappsDir = '';
        $dest_name = '';
        $dest_name1 = '';
        
        if(is_dir($name)==1){
            $dest_name = str_replace('src', 'out', $name);
            mkdir($dest_name);
        } else if(is_dir($name)!=1){
            if(strpos(basename($name), 'Document') !== false){
                $editorDir = 'documenteditor';
            } else if(strpos(basename($name), 'Presentation') !== false){
                $editorDir = 'presentationeditor';
            } else if(strpos(basename($name), 'Spreadsheet') !== false){
                $editorDir = 'spreadsheeteditor';
            }
            if(strpos(basename($name), 'Mobile') !== false){
                $mobileDir = 'mobile/';
            }
            //if(strpos(basename($name), 'v2') !== false){
                $webappsDir = 'web-apps-pro';
            /*} else {
                $webappsDir = 'web-apps';
            }*/
            
            $dest_name = 'files/out/' . $webappsDir . '/' . $editorDir . '/' . $mobileDir . 'locale';

            mkdir($dest_name);
            echo '<br />' . $dest_name;
            
            if($mobileDir !== '' /*&& $webappsDir == 'web-apps'*/){
                $dest_name1 = 'files/out/web-apps-pro/' . $editorDir . '/' . $mobileDir . 'locale';
                mkdir($dest_name1);
                echo '<br />' . $dest_name1;
            }
            
            $zip = new ZipArchive;

            if ($zip->open($name) === TRUE) {
                $zip->extractTo($dest_name);
                if($dest_name1 !== ''){
                    $zip->extractTo($dest_name1);
                }
                $zip->close();
            } else {
                echo 'error';
            }
        }
    }
    $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('files/out'), RecursiveIteratorIterator::SELF_FIRST);
    
    foreach($objects as $name => $object){
        if(is_dir($name)!=1 && strpos(basename($name), 'da.json') != true && strpos(basename($name), 'fi.json') != true && strpos(basename($name), 'id.json') != true && strpos(basename($name), 'nb-NO.json') != true && strpos(basename($name), 'pt.json') != true && strpos(basename($name), 'sv.json') != true && strpos(basename($name), 'zh-TW.json') != true && filesize($name) > 1024){
            foreach($arrayNames as $editorName){
                if(strpos($name, $editorName) !== false){
                    $dest_name = str_replace($editorName . '.', '', $name);
                    if(basename($dest_name) == 'json'){
                        $dest_name = str_replace('json', 'en.json', $dest_name);
                    }
                    if(strpos(basename($name), 'pt-BR.json') != false){
                        $dest_name = str_replace('pt-BR', 'pt', $dest_name);
                    }
                    if(strpos(basename($name), 'zh-CN.json') != false){
                        $dest_name = str_replace('zh-CN', 'zh', $dest_name);
                    }
                    rename($name, $dest_name);
                }
            }
        } else if(is_dir($name)!=1){
            unlink($name);
        }
    }
?>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/arrowup.min.js"></script>
</body>
</html>