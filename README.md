# Input
Lightweight utility package to deal with request input variables. Probably dead

PHP 5.4 + only.

## Usage
    $input = new \Input\Post(); // Get/Server/Cookie/Env or Your Array using \Input\Container( array );

    $value = $input->value('my_post_val', 'default_value');

    $bool = $input->has('var1');

    $all = $input->all();

    $except = $input->except('var1','var2','var3');

    $only = $input->only('var1');


## Sanitize
    $int   = $input->asInt('var1');
    $email = $input->asEmail('email_variable');

## Validate
    $bool = $input->isEmail('email_variable');
    $bool = $input->isAlpha('alpha_var');
    $bool = $input->notAlpha('number_var');

## Files
    $files = new \Input\Files();

    // get single file
    // returns instance of \SplFileInfo
    $file = $files->get('filename');

    // returns instance of new moved file
    $uploadedFile = $file->move('/path/to/dir');

    // can rename files
    $file->setName('newname');
    $uploadedFile = $file->move('/path/to/dir'); // will be named to 'newname'

    // can validate the files
    $bool = $file->hasExtension('pdf');
    $bool = $file->hasExtension(['jpg','png','gif']);
    $bool = $file->hasMimeType('pdf');
    $bool = $file->hasMimeType(['image/jpeg','image/png']);
    $bool = $file->notBiggerThan('1M'); // in bytes or k/m/g/t
