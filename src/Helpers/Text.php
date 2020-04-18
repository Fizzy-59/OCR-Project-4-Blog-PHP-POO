<?php

namespace App\Helpers;


class Text
{
    /**
     * Cut and make an abstract for content of Post
     *
     * @param  string $content
     * @param  int $limit
     * @return string
     */
    public static function excerpt(string $content, int $limit = 60)
    {
        if(mb_strlen($content) <= $limit)
        {
            return $content;
        }

        // Prevents cutting in the middle of a word
        $lastSpace = mb_strpos($content, ' ', $limit);

        return mb_substr($content, 0, $lastSpace) . '...' ;
    }
}