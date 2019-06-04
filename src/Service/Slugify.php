<?php

namespace App\Service;

class Slugify
{
    /**
     * @param string $input
     * @return string
     */

    const REPLACEMENT = '/[[:punct:]]/';

    public function generate(string $input) : string{
        return  iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE',
           (str_replace(' ', '-',
               (preg_replace(self::REPLACEMENT,'',trim($input))))));
    }
}
