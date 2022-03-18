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
        'config/database/',
        'helpers/JWT/',
        'helpers/Response/',
        'helpers/Request/',
<<<<<<< HEAD
        'models/content/',
        'models/users/',
        'repository/ORM/',
        'router/'
=======
        'helpers/PasswordHandler/',
        'models/content/',
        'models/users/',
        'repository/ORM/',
        'router/',
        'auth/controllers/',
        'auth/services/authentication/',
        'auth/services/authorization/',
        'models/sessions/',
        'models/abstract/',
        'models/action/'
>>>>>>> origin/master
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