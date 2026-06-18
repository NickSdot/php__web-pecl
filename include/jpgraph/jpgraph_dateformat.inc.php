<?php

/**
 * Small formatter for JpGraph's supported date labels.
 */
function _jpgraph_strftime($format, $timestamp = null)
{
    $timestamp = $timestamp === null ? time() : (int) $timestamp;
    $result = '';
    $length = strlen($format);

    for ($i = 0; $i < $length; $i++) {
        if ($format[$i] !== '%' || $i === $length - 1) {
            $result .= $format[$i];
            continue;
        }

        $token = $format[++$i];

        switch ($token) {
            case 'a':
                $result .= date('D', $timestamp);
                break;
            case 'A':
                $result .= date('l', $timestamp);
                break;
            case 'b':
                $result .= date('M', $timestamp);
                break;
            case 'B':
                $result .= date('F', $timestamp);
                break;
            case 'd':
                $result .= date('d', $timestamp);
                break;
            case 'e':
                $result .= date('j', $timestamp);
                break;
            case 'j':
                $result .= sprintf('%03d', date('z', $timestamp) + 1);
                break;
            case 'm':
                $result .= date('m', $timestamp);
                break;
            case 'w':
                $result .= date('w', $timestamp);
                break;
            case 'y':
                $result .= date('y', $timestamp);
                break;
            case 'Y':
                $result .= date('Y', $timestamp);
                break;
            case '%':
                $result .= '%';
                break;
            default:
                $result .= '%'.$token;
        }
    }

    return $result;
}
