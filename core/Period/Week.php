<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id$
 * 
 * @category Piwik
 * @package Piwik
 */

/**
 * @package Piwik
 * @subpackage Piwik_Period
 */
class Piwik_Period_Week extends Piwik_Period
{
	protected $label = 'week';

	/**
	 * Returns the current period as a localized short string
	 *
	 * @return string
	 */
	public function getLocalizedShortString()
	{
		//"30 Dec - 6 Jan 09"
		$dateStart = $this->getDateStart();
		$dateEnd = $this->getDateEnd();
		$shortDateStart = $dateStart->getLocalized("%day% %shortMonth%");
		$shortDateEnd = $dateEnd->getLocalized("%day% %shortMonth% %shortYear%");
		$out = "$shortDateStart - $shortDateEnd";
		return $out;
	}

	/**
	 * Returns the current period as a localized long string
	 *
	 * @return string
	 */
	public function getLocalizedLongString()
	{
		$shortDateStart = $this->getDateStart()->getLocalized("%day% %longMonth%");
		$shortDateEnd =  $this->getDateEnd()->getLocalized("%day% %longMonth% %longYear%");
		return Piwik_Translate('CoreHome_PeriodWeek') . " $shortDateStart - $shortDateEnd";
	}

	/**
	 * Returns the current period as a string
	 *
	 * @return string
	 */
	public function getPrettyString()
	{
		$out = Piwik_Translate('General_DateRangeFromTo', 
		            array($this->getDateStart()->toString(),
		                $this->getDateEnd()->toString())
        );
		return $out;
	}

	/**
	 * Generates the subperiods - one for each day in the week
	 */
	protected function generate()
	{
		if($this->subperiodsProcessed)
		{
			return;
		}
		parent::generate();
		$date = $this->date;
		
		if( $date->toString('N') > 1)
		{
			$date = $date->subDay($date->toString('N')-1);
		}
		
		$startWeek = $date;
		
		$currentDay = clone $startWeek;
		while($currentDay->compareWeek($startWeek) == 0)
		{
			$this->addSubperiod(new Piwik_Period_Day($currentDay) );
			$currentDay = $currentDay->addDay(1);
		}
	}
}
