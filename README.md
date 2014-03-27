# Input
=====

Lighweight utility class to deal with request input variables.

## Usage
    $input = new \Input\Post(); // Get/Server/Cookie/Env or Your Array using \Input\Container( array );

    $value = $input->value('my_post_val', 'default_value');

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

## TODO
File upload is on its way. Some cookie manipulation as well.