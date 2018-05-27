<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 13.05.2018
 * Time: 23:40
 */

namespace App\Formatter;

class DateFormatter
{
    /**
     * @param array|bool $comments
     * @param string|bool $date
     * @return mixed
     */
    public function formatDateTimeForComments($comments = false, $date = false)
    {
        if (!empty($comments) && is_array($comments)) {
            foreach ($comments as &$comment) {
                $dateTime = $comment['date'];
                $dateArray = explode(' ', $dateTime);
                $dateTimeString = $dateArray[0] . ' в ' . substr($dateArray[1], 0, '-3');
                $comment['date'] = $dateTimeString;
            }
            return $comments;
        } elseif ($date) {
            $dateArray = explode(' ', $date);
            $dateTimeString = $dateArray[0] . ' в ' . substr($dateArray[1], 0, '-3');
            return $dateTimeString;
        }

        return null;
    }
}