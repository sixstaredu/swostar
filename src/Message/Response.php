<?php
namespace SwoStar\Message;

/**
 *
 */
class Response
{
    public static function send($content)
    {
        if (\is_array($content)) {
            return \json_encode($content);
        } else if (\is_string($content)) {
            return $content;
        } else {
            // var_dump($content);
        }
    }
}
