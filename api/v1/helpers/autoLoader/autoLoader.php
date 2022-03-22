<?php


spl_autoload_register(function ($className)
{
    
    if (preg_match('@\\\\([\w]+)$@', $className, $matches)) {
        $className = $matches[1];
    }

    //Directories to search
    $directories = array(
        'controllers/contentControllers/',
        'controllers/userControllers/',
        'controllers/eventControllers/',
        'controllers/issueControllers/',
        'config/database/',
        'helpers/JWT/',
        'helpers/Response/',
        'helpers/Request/',
        'helpers/PasswordHandler/',
        'models/content/',
        'models/users/',
        'models/issues/',
        'repository/ORM/',
        'router/',
        'auth/controllers/',
        'auth/services/authentication/',
        'auth/services/authorization/',
        'models/sessions/',
        'models/events/',
        'models/abstract/',
        'models/action/',
        'models/imgs/'
    );
    
    //for each directory
    foreach($directories as $directory)
    {
        //see if the file exsists
        $path = __DIR__."/../../".$directory.$className . '.php';
        if(file_exists($path))
        {
            require_once($path);
            return true;     // if using classes with same name in different folders, then remove the return and change appropriately to require both classes
        }           
    }

    return false;
});