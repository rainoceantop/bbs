<?php


class TimeToHuman{
    private const SECOND = 1;
    private const MINUTE = self::SECOND * 60;
    private const HOUR = self::MINUTE * 60;
    private const DAY = self::HOUR * 24;
    private const WEEK = self::DAY * 7;
    private const MONTH = self::DAY * 30;
    private const YEAR = self::MONTH * 12;
    public $time;

    function __construct(){
        date_default_timezone_set("Asia/Shanghai");
    }
    function init($time){
        $this->time = strtotime($time);
        return $this;
    }

    function format(){
        $gap = time() - $this->time;
        switch($gap){
            case $gap >= 0 and $gap < self::MINUTE:
                return $gap.'秒';
                break;
            case $gap >= self::MINUTE and $gap < self::HOUR:
                return floor($gap/self::MINUTE).'分钟';
                break;
            case $gap >= self::HOUR and $gap < self::DAY:
                return floor($gap/self::HOUR).'小时';
                break;
            case $gap >= self::DAY and $gap < self::WEEK:
                return floor($gap/self::DAY).'天';
                break;
            case $gap >= self::WEEK and $gap < self::MONTH:
                return floor($gap/self::WEEK).'周';
                break;
            case $gap >= self::MONTH and $gap < self::YEAR:
                return floor($gap/self::MONTH).'个月';
                break;
            case $gap >= self::YEAR:
                return date('Y-m-d', $this->time);
        }
    }

    function onlyDate(){
        return date('Y-m-d', $this->time);
    }
}