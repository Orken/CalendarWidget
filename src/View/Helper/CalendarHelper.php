<?php
namespace CalendarWidget\View\Helper;

use Cake\View\Helper;
use Cake\I18n\Time;

class CalendarHelper extends Helper
{
  public $helpers = ['Html'];

  public function display($month,$year,$options=[])
  {
    $this->Html->script('CalendarWidget.calendar',['block'=>true]);
    $this->Html->css('CalendarWidget.calendar',['block'=>true]);
    $date               = Time::parse($year . '-' . $month . '-01');
    $firstdayOfTheMonth = clone $date;
    $lastdayOfTheMonth  = clone $date;
    $firstdayOfTheMonth->startOfWeek();
    $lastdayOfTheMonth->endOfMonth()->endOfWeek();
    $datasource = (isset($options['datasource'])?$options['datasource']:'');
    $toggle = (isset($options['toggle'])?$options['toggle']:false);
    $modal = (isset($options['modal'])?$options['modal']:false);

    $content = '<div class="calendarwidget" data-datasource="' . $datasource . '"';
    $content.= ($toggle?' data-toggle="' . $toggle . '"':'');
    $content.= ($modal?' data-modal="' . $modal . '"':'');
    $content.= '>';
    $content.= '<div class="month">
      <ul>
        <li class="prev">' . ((isset($options['previous']))?$this->Html->link('&#10094;',$options['previous'],['escape' => false]):'&nbsp;') . '</li>
        <li class="next">' . ((isset($options['next']))?$this->Html->link('&#10095;',$options['next'],['escape' => false]):'&nbsp;') . '</li>
        <li class="text">
          ' .  ucfirst($date->i18nFormat('MMMM')) . '<br>
          <span style="font-size:18px">' . $year .'</span>
        </li>
      </ul>
    </div>';
    $content .= '<ul class="weekdays"><li>Lundi.</li><li>Mardi.</li><li>Mercredi.</li><li>Jeudi.</li><li>Vendredi.</li><li>Samedi.</li><li>Dimanche.</li></ul>';
    $content .= '<ul class="days">';
    for ($i=$firstdayOfTheMonth; $i < $lastdayOfTheMonth; $i->modify('+1 day')) {
      $currentmonth = ($i->month == $date->month)?'current':'prev';
      $content.='<li class="' . $currentmonth . '"><ul class="day" data-date="'.$i->i18nFormat('YYYY-MM-dd').'"><li class="am" data-plage="am"></li><li class="pm" data-plage="pm"></li><li  data-plage="both" class="number"><span>' . $i->i18nFormat('d') . '</span></li></ul></li>';
    }
    $content.= '</ul>';
    $content.= '</div>';
    return  $content;
  }

  private function _getNewDate($date, $newInterval) {
    $newDate = clone $date;
    return $newDate->modify($newInterval);
  }

}