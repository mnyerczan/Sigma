<?php
/**
 * Kiolvassa a konfigurációs állomány tartalmát és tömbben adja vissza.
 * 
 * @param string $path Path of configuratin file
 * 
 * @return array Content of readed file
 */
function getConfig($confPath)
{
    return json_decode( file_get_contents($confPath),true);
}


/**
 * Az addRout függvény végzi a regisztrációt. A global kulcsszóval lehet elérni
 * külső változót függvényen belülről
 * 
 * @param string $pattern pattern of searched part of url
 * @param string $controller Name of conteroller
 * 
 * @return void
 */
function addRoute($pattern, $controller)
{
    global $routes;
    $routes['%^'.$pattern.'$%'] = $controller;
}

/**
 * A routing függvény végzi a kikeresést. Ha nem talál egyezést a tisztított url
 * és a regisztrált minta között, meghívja a notFoundController-t.
 * 
 * @param string $cleanedUri Part of url
 * 
 * @return void
 */
function routing($cleanedUri)
{
    global $routes;
 
    foreach($routes as $pattern => $controller)
    {        
        if(preg_match($pattern, $cleanedUri, $matches))
        {            
            $controller($matches);
            return;
        }
    }
    notFoundController();
}


/**
 * A view függvény a kapott adatokat kibontja és átadja a layoutnak.
 * A $datas-nak tartalmaznia kell egy 'view' és egy 'title' kulcsot!
 * 
 * @param array $datas Datas from controller to view
 */
function view($datas)
{
    extract($datas);
    require_once APPPATH.'Templates/_layout.php';
}

/**
 * A kapott hibaüzenetet írja ki az Application/Log/dberror.log fájlba
 * Ha a Log/ mappa nem létezik, a fájl létrehozása nem hajtódik végre.
 * 
 * @param string $message Kiírandó hibaüzenet
 */
function errorLog($message)
{   
    
    $path = APPPATH.'Log/dberror.log';
    $msg  = "[".date('Y-m-d H:i:s')."]".$message.PHP_EOL;

    return file_put_contents($path, $msg);
}