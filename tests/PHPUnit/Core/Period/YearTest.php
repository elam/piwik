<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id$
 */
/**
 * Testing Period_Year
 */
class Period_YearTest extends PHPUnit_Framework_TestCase
{
    /**
     * test normal case
     * @group Core
     * @group Period
     * @group Period_Year
     */
    function testYearNormalcase()
    {
        $correct=array(
            '2024-01-01',
            '2024-02-01',
            '2024-03-01',
            '2024-04-01',
            '2024-05-01',
            '2024-06-01',
            '2024-07-01',
            '2024-08-01',
            '2024-09-01',
            '2024-10-01',
            '2024-11-01',
            '2024-12-01',);
        
        $year = new Piwik_Period_Year( Piwik_Date::factory('2024-10-09'));
        $this->assertEquals(12, $year->getNumberOfSubperiods());
        $this->assertEquals($correct, $year->toString());
    }
    
    /**
     * test past
     * @group Core
     * @group Period
     * @group Period_Year
     */
    function testYearPastAndWrongdate()
    {
        $correct=array(
            '2000-01-01',
            '2000-02-01',
            '2000-03-01',
            '2000-04-01',
            '2000-05-01',
            '2000-06-01',
            '2000-07-01',
            '2000-08-01',
            '2000-09-01',
            '2000-10-01',
            '2000-11-01',
            '2000-12-01',
        );
        
        $week = new Piwik_Period_Year( Piwik_Date::factory('2000-02-15'));
        $this->assertEquals(12, $week->getNumberOfSubperiods());
        $this->assertEquals($correct, $week->toString());
    }
}