
<?php
function listFolderFiles($dir){
    $ffs = scandir($dir);

    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);

    // prevent empty ordered elements
    if (count($ffs) < 1)
        return;

    echo '<ol>';

    if (is_dir($dir)) {
        $changedir = str_replace("LegacyFiles", "LegacyFilesInflated", $dir);
        echo 'origin dir : '.$dir.'<br>';
        echo 'changed dir : '.$changedir.'<br>';
        recurse_copy($dir, 'D:/'.$changedir);
    }

    foreach($ffs as $ff){
        echo '<li>'.$ff;
        if(is_dir($dir.'/'.$ff)) {
            listFolderFiles($dir.'/'.$ff);
        }
        echo '</li>';
    }
    echo '</ol>';
}

listFolderFiles('LegacyFiles');

function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                // echo 'is_dir : '.$file.'<br>';
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                // echo 'src : '.$src.'<br>';
                // echo 'non_dir : '.$file.'<br>';
                $content = gzinflate(file_get_contents($src.'/'.$file));

                $changefile = str_replace("dat", "jpg", $file);

                // echo 'dst changefile : '.$dst . '/' . $changefile.'<br>';
                $dir_changefile = $dst . '/' . $changefile;

                file_put_contents($dir_changefile, $content);

                // copy($src . '/' . $file,$dst . '/' . $changefile);
            }
        }
    }
    closedir($dir);
}

?>